<?php

namespace Database\Factories;

use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Court>
 */
class CourtFactory extends Factory
{
    public function definition(): array
    {
        return [
            'venue_id'      => Venue::factory(),
            'name'          => 'Lapangan ' . fake()->numberBetween(1, 10),
            'sport_type'    => fake()->randomElement(['futsal', 'badminton']),
            'description'   => fake()->optional()->sentence(),
            'price_per_hour' => fake()->randomElement([50000, 75000, 100000]),
            'status'        => 'available',
        ];
    }
}
