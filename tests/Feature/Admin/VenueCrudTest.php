<?php

namespace Tests\Feature\Admin;

use App\Models\Court;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VenueCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function customer(): User
    {
        return User::factory()->create(['role' => 'customer']);
    }

    private function venueData(array $overrides = []): array
    {
        return array_merge([
            'name'         => 'Lapangan Merdeka',
            'address'      => 'Jl. Merdeka No. 1',
            'description'  => null,
            'phone'        => null,
            'opening_time' => '08:00',
            'closing_time' => '22:00',
            'status'       => 'active',
        ], $overrides);
    }

    // --- Akses ---

    public function test_customer_cannot_access_venues_index(): void
    {
        $this->actingAs($this->customer())
            ->get(route('admin.venues.index'))
            ->assertStatus(403);
    }

    // --- Index ---

    public function test_admin_can_see_venues_index(): void
    {
        Venue::factory()->create(['name' => 'Venue Satu', 'opening_time' => '08:00', 'closing_time' => '22:00']);

        $this->actingAs($this->admin())
            ->get(route('admin.venues.index'))
            ->assertStatus(200)
            ->assertSee('Venue Satu');
    }

    // --- Create ---

    public function test_admin_can_create_venue(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.venues.store'), $this->venueData())
            ->assertRedirect(route('admin.venues.index'));

        $this->assertDatabaseHas('venues', ['name' => 'Lapangan Merdeka']);
    }

    // --- Update ---

    public function test_admin_can_update_venue(): void
    {
        $venue = Venue::factory()->create(['opening_time' => '08:00', 'closing_time' => '22:00']);

        $this->actingAs($this->admin())
            ->patch(route('admin.venues.update', $venue), $this->venueData(['name' => 'Nama Baru']))
            ->assertRedirect(route('admin.venues.index'));

        $this->assertDatabaseHas('venues', ['id' => $venue->id, 'name' => 'Nama Baru']);
    }

    // --- Delete ---

    public function test_admin_can_delete_venue_without_courts(): void
    {
        $venue = Venue::factory()->create(['opening_time' => '08:00', 'closing_time' => '22:00']);

        $this->actingAs($this->admin())
            ->delete(route('admin.venues.destroy', $venue))
            ->assertRedirect(route('admin.venues.index'));

        $this->assertDatabaseMissing('venues', ['id' => $venue->id]);
    }

    public function test_admin_cannot_delete_venue_with_courts(): void
    {
        $venue = Venue::factory()->create(['opening_time' => '08:00', 'closing_time' => '22:00']);
        Court::factory()->create(['venue_id' => $venue->id]);

        $this->actingAs($this->admin())
            ->delete(route('admin.venues.destroy', $venue))
            ->assertRedirect(route('admin.venues.index'));

        $this->assertDatabaseHas('venues', ['id' => $venue->id]);
    }

    // --- Validasi ---

    public function test_closing_time_must_be_after_opening_time(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.venues.store'), $this->venueData([
                'opening_time' => '22:00',
                'closing_time' => '08:00',
            ]))
            ->assertSessionHasErrors('closing_time');
    }

    public function test_name_is_required(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.venues.store'), $this->venueData(['name' => '']))
            ->assertSessionHasErrors('name');
    }
}
