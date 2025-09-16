<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ingredient;

class OrderController extends Controller {
    public function index(){
        $orders = Order::with('supplier','items.ingredient')->get();
        return view('orders.index', compact('orders'));
    }
    public function show($id){
        $order = Order::with('items.ingredient')->findOrFail($id);
        return view('orders.show', compact('order'));
    }
    public function store(Request $r){
        // For simplicity, accept supplier_id and items array
        $order = Order::create(['supplier_id'=>$r->supplier_id,'date'=>now(),'status'=>'pending','total'=>0]);
        $total = 0;
        foreach($r->items as $it){
            $subtotal = $it['quantity'] * $it['unit_cost'];
            OrderItem::create(['order_id'=>$order->id,'ingredient_id'=>$it['ingredient_id'],'quantity'=>$it['quantity'],'unit_cost'=>$it['unit_cost'],'subtotal'=>$subtotal]);
            $total += $subtotal;
        }
        $order->update(['total'=>$total]);
        return redirect()->route('orders.index');
    }
    public function receive($id){
        $order = Order::with('items.ingredient')->findOrFail($id);
        foreach($order->items as $it){
            $ing = Ingredient::find($it->ingredient_id);
            $ing->stock += $it->quantity;
            $ing->save();
        }
        $order->status = 'received';
        $order->save();
        return redirect()->route('orders.index');
    }
}
