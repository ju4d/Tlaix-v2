<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\Supplier;
use App\Models\Order;
use App\Models\OrderItem;

class InventoryController extends Controller {
    public function index(){
        $ingredients = Ingredient::with('supplier')->get();
        return view('inventory.index', compact('ingredients'));
    }
    public function create(){
        $suppliers = Supplier::all();
        return view('inventory.create', compact('suppliers'));
    }
    public function store(Request $r){
        Ingredient::create($r->only(['name','category','expiration_date','stock','min_stock','unit','supplier_id','cost']));
        return redirect()->route('inventory.index');
    }
    public function edit($id){
        $item = Ingredient::findOrFail($id);
        $suppliers = Supplier::all();
        return view('inventory.edit', compact('item','suppliers'));
    }
    public function update(Request $r,$id){
        $item = Ingredient::findOrFail($id);
        $item->update($r->only(['name','category','expiration_date','stock','min_stock','unit','supplier_id','cost']));
        // check auto-order
        if($item->stock < $item->min_stock){
            $this->generateOrderForIngredient($item);
        }
        return redirect()->route('inventory.index');
    }
    public function destroy($id){
        Ingredient::destroy($id);
        return redirect()->route('inventory.index');
    }

    protected function generateOrderForIngredient(Ingredient $item){
        // Very simple: create order with supplier, quantity = min_stock*2 - stock (example)
        $supplierId = $item->supplier_id;
        if(!$supplierId) return;
        $needed = max(1, ($item->min_stock * 2) - $item->stock);
        $order = Order::create(['supplier_id'=>$supplierId,'date'=>now(),'status'=>'pending','total'=>0]);
        $subtotal = $needed * ($item->cost ?? 1);
        OrderItem::create(['order_id'=>$order->id,'ingredient_id'=>$item->id,'quantity'=>$needed,'unit_cost'=>$item->cost,'subtotal'=>$subtotal]);
        $order->update(['total'=>$subtotal]);
    }
}
