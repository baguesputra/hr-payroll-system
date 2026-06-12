<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
});

it('allows active user to login with correct credentials', function () {
    $user = User::factory()->create([
        'email'     => 'test@example.com',
        'password'  => Hash::make('password'),
        'is_active' => true,
    ]);
    $user->assignRole('employee');

    $response = $this->postJson('/api/v1/auth/login', [
        'email'    => 'test@example.com',
        'password' => 'password',
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'token',
                'user' => ['id', 'name', 'email', 'roles', 'permissions'],
            ],
        ]);
});

it('rejects login with wrong password', function () {
    User::factory()->create([
        'email'    => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email'    => 'test@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertUnprocessable();
});

it('rejects login for inactive user', function () {
    User::factory()->create([
        'email'     => 'inactive@example.com',
        'password'  => Hash::make('password'),
        'is_active' => false,
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email'    => 'inactive@example.com',
        'password' => 'password',
    ]);

    $response->assertForbidden();
});

it('returns authenticated user data on /me', function () {
    $user = User::factory()->create(['is_active' => true]);
    $user->assignRole('employee');

    $response = $this->actingAs($user)
        ->getJson('/api/v1/auth/me');

    $response->assertOk()
        ->assertJsonPath('data.email', $user->email);
});

it('logs out successfully', function () {
    $user = User::factory()->create(['is_active' => true]);
    $token = $user->createToken('test_token')->plainTextToken;

    $response = $this->withToken($token)
        ->postJson('/api/v1/auth/logout');

    $response->assertOk()
        ->assertJsonPath('message', 'Logged out successfully');
});

it('requires authentication for protected routes', function () {
    $response = $this->getJson('/api/v1/auth/me');
    $response->assertUnauthorized();
});