<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\Dish;
use App\Models\Order;
use App\Models\CustomerOrder;
use App\Services\PredictionService;

class DashboardController extends Controller
{
    private $predictionService;

    public function __construct(PredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }

    public function index()
    {
        // Conteos generales
        $totalIngredients = Ingredient::count();
        $lowStock = Ingredient::whereColumn('stock', '<', 'min_stock')->count();
        $totalDishes = Dish::count();
        $availableDishes = Dish::where('available', true)->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        // Ingredientes con stock crítico
        $lowStockItems = Ingredient::whereColumn('stock', '<=', 'min_stock')
                            ->orderByRaw('stock - min_stock ASC')
                            ->take(5)
                            ->get(['id', 'name', 'stock', 'min_stock', 'unit', 'category']);

        // 🔥 ESTADÍSTICAS DE DEMANDA EN TIEMPO REAL
        $demandStats = $this->predictionService->getDemandStats();

        // Órdenes de clientes del día
        $todayOrders = CustomerOrder::whereDate('created_at', today())->count();
        $todayCompleted = CustomerOrder::whereDate('created_at', today())
                            ->where('status', 'completed')->count();

        return view('dashboard', compact(
            'totalIngredients',
            'lowStock',
            'totalDishes',
            'availableDishes',
            'pendingOrders',
            'lowStockItems',
            'demandStats',
            'todayOrders',
            'todayCompleted'
        ));
    }
}
