<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'web'
        ]);
        $user = User::create([
            'name' => 'Super admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password')
        ]);

        $user->assignRole('Super Admin');
    }
}
