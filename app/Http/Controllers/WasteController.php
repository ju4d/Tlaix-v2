<?php

namespace App\Http\Controllers;

use App\Models\WasteRecord;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WasteController extends Controller
{
    public function index()
    {
        $dateStart = request('date_start') 
            ? Carbon::parse(request('date_start'))->startOfDay()
            : Carbon::now()->startOfMonth();
        $dateEnd = request('date_end')
            ? Carbon::parse(request('date_end'))->endOfDay()
            : Carbon::now()->endOfDay();

        $wasteRecords = WasteRecord::with('ingredient')
            ->whereBetween('created_at', [$dateStart, $dateEnd])
            ->get();

        $totalLoss = $wasteRecords->sum('total_cost');
        $totalTax = $wasteRecords->sum('tax_amount');
        $totalWithTax = $wasteRecords->sum('total_cost_with_tax');

        // Agrupar los registros por razón
        $wasteByReason = $wasteRecords->groupBy('reason')
            ->map(function($group) {
                return [
                    'total' => $group->sum(function($record) {
                        return $record->quantity * $record->unit_cost_at_time;
                    }),
                    'count' => $group->count(),
                    'records' => $group->map(function($record) {
                        return [
                            'date' => $record->created_at->format('d/m/Y H:i'),
                            'ingredient' => $record->ingredient->name,
                            'quantity' => $record->quantity,
                            'comments' => $record->comments,
                            'total_cost' => $record->quantity * $record->unit_cost_at_time
                        ];
                    })
                ];
            });




        // Debug de las consultas
        \Log::info('SQL Waste By Reason:', [
            'query' => DB::table('waste_records')
                ->select('reason', DB::raw('SUM(quantity * unit_cost_at_time) as total_loss'))
                ->whereBetween('created_at', [$dateStart, $dateEnd])
                ->groupBy('reason')
                ->toSql(),
            'bindings' => [$dateStart, $dateEnd]
        ]);

        $ingredients = Ingredient::all();


        return view('waste.index', compact(
            'wasteRecords',
            'totalLoss',
            'totalTax',
            'totalWithTax',
            'wasteByReason',
            'ingredients'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'required|in:expired,damaged_in_storage,customer_return,inventory_error,theft_loss,internal_use,other',
            'comments' => 'required_if:reason,other|nullable|string',
        ]);

        $ingredient = Ingredient::findOrFail($validated['ingredient_id']);
        if (!$ingredient->cost) {
            return back()->withErrors(['error' => 'El ingrediente no tiene un costo registrado.'])->withInput();
        }

        $ingredient = Ingredient::findOrFail($validated['ingredient_id']);
        
        // Debug del ingrediente
        \Log::info('Datos del ingrediente:', [
            'ingredient_id' => $ingredient->id,
            'cost' => $ingredient->cost,
            'raw_ingredient' => $ingredient->toArray()
        ]);

        if (!$ingredient->cost || $ingredient->cost <= 0) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'El ingrediente seleccionado no tiene un costo válido registrado.']);
        }

        DB::transaction(function() use ($validated, $ingredient) {
            // Crear el registro de merma
            $waste = WasteRecord::create([
                'ingredient_id' => $validated['ingredient_id'],
                'quantity' => $validated['quantity'],
                'reason' => $validated['reason'],
                'comments' => $validated['comments'] ?? '',
                'unit_cost_at_time' => (float) $ingredient->cost
            ]);

            \Log::info('Registro de merma creado:', $waste->toArray());

            // Actualizar el inventario
            $ingredient->decrement('stock', $validated['quantity']);
        });

        return redirect()->route('waste.index')->with('success', 'Merma registrada correctamente');
    }
}