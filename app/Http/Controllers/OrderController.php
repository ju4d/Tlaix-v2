<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ingredient;

class OrderController extends Controller {
    public function index(){
        $orders = Order::with('supplier','items.ingredient')->orderBy('created_at', 'desc')->get();
        return view('orders.index', compact('orders'));
    }

    public function show($id){
        $order = Order::with('items.ingredient','supplier')->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function store(Request $r){
        // For simplicity, accept supplier_id and items array
        $order = Order::create([
            'supplier_id' => $r->supplier_id,
            'date' => now(),
            'status' => 'pending',
            'total' => 0
        ]);

        $total = 0;
        foreach($r->items as $it){
            $subtotal = $it['quantity'] * $it['unit_cost'];
            OrderItem::create([
                'order_id' => $order->id,
                'ingredient_id' => $it['ingredient_id'],
                'quantity' => $it['quantity'],
                'unit_cost' => $it['unit_cost'],
                'subtotal' => $subtotal
            ]);
            $total += $subtotal;
        }
        $order->update(['total' => $total]);
        return redirect()->route('orders.index')->with('success', 'Order created successfully');
    }

    public function receive($id){
        $order = Order::with('items.ingredient')->findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->route('orders.index')->withErrors(['error' => 'Order cannot be received']);
        }

        // Update ingredient stock
        foreach($order->items as $item){
            $ingredient = Ingredient::find($item->ingredient_id);
            if ($ingredient) {
                $ingredient->stock += $item->quantity;
                $ingredient->save();
            }
        }

        // Update order status
        $order->status = 'received';
        $order->save();

        // Update dish availability after stock changes
        $this->updateDishAvailability();

        return redirect()->route('orders.index')->with('success', 'Order received and stock updated');
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
