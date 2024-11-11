<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Fetch or create a category
        $category = Category::firstOrCreate([
            'name' => 'Electronics'
        ]);

        // Now create the product using the found or created category
        Product::create([
            'name' => 'Smartphone',
            'description' => 'A high-end smartphone.',
            'price' => 699.99,
            'stock' => 50,
            'image_url' => 'https://example.com/smartphone.jpg',
            'category_id' => $category->id,
        ]);
    }
}

