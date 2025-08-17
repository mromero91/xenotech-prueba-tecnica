<?php

namespace Database\Seeders;

use App\Enums\CustomerType;
use App\Enums\UserRoleType;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::where('role', UserRoleType::ADMIN->value)
            ->first();

        $companies = [
            [
                'name' => 'Xenotech Solutions',
                'phone' => '+1-555-0101',
                'address' => '123 street',
                'customer_type' => CustomerType::REGULAR,
                'is_active' => true,
                'created_by' => $adminUser?->id,
                'updated_by' => $adminUser?->id,
            ],
            [
                'name' => 'Premium Xenotech',
                'phone' => '+1-555-0202',
                'address' => 'prem stret',
                'customer_type' => CustomerType::PREMIUM,
                'is_active' => true,
                'created_by' => $adminUser?->id,
                'updated_by' => $adminUser?->id,
            ],
            [
                'name' => 'VIP Xenotech',
                'phone' => '+1-555-0303',
                'address' => '789 street',
                'customer_type' => CustomerType::VIP,
                'is_active' => true,
                'created_by' => $adminUser?->id,
                'updated_by' => $adminUser?->id,
            ],
        ];

        foreach ($companies as $companyData) {
            Company::firstOrCreate($companyData);
        }

        $this->command->info(' Companies created!');
    }
}
