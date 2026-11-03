<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhoneAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_phone_column_exists_in_users_table(): void
    {
        $user = User::factory()->create();

        $this->assertTrue(Schema::hasColumn('users', 'phone'));
        $this->assertNotNull($user->phone);
        $this->assertMatchesRegularExpression('/^08\d{8,11}$/', $user->phone);
    }

    public function test_registration_with_valid_phone(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'phone' => '081234567890',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertDatabaseHas('users', ['phone' => '081234567890']);
    }

    public function test_registration_with_duplicate_phone(): void
    {
        User::factory()->create(['phone' => '081234567890']);

        $response = $this->post('/register', [
            'name' => 'Dup User',
            'phone' => '081234567890',
            'email' => 'dup@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['phone' => 'Nomor HP sudah terdaftar.']);
    }

    public function test_registration_with_duplicate_phone_after_normalization(): void
    {
        User::factory()->create(['phone' => '081234567890']);

        $response = $this->post('/register', [
            'name' => 'Dup User',
            'phone' => '+6281234567890',
            'email' => 'dup@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('phone');
    }

    public function test_registration_with_invalid_phone_format(): void
    {
        $response = $this->post('/register', [
            'name' => 'Bad User',
            'phone' => '123',
            'email' => 'bad@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['phone' => 'Format nomor HP tidak valid. Gunakan 08xxxxxxxxxx.']);
    }

    public function test_login_with_correct_phone(): void
    {
        $this->markTestSkipped('Untuk commit 2: login nomor HP');
        $user = User::factory()->create([
            'phone' => '081234567890',
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'phone' => '081234567890',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_login_with_wrong_phone(): void
    {
        $this->markTestSkipped('Untuk commit 2: login nomor HP');
        $response = $this->post('/login', [
            'phone' => '089999999999',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('phone');
    }

    public function test_login_with_wrong_password(): void
    {
        $this->markTestSkipped('Untuk commit 2: login nomor HP');
        $user = User::factory()->create([
            'phone' => '081234567890',
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'phone' => '081234567890',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('phone');
    }
}