<?php

namespace Database\Factories;

use App\Models\PaidHolidaySettings;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaidHolidaySettingsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaidHolidaySettings::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'acquisition_order' => 0,
            'minimum_acquisition_unit' => 2,
            'scheduled_working_hours' => 0,
            'date_of_expiry_year' => 2,
            'date_of_expiry_month' => 0,
            'automatic_grant' => 1,
            'grant_implementation_date' => 1,
            'base_date_month' => 9,
            'base_date_day' => 18
        ];
    }
}
