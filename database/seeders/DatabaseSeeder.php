<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\PaidHolidaySettings;
use Database\Factories\EmployeeFactory;
use Database\Factories\PaidHolidaySettingsFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Employee::factory(1)->create();
        PaidHolidaySettings::factory(1)->create();
    }
}
