<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerOrder;
use App\Models\CustomerOrderDish;
use App\Models\Dish;
use Illuminate\Support\Facades\Auth;

class MeseroController extends Controller
{
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
        foreach($dishIds as $dishId) {
            $qty = isset($quantities[$dishId]) ? intval($quantities[$dishId]) : 1;
            CustomerOrderDish::create([
                'customer_order_id' => $order->id,
                'dish_id' => $dishId,
                'quantity' => $qty,
                'completed' => false,
            ]);
        }
        return redirect()->route('mesero.index')->with('success', 'Orden enviada a cocina.');
    }

    public function destroy($id)
    {
        $order = CustomerOrder::findOrFail($id);
        $order->delete();
        return redirect()->route('mesero.index')->with('success', 'Orden cancelada correctamente.');
    }
}
