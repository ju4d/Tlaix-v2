<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\Dish;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        // Conteos generales
        $totalIngredients = Ingredient::count();
        $lowStock = Ingredient::whereColumn('stock', '<', 'min_stock')->count();
        $totalDishes = Dish::count();
        $availableDishes = Dish::where('available', true)->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        // Ejemplo: top 5 ingredientes con menos stock
        $lowStockItems = Ingredient::orderByRaw('stock - min_stock ASC')
                            ->take(5)
                            ->get(['name', 'stock', 'min_stock']);

        return view('dashboard', compact(
            'totalIngredients',
            'lowStock',
            'totalDishes',
            'availableDishes',
            'pendingOrders',
            'lowStockItems'
        ));
    }
}
