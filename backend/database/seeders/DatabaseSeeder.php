<?php

namespace Database\Seeders;

use App\Models\Attachment;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::factory()->create();

        $product = Product::create([
            'user_id' => $user->id,
            'name' => 'Product 1',
            'price' => 1000,
            'detail' => 'Product 1 detail',
        ]);

        Attachment::create([
            'product_id' => $product->id,
            'path' => '/uploads/1.jpg',
        ]);


    }
}
