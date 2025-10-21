<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

test('allows admin to access filament panel', function () {
    $admin = User::factory()->create([
        'role' => UserRole::ADMIN,
    ]);

    $this->actingAs($admin)
        ->get('/admin')
        ->assertStatus(200);
});

test('denies non-admin to access filament panel', function () {
    $user = User::factory()->create([
        'role' => UserRole::CUSTOMER,
    ]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertStatus(403);
});
