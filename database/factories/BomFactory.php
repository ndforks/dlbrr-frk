<?php

namespace Database\Factories;

use App\Models\Bom;
use Illuminate\Database\Eloquent\Factories\Factory;

class BomFactory extends Factory
{
    protected $model = Bom::class;

    public function definition(): array
    {
        return [
            'ref' => $this->faker->unique()->numerify('BOM####'),
            'label' => $this->faker->words(3, true),
            'entity' => 1,
        ];
    }
}
