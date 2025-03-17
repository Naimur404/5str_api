<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userExists = User::where('email', '=', 'superadmin@gmail.com')->exists();
        $role = Role::firstOrCreate(['name' => 'Super Admin']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        if (!$userExists) {
            $user = User::create([
                'name' => 'Ariful Islam',
                'email' => 'superadmin@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678')
            ]);

            $user->assignRole([$role->id]);
        }
        else{
            $user = User::where('email', '=', 'superadmin@gmail.com')->first();
            $user->assignRole([$role->id]);
        }

        \App\Models\User::factory()->count(20)->create([
            'user_type' => 'user',
            'is_active' => true,
        ]);
    }
}
