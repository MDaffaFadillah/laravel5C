<?php

namespace Tests\Feature\Admin;

use App\Models\Court;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourtCrudTest extends TestCase
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

    private function courtData(array $overrides = []): array
    {
        $venue = Venue::factory()->create([
            'opening_time' => '08:00:00',
            'closing_time' => '22:00:00',
        ]);

        return array_merge([
            'venue_id'       => $venue->id,
            'name'           => 'Lapangan A',
            'sport_type'     => 'futsal',
            'description'    => null,
            'price_per_hour' => 75000,
            'status'         => 'available',
        ], $overrides);
    }

    // --- Akses ---

    public function test_customer_cannot_access_courts_index(): void
    {
        $this->actingAs($this->customer())
            ->get(route('admin.courts.index'))
            ->assertStatus(403);
    }

    // --- Index ---

    public function test_admin_can_see_courts_index(): void
    {
        $court = Court::factory()->create(['name' => 'Court Satu']);

        $this->actingAs($this->admin())
            ->get(route('admin.courts.index'))
            ->assertStatus(200)
            ->assertSee('Court Satu');
    }

    // --- Create ---

    public function test_admin_can_create_court(): void
    {
        $data = $this->courtData();

        $this->actingAs($this->admin())
            ->post(route('admin.courts.store'), $data)
            ->assertRedirect(route('admin.courts.index'));

        $this->assertDatabaseHas('courts', ['name' => 'Lapangan A']);
    }

    // --- Update ---

    public function test_admin_can_update_court(): void
    {
        $court = Court::factory()->create();

        $data = $this->courtData(['name' => 'Lapangan B', 'venue_id' => $court->venue_id]);

        $this->actingAs($this->admin())
            ->patch(route('admin.courts.update', $court), $data)
            ->assertRedirect(route('admin.courts.index'));

        $this->assertDatabaseHas('courts', ['id' => $court->id, 'name' => 'Lapangan B']);
    }

    // --- Delete ---

    public function test_admin_can_delete_court(): void
    {
        $court = Court::factory()->create();

        $this->actingAs($this->admin())
            ->delete(route('admin.courts.destroy', $court))
            ->assertRedirect(route('admin.courts.index'));

        $this->assertDatabaseMissing('courts', ['id' => $court->id]);
    }

    // --- Validasi ---

    public function test_price_per_hour_cannot_be_negative(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.courts.store'), $this->courtData(['price_per_hour' => -1]))
            ->assertSessionHasErrors('price_per_hour');
    }

    public function test_venue_id_must_exist(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.courts.store'), $this->courtData(['venue_id' => 9999]))
            ->assertSessionHasErrors('venue_id');
    }
}
