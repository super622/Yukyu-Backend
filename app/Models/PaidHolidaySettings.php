<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaidHolidaySettings extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_paid_holiday_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'acquisition_order',
        'minimum_acquisition_unit',
        'scheduled_working_hours',
        'date_of_expiry_year',
        'date_of_expiry_month',
        'automatic_grant',
        'grant_implementation_date'
    ];
}
