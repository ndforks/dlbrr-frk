<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\Societe;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'ref' => $this->faker->unique()->numerify('TIC####'),
            'subject' => $this->faker->sentence(),
            'fk_soc' => Societe::factory(),
            'datec' => now(),
            'entity' => 1,
        ];
    }
}
