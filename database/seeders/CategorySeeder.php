<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Plastik',
            'Kertas',
            'Kardus',
            'Logam',
            'Besi Tua',
            'Aluminium',
            'Elektronik Bekas',
            'Botol Kaca',
            'Tekstil',
            'Organik',
        ];

        foreach ($categories as $category) {

            Category::firstOrCreate(
                [
                    'slug' => Str::slug($category),
                ],
                [
                    'name' => $category,
                ]
            );
        }
    }
}