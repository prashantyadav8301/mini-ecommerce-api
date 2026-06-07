<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name'        => 'iPhone 15 Pro',
                'description' => 'Latest Apple flagship phone',
                'price'       => 134900,
                'stock'       => 50,
                'category'    => 'Electronics',
            ],
            [
                'name'        => 'Samsung Galaxy S24',
                'description' => 'Samsung flagship with AI features',
                'price'       => 79999,
                'stock'       => 30,
                'category'    => 'Electronics',
            ],
            [
                'name'        => 'Sony WH-1000XM5',
                'description' => 'Industry leading noise cancelling headphones',
                'price'       => 29990,
                'stock'       => 25,
                'category'    => 'Audio',
            ],
            [
                'name'        => 'Dell XPS 15',
                'description' => 'Premium laptop for professionals',
                'price'       => 189900,
                'stock'       => 15,
                'category'    => 'Laptops',
            ],
            [
                'name'        => 'Apple iPad Pro',
                'description' => 'Most powerful iPad ever',
                'price'       => 109900,
                'stock'       => 20,
                'category'    => 'Tablets',
            ],
            [
                'name'        => 'Nike Air Max 270',
                'description' => 'Comfortable running shoes',
                'price'       => 12995,
                'stock'       => 100,
                'category'    => 'Footwear',
            ],
            [
                'name'        => 'Logitech MX Master 3',
                'description' => 'Advanced wireless mouse',
                'price'       => 9995,
                'stock'       => 40,
                'category'    => 'Accessories',
            ],
            [
                'name'        => 'LG 4K Monitor 27"',
                'description' => '4K UHD display for professionals',
                'price'       => 45990,
                'stock'       => 2,           // ← low stock for testing
                'category'    => 'Monitors',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
