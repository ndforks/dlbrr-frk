<?php

namespace Database\Factories;

use App\Models\SupplierProposal;
use App\Models\Societe;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierProposalFactory extends Factory
{
    protected $model = SupplierProposal::class;

    public function definition(): array
    {
        return [
            'ref' => $this->faker->unique()->numerify('SP####'),
            'fk_soc' => Societe::factory(),
            'date_valid' => now(),
            'entity' => 1,
        ];
    }
}
