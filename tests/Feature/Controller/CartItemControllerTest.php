<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Cart;
use App\Models\User;
// use PHPUnit\Framework\TestCase;
use App\Models\Product;
use App\Models\CartItem;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartItemControllerTest extends TestCase
{

    use RefreshDatabase;

    private $fakeUser;

    protected function setUp():void
    {
        parent::setUp();
        $this->fakeUser = User::create([
            'name' => 'John333',
            'email' => 'john123@gmail.com',
            'password' => '12345678'
        ]);

        Passport::actingAs($this->fakeUser);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testStore()
    {
        $cart = Cart::factory()->create();
        $product = Product::factory()->create();

        $response = $this->call(
            'POST',
            'cart-item',
            [
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 99
            ]
        );
        $response->assertOk();

        // 庫存不足
        $product = Product::factory()->less()->create();
        $response = $this->call(
            'POST',
            'cart-item/',
            ['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 10]
        );

        $this->assertEquals($product->title . '庫存不足', $response->getContent());

        $response = $this->call(
            'POST',
            'cart-item',
            [
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 99999
            ]
        );
        $response->assertStatus(400);
    }

    public function testUpdate()
    {
        // $cart = $this->fakeUser->carts()->create();
        // $product = Product::factory()->create();
        // $cart_item = $cart->cartItems()->create([
        //     'product_id' => $product->id,
        //     'quantity' => 10
        // ]);

        // $response = $this->call(
        //     'PUT',
        //     'cart-item/'.$cart_item->id,
        //     ['quantity' => 1]
        // );

        // 更新數量
        $update_quantity = 5;
        $cart_item = CartItem::factory()->create();
        $response = $this->call(
            'PUT',
            'cart-item/' . $cart_item->id,
            ['quantity' => $update_quantity]
        );

        $this->assertEquals('true', $response->getContent());

        // 刷新資料庫
        $cart_item->refresh();

        $this->assertEquals($update_quantity, $cart_item->quantity);
    }

    public function testDestroy()
    {
        $cart = $this->fakeUser->carts()->create();
        $product = Product::factory()->create();
        $cartItem = $cart->cartItems()->create([
            'product_id' => $product->id,
            'quantity' => 10
        ]);

        $response = $this->call(
            'DELETE',
            'cart-item/' . $cartItem->id,
            ['quantity' => 1]
        );

        $response->assertOk();

        // $cartItem->refresh();
        $cartItem = CartItem::find($cartItem->id);
        $this->assertNull($cartItem);
    }
}
