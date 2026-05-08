<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\Bungalow;
use App\Services\AvailabilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailabilityServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_detects_available_and_unavailable_date_ranges(): void
    {
        $bungalow = Bungalow::factory()->create(['status' => 'available']);
        $service = new AvailabilityService;

        Booking::factory()->confirmed()->create([
            'bungalow_id' => $bungalow->id,
            'check_in_date' => '2026-06-10',
            'check_out_date' => '2026-06-15',
        ]);

        $this->assertFalse($service->isAvailable($bungalow, '2026-06-12', '2026-06-16'));
        $this->assertTrue($service->isAvailable($bungalow, '2026-06-15', '2026-06-18'));
        $this->assertFalse($service->isAvailable($bungalow, '2026-06-18', '2026-06-18'));
    }
}
