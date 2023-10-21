<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'admin'
        ]);

        $user = User::factory()->create([
            'uniq_id' => generateUuid(),
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('P@ssw0rd'),
        ]);

        $user->assignRole('admin');
    }
}
