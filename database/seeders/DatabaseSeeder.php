<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Ichal Wira',
            'email' => 'ichal@gmail.com',
            'password' => Hash::make('123123')
        ]);

        User::factory(9)->create();


        // category factory
        Category::factory(2)->create();

        //product factory
        Product::factory(15)->create();

    }
}
