<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Dish;
use App\Models\Ingredient;

class DishController extends Controller {
    public function index(){
        $dishes = Dish::with('ingredients')->get();
        return view('dishes.index', compact('dishes'));
    }
    public function create(){ $ingredients = Ingredient::all(); return view('dishes.create', compact('ingredients')); }
    public function store(Request $r){
        $r->validate([
            'name' => 'required|unique:dishes,name',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string'
        ]);
        
        $dish = Dish::create($r->only(['name','description','price','available']));
        if($r->ingredients){
            foreach($r->ingredients as $ingId=>$qty){
                $dish->ingredients()->attach($ingId,['quantity_required'=>$qty]);
            }
        }
        $this->updateAvailability($dish);
        return redirect()->route('dishes.index')->with('success', 'Platillo creado exitosamente.');
    }
    public function edit($id){ $dish = Dish::with('ingredients')->findOrFail($id); $ingredients = Ingredient::all(); return view('dishes.edit',compact('dish','ingredients')); }
    public function update(Request $r,$id){
        $dish = Dish::findOrFail($id);
        $dish->update($r->only(['name','description','price','available']));
        $dish->ingredients()->sync([]);
        if($r->ingredients){
            foreach($r->ingredients as $ingId=>$qty) $dish->ingredients()->attach($ingId,['quantity_required'=>$qty]);
        }
        $this->updateAvailability($dish);
        return redirect()->route('dishes.index');
    }
    public function destroy($id){ Dish::destroy($id); return redirect()->route('dishes.index'); }

    protected function updateAvailability(Dish $dish){
        foreach($dish->ingredients as $ing){
            if($ing->stock < $ing->pivot->quantity_required){
                $dish->available = false;
                $dish->save();
                return;
            }
        }
        $dish->available = true;
        $dish->save();
    }
}
