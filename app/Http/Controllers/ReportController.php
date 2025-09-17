<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\Dish;
use App\Models\Order;
use Carbon\Carbon;

class ReportController extends Controller {
    public function index(){
        $ingredients = Ingredient::with('supplier')->get();

        // Expired items (today > expiration_date)
        $expired = Ingredient::whereNotNull('expiration_date')
            ->where('expiration_date', '<', Carbon::today())
            ->get();

        // Expiring soon (within 3 days)
        $expiringSoon = Ingredient::whereNotNull('expiration_date')
            ->whereBetween('expiration_date', [Carbon::today(), Carbon::today()->addDays(3)])
            ->get();

        // Low stock items
        $lowStock = Ingredient::whereColumn('stock', '<', 'min_stock')->get();

        // Stock value analysis
        $totalStockValue = $ingredients->sum(function($item) {
            return $item->stock * ($item->cost ?? 0);
        });

        // Category analysis
        $categoryAnalysis = $ingredients->groupBy('category')->map(function($items) {
            return [
                'count' => $items->count(),
                'total_stock' => $items->sum('stock'),
                'total_value' => $items->sum(function($item) {
                    return $item->stock * ($item->cost ?? 0);
                })
            ];
        });

        // Recent orders
        $recentOrders = Order::with('supplier', 'items.ingredient')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Dish availability analysis
        $dishAnalysis = [
            'total' => Dish::count(),
            'available' => Dish::where('available', true)->count(),
            'unavailable' => Dish::where('available', false)->count()
        ];

        return view('reports.index', compact(
            'ingredients',
            'expired',
            'expiringSoon',
            'lowStock',
            'totalStockValue',
            'categoryAnalysis',
            'recentOrders',
            'dishAnalysis'
        ));
    }

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
}
