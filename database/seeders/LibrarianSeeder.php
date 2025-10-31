<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LibrarianSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Regente da Biblioteca',
            'email' => 'regente@biblioteca.com',
            'password' => Hash::make('biblioteca123'),
        ]);
    }
}