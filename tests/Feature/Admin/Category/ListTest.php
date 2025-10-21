<?php

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

test('category index lists categories', function () {
    $admin = User::factory()->create([
        'role' => UserRole::ADMIN,
    ]);
    $categories = Category::factory()->count(3)->create();
    $this->actingAs($admin)
        ->get('/admin/categories')
        ->assertSeeText($categories[0]->name)
        ->assertSeeText($categories[1]->name)
        ->assertSeeText($categories[2]->name);
});
