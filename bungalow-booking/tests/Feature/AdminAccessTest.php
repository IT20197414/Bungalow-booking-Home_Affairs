<?php

namespace Tests\Feature;

use App\Models\Bungalow;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_users_cannot_access_admin_routes(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));

        $customer = User::factory()->customer()->create();

        $this->actingAs($customer)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_bungalows(): void
    {
        $admin = User::factory()->admin()->create();

        $createResponse = $this->actingAs($admin)->post(route('admin.bungalows.store'), [
            'title' => 'Lake View Bungalow',
            'description' => 'A calm lakefront stay.',
            'address' => '12 Lake Road',
            'city' => 'Kandy',
            'capacity' => 6,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'nightly_rate' => 180,
            'status' => 'available',
            'featured' => '1',
        ]);

        $createResponse->assertRedirect(route('admin.bungalows.index'));
        $bungalow = Bungalow::where('title', 'Lake View Bungalow')->firstOrFail();

        $updateResponse = $this->actingAs($admin)->put(route('admin.bungalows.update', $bungalow), [
            'title' => 'Updated Lake View Bungalow',
            'description' => 'Updated description.',
            'address' => '12 Lake Road',
            'city' => 'Kandy',
            'capacity' => 8,
            'bedrooms' => 4,
            'bathrooms' => 3,
            'nightly_rate' => 220,
            'status' => 'available',
        ]);

        $updateResponse->assertRedirect(route('admin.bungalows.index'));
        $this->assertDatabaseHas('bungalows', [
            'id' => $bungalow->id,
            'title' => 'Updated Lake View Bungalow',
            'capacity' => 8,
        ]);

        $deleteResponse = $this->actingAs($admin)->delete(route('admin.bungalows.destroy', $bungalow));

        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('bungalows', ['id' => $bungalow->id]);
    }
}
