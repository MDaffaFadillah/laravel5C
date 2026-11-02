<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Venue>
 */
class VenueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'             => fake()->company(),
            'address'          => fake()->address(),
            'description'      => fake()->optional()->sentence(),
            'phone'            => fake()->optional()->numerify('08##########'),
            'opening_time'     => '08:00:00',
            'closing_time'     => '22:00:00',
            'status'           => 'active',
            'allow_full_online' => true,
            'allow_dp'         => false,
            'allow_onsite'     => false,
            'dp_type'          => null,
            'dp_value'         => null,
        ];
    }
}
