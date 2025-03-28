<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'user'];

        foreach($roles as $role){
            Role::create(['name' => $role]);
        }

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin',
            'password' => 'admin'
        ]);

        $admin->assignRole('admin');

        $this->command->info('Roles and admin user seeded successfully.');
    }
}
