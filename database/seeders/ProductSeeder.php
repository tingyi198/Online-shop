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
                'id' => 1,
                'title' => 'title1',
                'content' => 'content1',
                'price' => 400,
                'quantity' => 10000
            ],
            [
                'id' => 2,
                'title' => 'title2',
                'content' => 'content2',
                'price' => 80,
                'quantity' => 999
            ],
            ['id'],
            ['title', 'content', 'quantity']
        );
    }
}
