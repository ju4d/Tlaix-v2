<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Carnes y Embutidos del Valle',
                'contact' => 'Roberto Sánchez',
                'phone' => '33-1234-5678',
                'email' => 'ventas@carnesvalle.mx'
            ],
            [
                'name' => 'Verduras Frescas Premium',
                'contact' => 'María González',
                'phone' => '33-2345-6789',
                'email' => 'pedidos@verduraspremium.mx'
            ],
            [
                'name' => 'Lácteos y Quesos Guadalajara',
                'contact' => 'Ana Rodríguez',
                'phone' => '33-3456-7890',
                'email' => 'contacto@lacteosguadalajara.mx'
            ],
            [
                'name' => 'Mariscos del Pacífico',
                'contact' => 'Carlos López',
                'phone' => '33-4567-8901',
                'email' => 'ordenes@mariscospacifico.mx'
            ],
            [
                'name' => 'Abarrotes y Especias del Centro',
                'contact' => 'Luis Martínez',
                'phone' => '33-5678-9012',
                'email' => 'info@abarrotescentro.mx'
            ],
            [
                'name' => 'Frutas Tropicales de Jalisco',
                'contact' => 'Patricia Hernández',
                'phone' => '33-6789-0123',
                'email' => 'ventas@frutastropicales.mx'
            ],
            [
                'name' => 'Panadería Artesanal Don José',
                'contact' => 'José Ramírez',
                'phone' => '33-7890-1234',
                'email' => 'pedidos@panaderiadonjose.mx'
            ],
            [
                'name' => 'Bebidas y Refrescos del Norte',
                'contact' => 'Diana Torres',
                'phone' => '33-8901-2345',
                'email' => 'ordenes@bebidasnorte.mx'
            ]
        ];

        foreach ($suppliers as $supplierData) {
            Supplier::create($supplierData);
        }

        $this->command->info('✓ Proveedores creados: ' . count($suppliers));
    }
}
