<?php
// Reemplaza completamente el archivo app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ingredient;
use App\Models\Supplier;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller {
    public function index(){
        $orders = Order::with('supplier','items.ingredient')->orderBy('created_at', 'desc')->get();
        return view('orders.index', compact('orders'));
    }

    public function show($id){
        $order = Order::with('items.ingredient','supplier')->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function create(){
        $suppliers = Supplier::all();
        $ingredients = Ingredient::all();

        // Debug: verificar que tenemos datos
        Log::info('Creating order form', [
            'suppliers_count' => $suppliers->count(),
            'ingredients_count' => $ingredients->count()
        ]);

        return view('orders.create', compact('suppliers', 'ingredients'));
    }

    public function store(Request $request){
        Log::info('Order creation attempt', [
            'all_data' => $request->all(),
            'items' => $request->input('items', [])
        ]);

        try {
            // Validación paso a paso
            $validated = $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'date' => 'required|date',
                'items' => 'required|array|min:1',
            ]);

            // El status siempre será 'pending' para nuevos pedidos
            $status = 'pending';

            // Validación adicional para items
            foreach($request->input('items', []) as $index => $item) {
                $request->validate([
                    "items.{$index}.ingredient_id" => 'required|exists:ingredients,id',
                    "items.{$index}.quantity" => 'required|numeric|min:0.01',
                    "items.{$index}.unit_cost" => 'required|numeric|min:0',
                ]);

                // Validar que el ingrediente pertenezca al proveedor seleccionado
                $ingredient = Ingredient::find($item['ingredient_id']);
                if (!$ingredient || $ingredient->supplier_id != $request->supplier_id) {
                    throw new \Exception("El ingrediente '{$ingredient->name}' no pertenece al proveedor seleccionado.");
                }
            }

            DB::beginTransaction();

            // Crear la orden
            $order = Order::create([
                'supplier_id' => $request->supplier_id,
                'date' => $request->date,
                'status' => $status,
                'total' => 0
            ]);

            Log::info('Order created', ['order_id' => $order->id, 'status' => $status]);

            $total = 0;
            $itemsCreated = 0;

            foreach($request->items as $item){
                if (!empty($item['ingredient_id']) &&
                    isset($item['quantity']) && $item['quantity'] > 0 &&
                    isset($item['unit_cost']) && $item['unit_cost'] >= 0) {

                    $subtotal = floatval($item['quantity']) * floatval($item['unit_cost']);

                    $orderItem = OrderItem::create([
                        'order_id' => $order->id,
                        'ingredient_id' => $item['ingredient_id'],
                        'quantity' => $item['quantity'],
                        'unit_cost' => $item['unit_cost'],
                        'subtotal' => $subtotal
                    ]);

                    Log::info('Order item created', [
                        'order_item_id' => $orderItem->id,
                        'ingredient_id' => $item['ingredient_id'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $subtotal
                    ]);

                    $total += $subtotal;
                    $itemsCreated++;
                }
            }

            // Actualizar total de la orden
            $order->update(['total' => $total]);

            Log::info('Order completed', [
                'order_id' => $order->id,
                'total' => $total,
                'items_created' => $itemsCreated,
                'status' => $status
            ]);

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'Pedido creado exitosamente con ' . $itemsCreated . ' productos (Estado: Pendiente)');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error in order creation', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating order', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return back()->withErrors(['error' => 'Error al crear el pedido: ' . $e->getMessage()])->withInput();
        }
    }

    public function receive($id){
        try {
            $order = Order::with('items.ingredient')->findOrFail($id);

            if ($order->status !== 'pending') {
                return redirect()->route('orders.index')->withErrors(['error' => 'El pedido no puede ser recibido']);
            }

            DB::beginTransaction();

            // Update ingredient stock
            foreach($order->items as $item){
                $ingredient = Ingredient::find($item->ingredient_id);
                if ($ingredient) {
                    $ingredient->stock += $item->quantity;
                    $ingredient->save();

                    Log::info('Stock updated', [
                        'ingredient_id' => $ingredient->id,
                        'ingredient_name' => $ingredient->name,
                        'added_quantity' => $item->quantity,
                        'new_stock' => $ingredient->stock
                    ]);
                }
            }

            // Update order status
            $order->status = 'received';
            $order->save();

            // Update dish availability after stock changes
            $this->updateDishAvailability();

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Pedido recibido y stock actualizado');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error receiving order', [
                'order_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('orders.index')->withErrors(['error' => 'Error al recibir el pedido: ' . $e->getMessage()]);
        }
    }

    public function cancel($id){
        try {
            $order = Order::findOrFail($id);

            if ($order->status !== 'pending') {
                return redirect()->route('orders.index')->withErrors(['error' => 'Solo se pueden cancelar pedidos pendientes']);
            }

            $order->status = 'cancelled';
            $order->save();

            Log::info('Order cancelled', ['order_id' => $order->id]);

            return redirect()->route('orders.index')->with('success', 'Pedido cancelado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error cancelling order', [
                'order_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('orders.index')->withErrors(['error' => 'Error al cancelar el pedido: ' . $e->getMessage()]);
        }
    }

    private function updateInventoryFromOrder($order) {
        foreach($order->items as $item){
            $ingredient = Ingredient::find($item->ingredient_id);
            if ($ingredient) {
                $ingredient->stock += $item->quantity;
                $ingredient->save();
            }
        }

        // Update dish availability
        $this->updateDishAvailability();
    }

    private function updateDishAvailability() {
        $dishes = \App\Models\Dish::with('ingredients')->get();
        foreach ($dishes as $dish) {
            $available = true;
            foreach ($dish->ingredients as $ingredient) {
                if ($ingredient->stock < $ingredient->pivot->quantity_required) {
                    $available = false;
                    break;
                }
            }
            $dish->update(['available' => $available]);
        }
    }
}
