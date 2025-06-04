<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;

class AdminAndCategorySeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gamestore.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Contoh kategori awal
        $categories = ['Action', 'Adventure', 'Puzzle', 'Racing', 'RPG'];
        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}
