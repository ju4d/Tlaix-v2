<?php
// app/Http/Controllers/IngredientPredictionController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\Dish;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IngredientPredictionController extends Controller
{
    /**
     * Predice qué ingredientes necesitan reabastecimiento
     */
    public function predictIngredientNeeds(Request $request)
    {
        $days = $request->input('days', 7);

        try {
            // 1. Obtener predicción de demanda general
            $demandPrediction = $this->getDemandPrediction($days);

            // 2. Calcular consumo promedio por ingrediente
            $ingredientConsumption = $this->calculateIngredientConsumption();

            // 3. Obtener stock actual de todos los ingredientes
            $currentStock = $this->getCurrentStock();

            // 4. Calcular necesidades de reabastecimiento
            $predictions = $this->calculateRestockNeeds(
                $demandPrediction,
                $ingredientConsumption,
                $currentStock,
                $days
            );

            return response()->json([
                'success' => true,
                'predictions' => $predictions,
                'summary' => $this->generateSummary($predictions),
                'period_days' => $days,
                'generated_at' => now()->toIso8601String()
            ]);

        } catch (\Exception $e) {
            Log::error('Error en predicción de ingredientes', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Error al generar predicciones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene la predicción de demanda general (órdenes/día)
     */
    private function getDemandPrediction($days)
    {
        // Obtener histórico de órdenes de los últimos 30 días
        $startDate = Carbon::now()->subDays(30);

        $historicalData = Order::where('status', 'received')
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        if ($historicalData->count() < 3) {
            // Si no hay suficientes datos históricos, usar promedio simple
            $avgOrders = max(1, Order::where('status', 'received')->count() / 30);
            return array_fill(0, $days, $avgOrders);
        }

        // Calcular promedio y tendencia
        $avgOrders = $historicalData->avg('orders_count');

        // Proyección simple: mantener promedio con ajuste por día de semana
        $predictions = [];
        for ($i = 1; $i <= $days; $i++) {
            $futureDate = Carbon::now()->addDays($i);
            $isWeekend = $futureDate->isWeekend();

            // Incremento de 30% en fin de semana
            $dayPrediction = $avgOrders * ($isWeekend ? 1.3 : 1.0);
            $predictions[] = $dayPrediction;
        }

        return $predictions;
    }

    /**
     * Calcula el consumo promedio de cada ingrediente por orden
     */
    private function calculateIngredientConsumption()
    {
        $consumption = [];

        // Obtener todos los platos con sus ingredientes
        $dishes = Dish::with('ingredients')->get();

        foreach ($dishes as $dish) {
            foreach ($dish->ingredients as $ingredient) {
                $ingredientId = $ingredient->id;
                $quantityPerDish = $ingredient->pivot->quantity_required;

                if (!isset($consumption[$ingredientId])) {
                    $consumption[$ingredientId] = [
                        'ingredient' => $ingredient,
                        'total_per_order' => 0,
                        'dishes_using' => []
                    ];
                }

                // Asumimos que cada orden puede tener este plato
                // Esto es una simplificación - idealmente tendrías datos reales de ventas
                $consumption[$ingredientId]['total_per_order'] += $quantityPerDish * 0.3; // 30% probabilidad de pedir cada plato
                $consumption[$ingredientId]['dishes_using'][] = $dish->name;
            }
        }

        return $consumption;
    }

    /**
     * Obtiene el stock actual de todos los ingredientes
     */
    private function getCurrentStock()
    {
        return Ingredient::all()->keyBy('id');
    }

    /**
     * Calcula las necesidades de reabastecimiento
     */
    private function calculateRestockNeeds($demandPrediction, $ingredientConsumption, $currentStock, $days)
    {
        $predictions = [];

        // Calcular demanda total proyectada
        $totalProjectedOrders = array_sum($demandPrediction);

        foreach ($ingredientConsumption as $ingredientId => $consumption) {
            $ingredient = $consumption['ingredient'];
            $consumptionPerOrder = $consumption['total_per_order'];

            // Calcular consumo proyectado
            $projectedConsumption = $totalProjectedOrders * $consumptionPerOrder;

            // Stock actual
            $currentAmount = $ingredient->stock;

            // Stock mínimo
            $minStock = $ingredient->min_stock;

            // Calcular si necesita reabastecimiento
            $stockAfterPeriod = $currentAmount - $projectedConsumption;
            $needsRestock = $stockAfterPeriod < $minStock;

            // Calcular cantidad recomendada
            $recommendedOrder = 0;
            if ($needsRestock) {
                // Ordenar suficiente para: consumo proyectado + buffer de seguridad (50%)
                $recommendedOrder = max(0, ($minStock * 2) - $stockAfterPeriod);
            }

            // Calcular días hasta agotamiento
            $daysUntilStockout = $consumptionPerOrder > 0
                ? ($currentAmount / ($consumptionPerOrder * ($totalProjectedOrders / $days)))
                : 999;

            // Determinar urgencia
            $urgency = $this->calculateUrgency($stockAfterPeriod, $minStock, $daysUntilStockout);

            $predictions[] = [
                'ingredient_id' => $ingredientId,
                'ingredient_name' => $ingredient->name,
                'category' => $ingredient->category,
                'current_stock' => round($currentAmount, 2),
                'min_stock' => round($minStock, 2),
                'unit' => $ingredient->unit,
                'projected_consumption' => round($projectedConsumption, 2),
                'stock_after_period' => round($stockAfterPeriod, 2),
                'needs_restock' => $needsRestock,
                'recommended_order_quantity' => round($recommendedOrder, 2),
                'days_until_stockout' => round($daysUntilStockout, 1),
                'urgency' => $urgency,
                'urgency_score' => $this->getUrgencyScore($urgency),
                'dishes_using' => $consumption['dishes_using'],
                'supplier' => $ingredient->supplier ? [
                    'id' => $ingredient->supplier->id,
                    'name' => $ingredient->supplier->name,
                    'contact' => $ingredient->supplier->contact
                ] : null,
                'cost_estimate' => round($recommendedOrder * ($ingredient->cost ?? 0), 2)
            ];
        }

        // Ordenar por urgencia (más urgente primero)
        usort($predictions, function($a, $b) {
            return $b['urgency_score'] - $a['urgency_score'];
        });

        return $predictions;
    }

    /**
     * Calcula el nivel de urgencia
     */
    private function calculateUrgency($stockAfterPeriod, $minStock, $daysUntilStockout)
    {
        if ($stockAfterPeriod < 0) {
            return 'critical'; // Se agotará antes del período
        } elseif ($stockAfterPeriod < $minStock * 0.5) {
            return 'high'; // Muy por debajo del mínimo
        } elseif ($stockAfterPeriod < $minStock) {
            return 'medium'; // Por debajo del mínimo
        } elseif ($daysUntilStockout < 14) {
            return 'low'; // Se agotará pronto pero no crítico
        } else {
            return 'normal'; // Stock suficiente
        }
    }

    /**
     * Obtiene score numérico de urgencia para ordenar
     */
    private function getUrgencyScore($urgency)
    {
        $scores = [
            'critical' => 100,
            'high' => 75,
            'medium' => 50,
            'low' => 25,
            'normal' => 0
        ];

        return $scores[$urgency] ?? 0;
    }

    /**
     * Genera resumen de las predicciones
     */
    private function generateSummary($predictions)
    {
        $needsRestock = array_filter($predictions, function($p) {
            return $p['needs_restock'];
        });

        $byCriticalLevel = [
            'critical' => 0,
            'high' => 0,
            'medium' => 0,
            'low' => 0,
            'normal' => 0
        ];

        $totalCost = 0;

        foreach ($predictions as $pred) {
            $byCriticalLevel[$pred['urgency']]++;
            if ($pred['needs_restock']) {
                $totalCost += $pred['cost_estimate'];
            }
        }

        return [
            'total_ingredients' => count($predictions),
            'needs_restock' => count($needsRestock),
            'by_urgency' => $byCriticalLevel,
            'estimated_total_cost' => round($totalCost, 2),
            'top_priority_count' => $byCriticalLevel['critical'] + $byCriticalLevel['high']
        ];
    }

    /**
     * Genera órdenes de compra sugeridas agrupadas por proveedor
     */
    public function generateSuggestedOrders(Request $request)
    {
        $days = $request->input('days', 7);

        try {
            $predictionResult = $this->predictIngredientNeeds($request);
            $predictions = $predictionResult->getData()->predictions;

            // Filtrar solo los que necesitan reabastecimiento
            $needsRestock = array_filter($predictions, function($p) {
                return $p->needs_restock;
            });

            // Agrupar por proveedor
            $ordersBySupplier = [];

            foreach ($needsRestock as $item) {
                if (!$item->supplier) {
                    // Sin proveedor asignado
                    if (!isset($ordersBySupplier['no_supplier'])) {
                        $ordersBySupplier['no_supplier'] = [
                            'supplier_name' => 'Sin proveedor asignado',
                            'supplier_id' => null,
                            'items' => [],
                            'total_cost' => 0
                        ];
                    }

                    $ordersBySupplier['no_supplier']['items'][] = $item;
                    $ordersBySupplier['no_supplier']['total_cost'] += $item->cost_estimate;
                } else {
                    $supplierId = $item->supplier->id;

                    if (!isset($ordersBySupplier[$supplierId])) {
                        $ordersBySupplier[$supplierId] = [
                            'supplier_name' => $item->supplier->name,
                            'supplier_id' => $supplierId,
                            'supplier_contact' => $item->supplier->contact,
                            'items' => [],
                            'total_cost' => 0
                        ];
                    }

                    $ordersBySupplier[$supplierId]['items'][] = $item;
                    $ordersBySupplier[$supplierId]['total_cost'] += $item->cost_estimate;
                }
            }

            return response()->json([
                'success' => true,
                'suggested_orders' => array_values($ordersBySupplier),
                'total_suppliers' => count($ordersBySupplier),
                'total_items' => count($needsRestock),
                'grand_total_cost' => array_sum(array_column($ordersBySupplier, 'total_cost'))
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
