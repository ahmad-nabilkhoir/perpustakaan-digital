<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@perpus.jateng'], // username admin
            [
                'name' => 'Administrator',
                'password' => Hash::make('perpusjateng'), //password admin
                'is_admin' => true, 
            ]
        );
    }
}
