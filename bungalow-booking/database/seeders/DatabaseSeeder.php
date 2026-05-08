<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Bungalow;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->customer()->create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
        ]);

        $amenities = collect(['Wi-Fi', 'Pool', 'Kitchen', 'Air Conditioning', 'Parking', 'BBQ Area'])
            ->map(fn (string $name) => Amenity::create(['name' => $name]));

        Bungalow::factory()
            ->count(8)
            ->create()
            ->each(fn (Bungalow $bungalow) => $bungalow->amenities()->sync($amenities->random(3)->pluck('id')));
    }
}
