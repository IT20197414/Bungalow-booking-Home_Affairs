<?php

namespace Tests\Feature;

use App\Models\Amenity;
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
        $wifi = Amenity::create(['name' => 'Wi-Fi']);
        $parking = Amenity::create(['name' => 'Parking']);
        $bbq = Amenity::create(['name' => 'BBQ Area']);

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
            'amenity_ids' => [$wifi->id, $parking->id],
        ]);

        $createResponse->assertRedirect(route('admin.bungalows.index'));
        $bungalow = Bungalow::where('title', 'Lake View Bungalow')->firstOrFail();
        $this->assertEqualsCanonicalizing([$wifi->id, $parking->id], $bungalow->amenities()->pluck('amenities.id')->all());

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
            'amenity_ids' => [$bbq->id],
        ]);

        $updateResponse->assertRedirect(route('admin.bungalows.index'));
        $this->assertDatabaseHas('bungalows', [
            'id' => $bungalow->id,
            'title' => 'Updated Lake View Bungalow',
            'capacity' => 8,
        ]);
        $this->assertEqualsCanonicalizing([$bbq->id], $bungalow->fresh()->amenities()->pluck('amenities.id')->all());

        $deleteResponse = $this->actingAs($admin)->delete(route('admin.bungalows.destroy', $bungalow));

        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('bungalows', ['id' => $bungalow->id]);
    }

    public function test_admin_can_update_bungalow_when_existing_times_include_seconds(): void
    {
        $admin = User::factory()->admin()->create();
        $bungalow = Bungalow::factory()->create([
            'check_in_time' => '14:00:00',
            'check_out_time' => '11:00:00',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.bungalows.update', $bungalow), [
            'title' => $bungalow->title,
            'description' => $bungalow->description,
            'address' => $bungalow->address,
            'city' => $bungalow->city,
            'capacity' => $bungalow->capacity,
            'bedrooms' => $bungalow->bedrooms,
            'bathrooms' => $bungalow->bathrooms,
            'nightly_rate' => $bungalow->nightly_rate,
            'status' => $bungalow->status,
            'check_in_time' => '14:00:00',
            'check_out_time' => '11:00:00',
        ]);

        $response->assertRedirect(route('admin.bungalows.index'));
        $response->assertSessionHasNoErrors();
    }
}
