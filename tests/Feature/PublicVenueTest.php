<?php

namespace Tests\Feature;

use App\Models\Court;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicVenueTest extends TestCase
{
    use RefreshDatabase;

    private function activeVenue(array $overrides = []): Venue
    {
        return Venue::factory()->create(array_merge([
            'status'       => 'active',
            'opening_time' => '08:00:00',
            'closing_time' => '22:00:00',
        ], $overrides));
    }

    // --- Index ---

    public function test_guest_can_see_active_venues(): void
    {
        $active = $this->activeVenue(['name' => 'Venue Aktif']);

        $this->get(route('venues.index'))
            ->assertStatus(200)
            ->assertSee('Venue Aktif');
    }

    public function test_inactive_venue_does_not_appear_in_listing(): void
    {
        $inactive = Venue::factory()->create([
            'name'         => 'Venue Nonaktif',
            'status'       => 'inactive',
            'opening_time' => '08:00:00',
            'closing_time' => '22:00:00',
        ]);

        $this->get(route('venues.index'))
            ->assertStatus(200)
            ->assertDontSee('Venue Nonaktif');
    }

    public function test_sport_type_filter_only_shows_matching_venues(): void
    {
        $futsalVenue = $this->activeVenue(['name' => 'Futsal Venue']);
        Court::factory()->create([
            'venue_id'   => $futsalVenue->id,
            'sport_type' => 'futsal',
            'status'     => 'available',
        ]);

        $badmintonVenue = $this->activeVenue(['name' => 'Badminton Venue']);
        Court::factory()->create([
            'venue_id'   => $badmintonVenue->id,
            'sport_type' => 'badminton',
            'status'     => 'available',
        ]);

        $response = $this->get(route('venues.index', ['sport_type' => 'futsal']));

        $response->assertStatus(200)
            ->assertSee('Futsal Venue')
            ->assertDontSee('Badminton Venue');
    }

    public function test_invalid_sport_type_filter_is_ignored(): void
    {
        $venue = $this->activeVenue(['name' => 'Venue Test']);

        $this->get(route('venues.index', ['sport_type' => 'invalid']))
            ->assertStatus(200)
            ->assertSee('Venue Test');
    }

    // --- Show ---

    public function test_guest_can_see_active_venue_detail(): void
    {
        $venue = $this->activeVenue(['name' => 'Venue Detail']);

        $this->get(route('venues.show', $venue))
            ->assertStatus(200)
            ->assertSee('Venue Detail');
    }

    public function test_inactive_venue_returns_404(): void
    {
        $inactive = Venue::factory()->create([
            'status'       => 'inactive',
            'opening_time' => '08:00:00',
            'closing_time' => '22:00:00',
        ]);

        $this->get(route('venues.show', $inactive))
            ->assertStatus(404);
    }

    public function test_show_only_displays_available_courts(): void
    {
        $venue = $this->activeVenue();

        $available = Court::factory()->create([
            'venue_id'   => $venue->id,
            'name'       => 'Court Tersedia',
            'status'     => 'available',
        ]);

        $maintenance = Court::factory()->create([
            'venue_id'   => $venue->id,
            'name'       => 'Court Maintenance',
            'status'     => 'maintenance',
        ]);

        $this->get(route('venues.show', $venue))
            ->assertStatus(200)
            ->assertSee('Court Tersedia')
            ->assertDontSee('Court Maintenance');
    }
}
