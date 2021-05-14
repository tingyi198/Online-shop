<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::upsert(
            [
                'id' => '4',
                'title' => 'title',
                'content' => 'content',
                'price' => 400,
                'quantity' => 10000
            ],
            ['id'],
            ['title', 'content', 'quantity']
        );
    }
}
