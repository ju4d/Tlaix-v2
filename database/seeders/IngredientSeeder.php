<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;
use Carbon\Carbon;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $ingredients = [
            // CARNES (Proveedor 1)
            [
                'name' => 'Pechuga de Pollo',
                'category' => 'perecedero',
                'stock' => 12.5,
                'min_stock' => 15.0,
                'unit' => 'kg',
                'cost' => 85.00,
                'supplier_id' => 1,
                'expiration_date' => Carbon::now()->addDays(3)
            ],
            [
                'name' => 'Carne de Res Premium',
                'category' => 'perecedero',
                'stock' => 8.0,
                'min_stock' => 12.0,
                'unit' => 'kg',
                'cost' => 220.00,
                'supplier_id' => 1,
                'expiration_date' => Carbon::now()->addDays(4)
            ],
            [
                'name' => 'Carne Molida',
                'category' => 'perecedero',
                'stock' => 15.0,
                'min_stock' => 10.0,
                'unit' => 'kg',
                'cost' => 130.00,
                'supplier_id' => 1,
                'expiration_date' => Carbon::now()->addDays(2)
            ],
            [
                'name' => 'Lomo de Cerdo',
                'category' => 'perecedero',
                'stock' => 6.5,
                'min_stock' => 8.0,
                'unit' => 'kg',
                'cost' => 160.00,
                'supplier_id' => 1,
                'expiration_date' => Carbon::now()->addDays(3)
            ],
            [
                'name' => 'Tocino',
                'category' => 'perecedero',
                'stock' => 4.0,
                'min_stock' => 5.0,
                'unit' => 'kg',
                'cost' => 180.00,
                'supplier_id' => 1,
                'expiration_date' => Carbon::now()->addDays(7)
            ],
            [
                'name' => 'Chorizo',
                'category' => 'perecedero',
                'stock' => 3.5,
                'min_stock' => 4.0,
                'unit' => 'kg',
                'cost' => 95.00,
                'supplier_id' => 1,
                'expiration_date' => Carbon::now()->addDays(5)
            ],

            // VERDURAS (Proveedor 2)
            [
                'name' => 'Tomate',
                'category' => 'perecedero',
                'stock' => 18.5,
                'min_stock' => 20.0,
                'unit' => 'kg',
                'cost' => 35.00,
                'supplier_id' => 2,
                'expiration_date' => Carbon::now()->addDays(5)
            ],
            [
                'name' => 'Lechuga Romana',
                'category' => 'perecedero',
                'stock' => 12.0,
                'min_stock' => 15.0,
                'unit' => 'kg',
                'cost' => 28.00,
                'supplier_id' => 2,
                'expiration_date' => Carbon::now()->addDays(3)
            ],
            [
                'name' => 'Cebolla Blanca',
                'category' => 'perecedero',
                'stock' => 25.0,
                'min_stock' => 18.0,
                'unit' => 'kg',
                'cost' => 22.00,
                'supplier_id' => 2,
                'expiration_date' => Carbon::now()->addDays(10)
            ],
            [
                'name' => 'Pimiento Morrón',
                'category' => 'perecedero',
                'stock' => 8.5,
                'min_stock' => 12.0,
                'unit' => 'kg',
                'cost' => 45.00,
                'supplier_id' => 2,
                'expiration_date' => Carbon::now()->addDays(4)
            ],
            [
                'name' => 'Aguacate Hass',
                'category' => 'perecedero',
                'stock' => 15.0,
                'min_stock' => 20.0,
                'unit' => 'kg',
                'cost' => 68.00,
                'supplier_id' => 2,
                'expiration_date' => Carbon::now()->addDays(3)
            ],
            [
                'name' => 'Zanahoria',
                'category' => 'perecedero',
                'stock' => 10.0,
                'min_stock' => 12.0,
                'unit' => 'kg',
                'cost' => 18.00,
                'supplier_id' => 2,
                'expiration_date' => Carbon::now()->addDays(8)
            ],
            [
                'name' => 'Papa Blanca',
                'category' => 'perecedero',
                'stock' => 30.0,
                'min_stock' => 25.0,
                'unit' => 'kg',
                'cost' => 20.00,
                'supplier_id' => 2,
                'expiration_date' => Carbon::now()->addDays(12)
            ],
            [
                'name' => 'Brócoli',
                'category' => 'perecedero',
                'stock' => 7.0,
                'min_stock' => 8.0,
                'unit' => 'kg',
                'cost' => 42.00,
                'supplier_id' => 2,
                'expiration_date' => Carbon::now()->addDays(4)
            ],
            [
                'name' => 'Espinaca',
                'category' => 'perecedero',
                'stock' => 5.0,
                'min_stock' => 6.0,
                'unit' => 'kg',
                'cost' => 38.00,
                'supplier_id' => 2,
                'expiration_date' => Carbon::now()->addDays(3)
            ],
            [
                'name' => 'Champiñones',
                'category' => 'perecedero',
                'stock' => 4.5,
                'min_stock' => 6.0,
                'unit' => 'kg',
                'cost' => 65.00,
                'supplier_id' => 2,
                'expiration_date' => Carbon::now()->addDays(4)
            ],

            // LÁCTEOS (Proveedor 3)
            [
                'name' => 'Queso Manchego',
                'category' => 'perecedero',
                'stock' => 5.5,
                'min_stock' => 8.0,
                'unit' => 'kg',
                'cost' => 250.00,
                'supplier_id' => 3,
                'expiration_date' => Carbon::now()->addDays(15)
            ],
            [
                'name' => 'Queso Panela',
                'category' => 'perecedero',
                'stock' => 4.0,
                'min_stock' => 5.0,
                'unit' => 'kg',
                'cost' => 110.00,
                'supplier_id' => 3,
                'expiration_date' => Carbon::now()->addDays(10)
            ],
            [
                'name' => 'Queso Oaxaca',
                'category' => 'perecedero',
                'stock' => 3.5,
                'min_stock' => 5.0,
                'unit' => 'kg',
                'cost' => 140.00,
                'supplier_id' => 3,
                'expiration_date' => Carbon::now()->addDays(12)
            ],
            [
                'name' => 'Crema Ácida',
                'category' => 'perecedero',
                'stock' => 6.0,
                'min_stock' => 8.0,
                'unit' => 'liters',
                'cost' => 60.00,
                'supplier_id' => 3,
                'expiration_date' => Carbon::now()->addDays(7)
            ],
            [
                'name' => 'Leche Entera',
                'category' => 'perecedero',
                'stock' => 20.0,
                'min_stock' => 15.0,
                'unit' => 'liters',
                'cost' => 25.00,
                'supplier_id' => 3,
                'expiration_date' => Carbon::now()->addDays(5)
            ],
            [
                'name' => 'Mantequilla',
                'category' => 'perecedero',
                'stock' => 4.0,
                'min_stock' => 5.0,
                'unit' => 'kg',
                'cost' => 180.00,
                'supplier_id' => 3,
                'expiration_date' => Carbon::now()->addDays(20)
            ],

            // MARISCOS (Proveedor 4)
            [
                'name' => 'Filete de Pescado Blanco',
                'category' => 'perecedero',
                'stock' => 5.0,
                'min_stock' => 8.0,
                'unit' => 'kg',
                'cost' => 150.00,
                'supplier_id' => 4,
                'expiration_date' => Carbon::now()->addDays(2)
            ],
            [
                'name' => 'Camarones Frescos',
                'category' => 'perecedero',
                'stock' => 4.0,
                'min_stock' => 6.0,
                'unit' => 'kg',
                'cost' => 280.00,
                'supplier_id' => 4,
                'expiration_date' => Carbon::now()->addDays(1)
            ],
            [
                'name' => 'Salmón',
                'category' => 'perecedero',
                'stock' => 3.0,
                'min_stock' => 5.0,
                'unit' => 'kg',
                'cost' => 320.00,
                'supplier_id' => 4,
                'expiration_date' => Carbon::now()->addDays(2)
            ],

            // ABARROTES (Proveedor 5)
            [
                'name' => 'Arroz Blanco',
                'category' => 'no_perecedero',
                'stock' => 45.0,
                'min_stock' => 30.0,
                'unit' => 'kg',
                'cost' => 18.00,
                'supplier_id' => 5,
                'expiration_date' => null
            ],
            [
                'name' => 'Frijol Negro',
                'category' => 'no_perecedero',
                'stock' => 35.0,
                'min_stock' => 25.0,
                'unit' => 'kg',
                'cost' => 25.00,
                'supplier_id' => 5,
                'expiration_date' => null
            ],
            [
                'name' => 'Pasta Spaghetti',
                'category' => 'no_perecedero',
                'stock' => 28.0,
                'min_stock' => 20.0,
                'unit' => 'kg',
                'cost' => 32.00,
                'supplier_id' => 5,
                'expiration_date' => null
            ],
            [
                'name' => 'Pasta Penne',
                'category' => 'no_perecedero',
                'stock' => 22.0,
                'min_stock' => 15.0,
                'unit' => 'kg',
                'cost' => 34.00,
                'supplier_id' => 5,
                'expiration_date' => null
            ],
            [
                'name' => 'Aceite de Oliva Extra Virgen',
                'category' => 'no_perecedero',
                'stock' => 8.0,
                'min_stock' => 10.0,
                'unit' => 'liters',
                'cost' => 180.00,
                'supplier_id' => 5,
                'expiration_date' => null
            ],
            [
                'name' => 'Aceite Vegetal',
                'category' => 'no_perecedero',
                'stock' => 15.0,
                'min_stock' => 12.0,
                'unit' => 'liters',
                'cost' => 45.00,
                'supplier_id' => 5,
                'expiration_date' => null
            ],
            [
                'name' => 'Harina de Trigo',
                'category' => 'no_perecedero',
                'stock' => 40.0,
                'min_stock' => 30.0,
                'unit' => 'kg',
                'cost' => 15.00,
                'supplier_id' => 5,
                'expiration_date' => null
            ],
            [
                'name' => 'Azúcar Refinada',
                'category' => 'no_perecedero',
                'stock' => 25.0,
                'min_stock' => 20.0,
                'unit' => 'kg',
                'cost' => 22.00,
                'supplier_id' => 5,
                'expiration_date' => null
            ],

            // CONDIMENTOS (Proveedor 5)
            [
                'name' => 'Sal de Mar',
                'category' => 'condimento',
                'stock' => 12.0,
                'min_stock' => 8.0,
                'unit' => 'kg',
                'cost' => 15.00,
                'supplier_id' => 5,
                'expiration_date' => null
            ],
            [
                'name' => 'Pimienta Negra Molida',
                'category' => 'condimento',
                'stock' => 2.5,
                'min_stock' => 3.0,
                'unit' => 'kg',
                'cost' => 220.00,
                'supplier_id' => 5,
                'expiration_date' => null
            ],
            [
                'name' => 'Ajo en Polvo',
                'category' => 'condimento',
                'stock' => 1.8,
                'min_stock' => 2.0,
                'unit' => 'kg',
                'cost' => 150.00,
                'supplier_id' => 5,
                'expiration_date' => null
            ],
            [
                'name' => 'Orégano Seco',
                'category' => 'condimento',
                'stock' => 1.5,
                'min_stock' => 2.0,
                'unit' => 'kg',
                'cost' => 180.00,
                'supplier_id' => 5,
                'expiration_date' => null
            ],
            [
                'name' => 'Comino',
                'category' => 'condimento',
                'stock' => 1.2,
                'min_stock' => 1.5,
                'unit' => 'kg',
                'cost' => 200.00,
                'supplier_id' => 5,
                'expiration_date' => null
            ],
            [
                'name' => 'Cilantro Fresco',
                'category' => 'perecedero',
                'stock' => 2.0,
                'min_stock' => 3.0,
                'unit' => 'kg',
                'cost' => 40.00,
                'supplier_id' => 2,
                'expiration_date' => Carbon::now()->addDays(3)
            ],
            [
                'name' => 'Limón',
                'category' => 'perecedero',
                'stock' => 8.0,
                'min_stock' => 10.0,
                'unit' => 'kg',
                'cost' => 30.00,
                'supplier_id' => 6,
                'expiration_date' => Carbon::now()->addDays(7)
            ],

            // PAN (Proveedor 7)
            [
                'name' => 'Pan para Hamburguesa',
                'category' => 'perecedero',
                'stock' => 50.0,
                'min_stock' => 60.0,
                'unit' => 'piezas',
                'cost' => 8.00,
                'supplier_id' => 7,
                'expiration_date' => Carbon::now()->addDays(3)
            ],
            [
                'name' => 'Pan Baguette',
                'category' => 'perecedero',
                'stock' => 20.0,
                'min_stock' => 25.0,
                'unit' => 'piezas',
                'cost' => 15.00,
                'supplier_id' => 7,
                'expiration_date' => Carbon::now()->addDays(2)
            ],
            [
                'name' => 'Tortillas de Harina',
                'category' => 'perecedero',
                'stock' => 40.0,
                'min_stock' => 50.0,
                'unit' => 'paquetes',
                'cost' => 25.00,
                'supplier_id' => 7,
                'expiration_date' => Carbon::now()->addDays(5)
            ],
        ];

        foreach ($ingredients as $ingredientData) {
            Ingredient::create($ingredientData);
        }

        $this->command->info('✓ Ingredientes creados: ' . count($ingredients));
    }
}
