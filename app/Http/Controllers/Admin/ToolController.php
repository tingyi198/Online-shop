<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Jobs\UpdateProductPrice;
use Illuminate\Support\Facades\Redis;

class ToolController extends Controller
{
    public function updateProductPrice()
    {
        $products = Product::all();

        foreach($products as $product) {
            UpdateProductPrice::dispatch($product);
        }
    }

    public function createProductRedis()
    {
        Redis::set('products', json_encode(Product::all()));
    }
}
