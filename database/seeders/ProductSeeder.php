<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            ['name' => 'Basic Widget', 'price' => 9.99, 'quantity_available' => 100],
            ['name' => 'Advanced Gadget', 'price' => 24.95, 'quantity_available' => 45],
            ['name' => 'Premium Device', 'price' => 59.99, 'quantity_available' => 20],
            ['name' => 'Accessory Pack', 'price' => 14.50, 'quantity_available' => 75],
            ['name' => 'Replacement Part', 'price' => 4.25, 'quantity_available' => 200],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
