<?php

namespace Database\Seeders;

use App\Enums\CustomerType;
use App\Enums\UserRoleType;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('secret');

        $regularCompany = Company::where('customer_type', CustomerType::REGULAR)->first();
        $premiumCompany = Company::where('customer_type', CustomerType::PREMIUM)->first();
        $vipCompany = Company::where('customer_type', CustomerType::VIP)->first();

        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@xenotech.com',
                'password' => $password,
                'role' => UserRoleType::ADMIN->value,
                'company_id' => $regularCompany?->id,
            ],
            [
                'name' => 'Regular',
                'email' => 'user@xenotech.com',
                'password' => $password,
                'role' => UserRoleType::USER->value,
                'company_id' => $regularCompany?->id,
            ],
            [
                'name' => 'Premium',
                'email' => 'premium@xenotech.com',
                'password' => $password,
                'role' => UserRoleType::USER->value,
                'company_id' => $premiumCompany?->id,
            ],
            [
                'name' => 'VIP',
                'email' => 'vip@xenotech.com',
                'password' => $password,
                'role' => UserRoleType::USER->value,
                'company_id' => $vipCompany?->id,
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(['email' => $user['email']], $user);
        }

        $this->command->info('Users created!');
    }
}
