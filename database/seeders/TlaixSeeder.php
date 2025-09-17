<?php
// database/seeders/TlaixSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Ingredient;
use App\Models\Dish;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TlaixSeeder extends Seeder
{
    public function run()
    {
        // Create users
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@tlaix.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ],
            [
                'name' => 'Chef García',
                'email' => 'chef@tlaix.com',
                'password' => Hash::make('chef123'),
                'role' => 'chef'
            ],
            [
                'name' => 'Mesero Juan',
                'email' => 'waiter@tlaix.com',
                'password' => Hash::make('waiter123'),
                'role' => 'waiter'
            ]
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        // Create suppliers
        $suppliers = [
            [
                'name' => 'Fresh Foods Co.',
                'contact' => 'María González',
                'phone' => '33-1234-5678',
                'email' => 'orders@freshfoods.mx'
            ],
            [
                'name' => 'Carnes Premium',
                'contact' => 'Roberto Martínez',
                'phone' => '33-2345-6789',
                'email' => 'ventas@carnespremium.mx'
            ],
            [
                'name' => 'Lácteos Guadalajara',
                'contact' => 'Ana Rodríguez',
                'phone' => '33-3456-7890',
                'email' => 'pedidos@lacteosguadalajara.mx'
            ],
            [
                'name' => 'Verduras del Valle',
                'contact' => 'Carlos López',
                'phone' => '33-4567-8901',
                'email' => 'info@verdurasdelevalle.mx'
            ]
        ];

        foreach ($suppliers as $supplierData) {
            Supplier::create($supplierData);
        }

        // Create ingredients
        $ingredients = [
            // Vegetables
            ['name' => 'Tomates', 'category' => 'perecedero', 'stock' => 25.5, 'min_stock' => 10, 'unit' => 'kg', 'cost' => 35.00, 'supplier_id' => 4, 'expiration_date' => Carbon::now()->addDays(5)],
            ['name' => 'Lechuga', 'category' => 'perecedero', 'stock' => 15.0, 'min_stock' => 8, 'unit' => 'kg', 'cost' => 28.00, 'supplier_id' => 4, 'expiration_date' => Carbon::now()->addDays(3)],
            ['name' => 'Cebolla', 'category' => 'perecedero', 'stock' => 18.5, 'min_stock' => 12, 'unit' => 'kg', 'cost' => 22.00, 'supplier_id' => 4, 'expiration_date' => Carbon::now()->addDays(10)],
            ['name' => 'Pimientos', 'category' => 'perecedero', 'stock' => 8.0, 'min_stock' => 15, 'unit' => 'kg', 'cost' => 45.00, 'supplier_id' => 4, 'expiration_date' => Carbon::now()->addDays(4)],

            // Meats
            ['name' => 'Pollo', 'category' => 'perecedero', 'stock' => 12.0, 'min_stock' => 20, 'unit' => 'kg', 'cost' => 85.00, 'supplier_id' => 2, 'expiration_date' => Carbon::now()->addDays(2)],
            ['name' => 'Carne de Res', 'category' => 'perecedero', 'stock' => 8.5, 'min_stock' => 15, 'unit' => 'kg', 'cost' => 180.00, 'supplier_id' => 2, 'expiration_date' => Carbon::now()->addDays(3)],
            ['name' => 'Pescado', 'category' => 'perecedero', 'stock' => 6.0, 'min_stock' => 10, 'unit' => 'kg', 'cost' => 120.00, 'supplier_id' => 2, 'expiration_date' => Carbon::now()->addDays(1)],

            // Dairy
            ['name' => 'Queso Manchego', 'category' => 'perecedero', 'stock' => 3.5, 'min_stock' => 5, 'unit' => 'kg', 'cost' => 250.00, 'supplier_id' => 3, 'expiration_date' => Carbon::now()->addDays(15)],
            ['name' => 'Crema', 'category' => 'perecedero', 'stock' => 2.0, 'min_stock' => 4, 'unit' => 'liters', 'cost' => 60.00, 'supplier_id' => 3, 'expiration_date' => Carbon::now()->addDays(7)],
            ['name' => 'Leche', 'category' => 'perecedero', 'stock' => 15.0, 'min_stock' => 10, 'unit' => 'liters', 'cost' => 25.00, 'supplier_id' => 3, 'expiration_date' => Carbon::now()->addDays(5)],

            // Non-perishables
            ['name' => 'Arroz', 'category' => 'no_perecedero', 'stock' => 50.0, 'min_stock' => 25, 'unit' => 'kg', 'cost' => 18.00, 'supplier_id' => 1, 'expiration_date' => null],
            ['name' => 'Pasta', 'category' => 'no_perecedero', 'stock' => 30.0, 'min_stock' => 15, 'unit' => 'kg', 'cost' => 32.00, 'supplier_id' => 1, 'expiration_date' => null],
            ['name' => 'Aceite de Oliva', 'category' => 'no_perecedero', 'stock' => 5.0, 'min_stock' => 8, 'unit' => 'liters', 'cost' => 150.00, 'supplier_id' => 1, 'expiration_date' => null],

            // Condiments
            ['name' => 'Sal', 'category' => 'condimento', 'stock' => 10.0, 'min_stock' => 5, 'unit' => 'kg', 'cost' => 12.00, 'supplier_id' => 1, 'expiration_date' => null],
            ['name' => 'Pimienta Negra', 'category' => 'condimento', 'stock' => 1.5, 'min_stock' => 2, 'unit' => 'kg', 'cost' => 180.00, 'supplier_id' => 1, 'expiration_date' => null],
        ];

        foreach ($ingredients as $ingredientData) {
            Ingredient::create($ingredientData);
        }

        // Create dishes
        $dishes = [
            [
                'name' => 'Ensalada César',
                'description' => 'Lechuga fresca con aderezo césar, pollo y queso',
                'price' => 145.00,
                'available' => true
            ],
            [
                'name' => 'Pasta Alfredo',
                'description' => 'Pasta con salsa de crema y queso manchego',
                'price' => 165.00,
                'available' => true
            ],
            [
                'name' => 'Pollo a la Parrilla',
                'description' => 'Pechuga de pollo con verduras asadas',
                'price' => 195.00,
                'available' => true
            ],
            [
                'name' => 'Pescado al Vapor',
                'description' => 'Filete de pescado con vegetales',
                'price' => 225.00,
                'available' => true
            ]
        ];

        foreach ($dishes as $dishData) {
            $dish = Dish::create($dishData);

            // Add ingredients to dishes
            switch ($dish->name) {
                case 'Ensalada César':
                    $dish->ingredients()->attach([
                        2 => ['quantity_required' => 0.2], // Lechuga
                        8 => ['quantity_required' => 0.05], // Queso
                        5 => ['quantity_required' => 0.15] // Pollo
                    ]);
                    break;
                case 'Pasta Alfredo':
                    $dish->ingredients()->attach([
                        12 => ['quantity_required' => 0.12], // Pasta
                        9 => ['quantity_required' => 0.1], // Crema
                        8 => ['quantity_required' => 0.08] // Queso
                    ]);
                    break;
                case 'Pollo a la Parrilla':
                    $dish->ingredients()->attach([
                        5 => ['quantity_required' => 0.25], // Pollo
                        1 => ['quantity_required' => 0.1], // Tomates
                        4 => ['quantity_required' => 0.08] // Pimientos
                    ]);
                    break;
                case 'Pescado al Vapor':
                    $dish->ingredients()->attach([
                        7 => ['quantity_required' => 0.2], // Pescado
                        2 => ['quantity_required' => 0.05], // Lechuga
                        3 => ['quantity_required' => 0.03] // Cebolla
                    ]);
                    break;
            }
        }

        // Create sample prediction data
        $this->createPredictionData();

        echo "✅ Tlaix database seeded successfully!\n";
        echo "👤 Admin Login: admin@tlaix.com / admin123\n";
        echo "👨‍🍳 Chef Login: chef@tlaix.com / chef123\n";
        echo "🧑‍💼 Waiter Login: waiter@tlaix.com / waiter123\n";
    }

    private function createPredictionData()
    {
        $predictionsPath = storage_path('app/predictions');
        if (!file_exists($predictionsPath)) {
            mkdir($predictionsPath, 0755, true);
        }

        $historyFile = $predictionsPath . '/history.csv';
        $csvData = "date,demand\n";

        // Generate 30 days of historical data
        for ($i = 30; $i >= 1; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $baseDemand = 20;
            $weekdayFactor = Carbon::parse($date)->isWeekend() ? 1.3 : 1.0;
            $randomFactor = mt_rand(80, 120) / 100;
            $seasonalFactor = 1 + (sin(($i * 2 * pi()) / 30) * 0.2);

            $demand = round($baseDemand * $weekdayFactor * $randomFactor * $seasonalFactor, 1);
            $csvData .= "$date,$demand\n";
        }

        file_put_contents($historyFile, $csvData);
    }
}
