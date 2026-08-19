<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
        ]);

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@lumira.test'],
            [
                'name' => 'Admin Lumira',
                'password' => Hash::make('Admin12345!'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $seller = User::updateOrCreate(
            ['email' => 'seller@lumira.test'],
            [
                'name' => 'Seller Lumira',
                'password' => Hash::make('Seller12345!'),
                'role' => 'seller',
                'seller_status' => 'approved',
                'email_verified_at' => now(),
            ]
        );

        Store::firstOrCreate(
            ['user_id' => $seller->id],
            [
                'name' => 'Toko Lumira Demo',
                'slug' => 'toko-lumira-demo',
                'description' => 'Toko demo untuk penilaian project Lumira.',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@lumira.test'],
            [
                'name' => 'User Lumira',
                'password' => Hash::make('User12345!'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );
    }
}