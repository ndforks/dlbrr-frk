<?php

namespace Database\Factories;

use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAttributeFactory extends Factory
{
    protected $model = ProductAttribute::class;

    public function definition(): array
    {
        return [
            'ref' => $this->faker->unique()->word(),
            'label' => $this->faker->words(2, true),
            'position' => $this->faker->numberBetween(1, 100),
            'entity' => 1,
        ];
    }
}
