<?php

namespace Database\Factories;

use App\Models\Facture;
use App\Models\Societe;
use Illuminate\Database\Eloquent\Factories\Factory;

class FactureFactory extends Factory
{
    protected $model = Facture::class;

    public function definition(): array
    {
        return [
            'ref' => $this->faker->unique()->numerify('FA####'),
            'fk_soc' => Societe::factory(),
            'datef' => now(),
            'entity' => 1,
            'total_ht' => $this->faker->randomFloat(2, 100, 10000),
            'total_ttc' => $this->faker->randomFloat(2, 120, 12000),
        ];
    }
}
