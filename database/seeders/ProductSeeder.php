<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample automotive parts
        $products = [
            [
                'name' => 'High Performance Brake Pads',
                'description' => 'Premium ceramic brake pads for enhanced stopping power',
                'price' => 89.99,
                'stock_quantity' => 50,
                'category' => 'brake-system',
                'product_image' => null
            ],
            [
                'name' => 'Turbo Air Intake System',
                'description' => 'Improved airflow for increased engine performance',
                'price' => 299.99,
                'stock_quantity' => 25,
                'category' => 'engine-parts',
                'product_image' => null
            ],
            [
                'name' => 'Advanced Suspension Kit',
                'description' => 'Complete suspension upgrade for smoother ride',
                'price' => 499.99,
                'stock_quantity' => 15,
                'category' => 'suspension',
                'product_image' => null
            ],
            [
                'name' => 'High Voltage Alternator',
                'description' => 'Powerful alternator for enhanced electrical system',
                'price' => 199.99,
                'stock_quantity' => 40,
                'category' => 'electrical',
                'product_image' => null
            ],
            [
                'name' => 'Performance Transmission Fluid',
                'description' => 'Synthetic transmission fluid for optimal gear performance',
                'price' => 49.99,
                'stock_quantity' => 100,
                'category' => 'transmission',
                'product_image' => null
            ]
        ];

        // Insert products
        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
