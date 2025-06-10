<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Programming',
            'Design',
            'Marketing',
            'Business',
            'Photography',
            'Music',
            'Cooking',
            'Language',
            'Fitness',
            'Art'
        ];

        foreach ($categories as $category) {
            Kategori::create(['kategori' => $category]);
        }
    }
}
