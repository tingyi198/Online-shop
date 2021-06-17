<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateCartItem;
use App\Http\Requests\CreateCartItem;
use App\Models\CartItem;
use App\Models\Product;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CreateCartItem  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCartItem $request)
    {
        $request->validated();

        // check quantity of product
        $product = Product::find($request->product_id);
        $inventoryQuantity = $product->getQuantity($product->id);
        if ($request->quantity > $inventoryQuantity) {
            return response($product->title . '庫存不足', 400);
        }

        $cartItem = new CartItem;
        $cartItemRes = $cartItem->addCartItem($request->all());
        return response()->json($cartItemRes);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateCartItem $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCartItem $request, $id)
    {
        $validate = $request->validated();
        $cartItem = new CartItem();
        $cartItemRes = $cartItem->updateCartItem($request->all(), $id);
        return response()->json(true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cartItem = new CartItem();
        $cartItem->deleteCartItem($id);
    }
}
