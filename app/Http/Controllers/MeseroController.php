<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDish;
use App\Models\Dish;
use Illuminate\Support\Facades\Auth;

class MeseroController extends Controller
{
    public function index()
    {
        $dishes = Dish::all();
    $orders = Order::with('dishes.dish')->orderBy('created_at', 'desc')->take(10)->get();
    return view('mesero.index', compact('dishes', 'orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dishes' => 'required|array|min:1',
            'quantities' => 'required|array',
        ]);
        $dishIds = $request->dishes;
        $quantities = $request->quantities;
        $order = Order::create([
            'status' => 'pending',
            'user_id' => Auth::id(),
        ]);
        foreach($dishIds as $dishId) {
            $qty = isset($quantities[$dishId]) ? intval($quantities[$dishId]) : 1;
            OrderDish::create([
                'order_id' => $order->id,
                'dish_id' => $dishId,
                'quantity' => $qty,
                'completed' => false,
            ]);
        }
        return redirect()->route('mesero.index')->with('success', 'Orden enviada a cocina.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('mesero.index')->with('success', 'Orden cancelada correctamente.');
    }
}
