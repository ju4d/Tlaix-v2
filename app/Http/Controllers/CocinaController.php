<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDish;
use Illuminate\Support\Facades\DB;

class CocinaController extends Controller
{
    public function index()
    {
    $orders = Order::with('dishes.dish')->where('status', 'pending')->orderBy('created_at')->get();
        $historico = OrderDish::with('dish', 'order')
            ->where('completed', true)
            ->orderByDesc('updated_at')
            ->take(20)
            ->get();
    return view('cocina.index', compact('orders', 'historico'));
    }

    public function complete($orderId, $itemId)
    {
        $item = OrderDish::findOrFail($itemId);
        $item->completed = true;
        $item->save();

        // Descontar insumos del platillo
        $dish = $item->dish;
        if ($dish) {
            foreach ($dish->ingredients as $ingredient) {
                $cantidad_usar = $ingredient->pivot->quantity_required * $item->quantity;
                $ingredient->stock = max(0, $ingredient->stock - $cantidad_usar);
                $ingredient->save();
            }
        }

        // Si todos los platillos de la orden están completos, marcar la orden como completada
        $order = $item->order;
        if ($order->dishes()->where('completed', false)->count() === 0) {
            $order->status = 'completada'; // Debe coincidir con el enum de la migración
            $order->save();
        }
        return back()->with('success', 'Platillo marcado como hecho y stock descontado.');
    }
}
