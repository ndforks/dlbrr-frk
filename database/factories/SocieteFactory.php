<?php

namespace Database\Factories;

use App\Models\Societe;
use Illuminate\Database\Eloquent\Factories\Factory;

class SocieteFactory extends Factory
{
    protected $model = Societe::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->company(),
            'entity' => 1,
            'code_client' => $this->faker->unique()->numerify('CL####'),
            'status' => 1,
        ];
    }
}
