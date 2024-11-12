<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategoryIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get the count of categories_id
        $categoryCount = DB::table('products')->count();

        Product::all()->each(function ($product) use ($categoryCount){
            // get category_id
            $categoryIds = range(1, $categoryCount);
            $product->category_id = $categoryIds[array_rand($categoryIds)];
            $product->save();
        });
    }
}
