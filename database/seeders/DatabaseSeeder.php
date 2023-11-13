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
            'name' => 'admin',
        ]);

        Role::create([
            'name' => 'customer',
        ]);

        Role::create([
            'name' => 'author',
        ]);

        $user = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('P@ssw0rd'),
        ]);

        $customer = User::factory()->create([
            'name' => 'customer',
            'email' => 'customer@customer.com',
            'password' => bcrypt('P@ssw0rd'),
        ]);

        $author = User::factory()->create([
            'name' => 'author',
            'email' => 'author@author.com',
            'password' => bcrypt('P@ssw0rd'),
        ]);

        $user->assignRole('admin');
        $customer->assignRole('customer');
        $author->assignRole('author');
    }
}
