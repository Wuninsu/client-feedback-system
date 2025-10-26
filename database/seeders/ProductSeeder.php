<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Electronics', 'Software', 'Books', 'Fashion', 'Furniture'];

        foreach ($categories as $cat) {
            $category = ProductCategory::create(['name' => $cat, 'is_active' => true]);

            // Create sample products
            for ($i = 1; $i <= 3; $i++) {
                Product::create([
                    'product_category_id' => $category->id,
                    'name' => "$cat Product $i",
                    'description' => "This is a sample description for $cat Product $i.",
                    'price' => rand(50, 500),
                    'status' => true,
                ]);
            }
        }
    }
}
