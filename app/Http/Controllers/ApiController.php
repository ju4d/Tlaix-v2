<?php
// app/Http/Controllers/ApiController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\Dish;
use App\Models\Order;
use Carbon\Carbon;

class ApiController extends Controller {

    public function wasteReport() {
        $expired = Ingredient::whereNotNull('expiration_date')
            ->where('expiration_date', '<', Carbon::today())
            ->get();

        $wasteValue = $expired->sum(function($item) {
            return $item->stock * ($item->cost ?? 0);
        });

        return response()->json([
            'expired_items' => $expired,
            'total_waste_value' => $wasteValue,
            'items_count' => $expired->count()
        ]);
    }

    public function dashboardStats() {
        $stats = [
            'total_ingredients' => Ingredient::count(),
            'low_stock' => Ingredient::whereColumn('stock', '<', 'min_stock')->count(),
            'total_dishes' => Dish::count(),
            'available_dishes' => Dish::where('available', true)->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'expired_items' => Ingredient::whereNotNull('expiration_date')
                ->where('expiration_date', '<', Carbon::today())
                ->count(),
            'expiring_soon' => Ingredient::whereNotNull('expiration_date')
                ->whereBetween('expiration_date', [Carbon::today(), Carbon::today()->addDays(3)])
                ->count()
        ];

        return response()->json($stats);
    }

    public function inventoryStatus() {
        $ingredients = Ingredient::select('id', 'name', 'stock', 'min_stock', 'unit', 'expiration_date')
            ->get()
            ->map(function($item) {
                $status = 'normal';
                if ($item->stock < $item->min_stock) {
                    $status = 'low_stock';
                }
                if ($item->expiration_date && Carbon::parse($item->expiration_date)->isPast()) {
                    $status = 'expired';
                } elseif ($item->expiration_date && Carbon::parse($item->expiration_date)->diffInDays(Carbon::today()) <= 3) {
                    $status = 'expiring_soon';
                }

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'stock' => $item->stock,
                    'min_stock' => $item->min_stock,
                    'unit' => $item->unit,
                    'expiration_date' => $item->expiration_date,
                    'status' => $status
                ];
            });

        return response()->json($ingredients);
    }

    public function updateStock(Request $request, $id) {
        $ingredient = Ingredient::findOrFail($id);

        $request->validate([
            'stock' => 'required|numeric|min:0'
        ]);

        $oldStock = $ingredient->stock;
        $ingredient->stock = $request->stock;
        $ingredient->save();

        // Check if auto-order is needed
        if ($ingredient->stock < $ingredient->min_stock && $ingredient->supplier_id) {
            $this->generateAutoOrder($ingredient);
        }

        // Update dish availability
        $this->updateDishAvailability();

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully',
            'old_stock' => $oldStock,
            'new_stock' => $ingredient->stock,
            'auto_order_created' => $ingredient->stock < $ingredient->min_stock
        ]);
    }

    private function generateAutoOrder(Ingredient $ingredient) {
        $needed = max(1, ($ingredient->min_stock * 2) - $ingredient->stock);

        $order = Order::create([
            'supplier_id' => $ingredient->supplier_id,
            'date' => now(),
            'status' => 'pending',
            'total' => 0
        ]);

        $subtotal = $needed * ($ingredient->cost ?? 1);

        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'ingredient_id' => $ingredient->id,
            'quantity' => $needed,
            'unit_cost' => $ingredient->cost,
            'subtotal' => $subtotal
        ]);

        $order->update(['total' => $subtotal]);

        return $order;
    }

    private function updateDishAvailability() {
        $dishes = Dish::with('ingredients')->get();
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
