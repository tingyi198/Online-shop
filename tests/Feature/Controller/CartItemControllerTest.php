<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
// use PHPUnit\Framework\TestCase;
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
        $cart = $this->fakeUser->carts()->create();
        $product = Product::create([
            'title' => 'Test Product',
            'content' => 'Test Product Content',
            'price' => 10,
            'quantity' => 100
        ]);

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
        $cart = $this->fakeUser->carts()->create();
        $product = Product::create([
            'title' => 'Test Product',
            'content' => 'Test Product Content',
            'price' => 10,
            'quantity' => 100
        ]);
        $cart_item = $cart->cartItems()->create([
            'product_id' => $product->id,
            'quantity' => 10
        ]);

        $response = $this->call(
            'PUT',
            'cart-item/'.$cart_item->id,
            ['quantity' => 1]
        );
        $this->assertEquals('true', $response->getContent());

        // 刷新資料庫
        $cart_item->refresh();

        $this->assertEquals(1, $cart_item->quantity);
    }

    public function testDestroy()
    {
        $cart = $this->fakeUser->carts()->create();
        $product = Product::create([
            'title' => 'Test Product2',
            'content' => 'Test Product Content2',
            'price' => 10,
            'quantity' => 100
        ]);
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
