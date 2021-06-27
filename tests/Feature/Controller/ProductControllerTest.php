<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Http\Services\ShortUrlService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testShareUrl()
    {
        $product = Product::factory()->create();
        $id = $product->id;

        $this->mock(ShortUrlService::class, function ($mock) use ($id) {
            $mock->shouldReceive('makeShortUrl')
                ->with("http://localhost:3000/products/$id")
                ->andReturn('picseeUrl');
        });

        $response = $this->call(
            'GET',
            "products/$id/sharedUrl"
        );

        $response->assertOk();
        $response = json_decode($response->getContent(), true);

        $this->assertEquals($response['url'], 'picseeUrl');
    }
}
