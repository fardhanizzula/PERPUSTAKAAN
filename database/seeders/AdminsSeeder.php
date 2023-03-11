<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admins;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Admins::create([
            'name' => 'Fardhan',
            'email' => 'admin1@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
