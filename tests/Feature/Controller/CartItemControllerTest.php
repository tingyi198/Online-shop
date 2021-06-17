<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Laravel\Passport\Passport;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
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
}
