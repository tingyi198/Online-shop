<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title(),
            'content' => '測試產品',
            'price' => $this->faker->numberBetween(100, 1000),
            'quantity' => $this->faker->numberBetween(100, 1000)
        ];
    }

    // 庫存不足
    public function less()
    {
        return $this->state(function (array $attribute) {
            return [
                'quantity' => 1
            ];
        });
    }
}
