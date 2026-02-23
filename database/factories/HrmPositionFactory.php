<?php

namespace Database\Factories;

use App\Models\HrmPosition;
use Illuminate\Database\Eloquent\Factories\Factory;

class HrmPositionFactory extends Factory
{
    protected $model = HrmPosition::class;

    public function definition(): array
    {
        return [
            'ref' => $this->faker->unique()->numerify('POS####'),
            'label' => $this->faker->jobTitle(),
            'entity' => 1,
        ];
    }
}
