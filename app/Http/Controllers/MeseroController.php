<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerOrder;
use App\Models\CustomerOrderDish;
use App\Models\Dish;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MeseroController extends Controller
{
    private $historyFile;

    public function __construct()
    {
        $this->historyFile = storage_path('app/predictions/history.csv');
    }
    public function index()
    {
        $dishes = Dish::all();
        
        // Verificar disponibilidad de ingredientes para cada platillo
        $dishesAvailability = [];
        foreach ($dishes as $dish) {
            $dishesAvailability[$dish->id] = $this->checkDishAvailability($dish);
        }
        
        $orders = CustomerOrder::with('dishes.dish')->orderBy('created_at', 'desc')->take(10)->get();
        return view('mesero.index', compact('dishes', 'orders', 'dishesAvailability'));
    }

    /**
     * Verificar si hay suficientes ingredientes para preparar un platillo
     */
    private function checkDishAvailability($dish)
    {
        if (!$dish->ingredients || $dish->ingredients->count() === 0) {
            return ['available' => true, 'message' => 'Disponible'];
        }

        foreach ($dish->ingredients as $ingredient) {
            $requiredQty = $ingredient->pivot->quantity_required;
            $currentStock = $ingredient->stock;

            if ($currentStock < $requiredQty) {
                return [
                    'available' => false,
                    'message' => "Sin stock: {$ingredient->name}",
                    'ingredient' => $ingredient->name,
                    'required' => $requiredQty,
                    'current' => $currentStock
                ];
            }
        }

        return ['available' => true, 'message' => 'Disponible'];
    }

    public function store(Request $request)
    {
        $request->validate([
            'dishes' => 'required|array|min:1',
            'quantities' => 'required|array',
        ]);
        $dishIds = $request->dishes;
        $quantities = $request->quantities;
        
        // Verificar que todos los platillos tengan stock antes de crear la orden
        foreach ($dishIds as $dishId) {
            $dish = Dish::findOrFail($dishId);
            $availability = $this->checkDishAvailability($dish);
            
            if (!$availability['available']) {
                return redirect()->back()->with('error', "No hay stock disponible para: {$availability['message']}");
            }
        }
        
        $order = CustomerOrder::create([
            'status' => 'pending',
            'user_id' => Auth::id(),
        ]);

        $totalDishes = 0;
        foreach($dishIds as $dishId) {
            $qty = isset($quantities[$dishId]) ? intval($quantities[$dishId]) : 1;
            CustomerOrderDish::create([
                'customer_order_id' => $order->id,
                'dish_id' => $dishId,
                'quantity' => $qty,
                'completed' => false,
            ]);
            $totalDishes += $qty;
        }

        // 🔥 ACTUALIZAR DEMANDA EN TIEMPO REAL
        $this->updateDemandHistory($totalDishes);

        Log::info('Orden creada y demanda actualizada', [
            'order_id' => $order->id,
            'dishes_count' => $totalDishes,
            'user' => Auth::user()->name
        ]);

        return redirect()->route('mesero.index')->with('success', 'Orden enviada a cocina.');
    }

    public function destroy($id)
    {
        $order = CustomerOrder::findOrFail($id);
        $order->delete();
        return redirect()->route('mesero.index')->with('success', 'Orden cancelada correctamente.');
    }

    /**
     * 🔥 Actualiza el archivo de demanda histórica en tiempo real
     */
    private function updateDemandHistory($quantity)
    {
        try {
            $today = Carbon::today()->format('Y-m-d');
            
            // Asegurar que el directorio existe
            $directory = dirname($this->historyFile);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $data = $this->loadHistoryData();

            // Buscar si ya existe el registro para hoy
            $found = false;
            foreach ($data as $key => $row) {
                if ($row['date'] === $today) {
                    // Acumular demanda del mismo día
                    $data[$key]['demand'] = floatval($row['demand']) + floatval($quantity);
                    $found = true;
                    break;
                }
            }

            // Si no existe, agregar nuevo registro
            if (!$found) {
                $data[] = [
                    'date' => $today,
                    'demand' => floatval($quantity)
                ];
            }

            // Ordenar por fecha
            usort($data, function($a, $b) {
                return strcmp($a['date'], $b['date']);
            });

            // Guardar en CSV
            $fp = fopen($this->historyFile, 'w');
            fputcsv($fp, ['date', 'demand']);

            foreach ($data as $row) {
                fputcsv($fp, [$row['date'], $row['demand']]);
            }

            fclose($fp);

            Log::info('Demanda actualizada en history.csv', [
                'date' => $today,
                'quantity_added' => $quantity,
                'total_today' => $found ? $data[array_search($today, array_column($data, 'date'))]['demand'] : $quantity
            ]);

        } catch (\Exception $e) {
            Log::error('Error actualizando demanda', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Carga datos históricos del CSV
     */
    private function loadHistoryData()
    {
        if (!file_exists($this->historyFile)) {
            return [];
        }

        $data = [];
        $fp = fopen($this->historyFile, 'r');

        // Saltar encabezado
        fgetcsv($fp);

        while (($row = fgetcsv($fp)) !== false) {
            if (count($row) >= 2) {
                $data[] = [
                    'date' => $row[0],
                    'demand' => floatval($row[1])
                ];
            }
        }

        fclose($fp);
        return $data;
    }
}
