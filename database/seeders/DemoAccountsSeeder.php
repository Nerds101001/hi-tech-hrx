<?php

namespace Database\Seeders;

use App\Enums\UserAccountStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class DemoAccountsSeeder extends Seeder
{
    public function run(): void
    {
        $demos = [
            [
                'first_name'        => 'Demo',
                'last_name'         => 'Admin',
                'email'             => 'admin@demo.com',
                'code'              => 'DEMO-ADMIN',
                'password'          => Hash::make('password'),
                'status'            => UserAccountStatus::ACTIVE,
                'email_verified_at' => Carbon::now(),
                'role'              => 'admin',
            ],
            [
                'first_name'        => 'Demo',
                'last_name'         => 'HR',
                'email'             => 'hr@demo.com',
                'code'              => 'DEMO-HR',
                'password'          => Hash::make('password'),
                'status'            => UserAccountStatus::ACTIVE,
                'email_verified_at' => Carbon::now(),
                'role'              => 'hr',
            ],
            [
                'first_name'        => 'Demo',
                'last_name'         => 'Employee',
                'email'             => 'emp@demo.com',
                'code'              => 'DEMO-EMP',
                'password'          => Hash::make('password'),
                'status'            => UserAccountStatus::ACTIVE,
                'email_verified_at' => Carbon::now(),
                'role'              => 'field_employee',
            ],
            [
                'first_name'        => 'Demo',
                'last_name'         => 'Manager',
                'email'             => 'manager@demo.com',
                'code'              => 'DEMO-MGR',
                'password'          => Hash::make('password'),
                'status'            => UserAccountStatus::ACTIVE,
                'email_verified_at' => Carbon::now(),
                'role'              => 'manager',
            ],
        ];

        foreach ($demos as $data) {
            $role = $data['role'];
            unset($data['role']);

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );

            // Assign role (sync so it doesn't duplicate)
            $user->syncRoles([$role]);

            $this->command->info("✅ {$user->email} ({$role}) ready.");
        }
    }
}
