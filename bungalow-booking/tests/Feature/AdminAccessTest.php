<?php

namespace Tests\Feature;

use App\Models\Amenity;
use App\Models\Bungalow;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_admin_navigation_does_not_show_customer_booking_link(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Admin')
            ->assertDontSee('My Bookings');
    }

    public function test_admin_can_create_update_and_delete_bungalows(): void
    {
        Storage::fake('public');

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
            'photos' => [
                UploadedFile::fake()->create('front.jpg', 100, 'image/jpeg'),
                UploadedFile::fake()->create('pool.png', 100, 'image/png'),
            ],
        ]);

        $createResponse->assertRedirect(route('admin.bungalows.index'));
        $bungalow = Bungalow::where('title', 'Lake View Bungalow')->firstOrFail();
        $this->assertEqualsCanonicalizing([$wifi->id, $parking->id], $bungalow->amenities()->pluck('amenities.id')->all());
        $this->assertCount(2, $bungalow->images);
        $this->assertTrue($bungalow->images->first()->is_primary);
        Storage::disk('public')->assertExists($bungalow->images->first()->path);

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
            'photos' => [
                UploadedFile::fake()->create('garden.webp', 100, 'image/webp'),
            ],
        ]);

        $updateResponse->assertRedirect(route('admin.bungalows.index'));
        $this->assertDatabaseHas('bungalows', [
            'id' => $bungalow->id,
            'title' => 'Updated Lake View Bungalow',
            'capacity' => 8,
        ]);
        $this->assertEqualsCanonicalizing([$bbq->id], $bungalow->fresh()->amenities()->pluck('amenities.id')->all());
        $this->assertCount(3, $bungalow->fresh()->images);

        $imagePath = $bungalow->fresh()->images->first()->path;
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.bungalows.destroy', $bungalow));

        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('bungalows', ['id' => $bungalow->id]);
        Storage::disk('public')->assertMissing($imagePath);
    }

    public function test_admin_can_change_uploaded_bungalow_photos(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $bungalow = Bungalow::factory()->create();
        $firstImage = $bungalow->images()->create([
            'path' => UploadedFile::fake()->create('front.jpg', 100, 'image/jpeg')->store('bungalows', 'public'),
            'caption' => 'Front',
            'is_primary' => true,
            'sort_order' => 1,
        ]);
        $secondImage = $bungalow->images()->create([
            'path' => UploadedFile::fake()->create('pool.jpg', 100, 'image/jpeg')->store('bungalows', 'public'),
            'caption' => 'Pool',
            'is_primary' => false,
            'sort_order' => 2,
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
            'primary_image_id' => $secondImage->id,
            'delete_image_ids' => [$firstImage->id],
            'photos' => [
                UploadedFile::fake()->create('garden.png', 100, 'image/png'),
            ],
        ]);

        $response->assertRedirect(route('admin.bungalows.index'));
        $response->assertSessionHasNoErrors();
        Storage::disk('public')->assertMissing($firstImage->path);
        Storage::disk('public')->assertExists($secondImage->path);
        $this->assertDatabaseMissing('bungalow_images', ['id' => $firstImage->id]);
        $this->assertDatabaseHas('bungalow_images', [
            'id' => $secondImage->id,
            'is_primary' => true,
        ]);
        $this->assertCount(2, $bungalow->fresh()->images);
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
