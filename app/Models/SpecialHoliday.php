<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialHoliday extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_special_holiday';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'type',
        'employee_id',
        'holiday_type',
        'grant_type',
        'granted_days_type',
        'granted_days',
        'note',
        'granted_at',
        'expired_at'
    ];
}
