<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPaidHolidaySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_paid_holiday_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('acquisition_order')->default(1);
            $table->integer('minimum_acquisition_unit')->default(3);
            $table->integer('scheduled_working_hours')->default(1);
            $table->integer('date_of_expiry_year')->default(2);
            $table->integer('date_of_expiry_month')->default(0);
            $table->integer('automatic_grant')->default(1);
            $table->integer('grant_implementation_date')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_paid_holiday_settings');
    }
}
