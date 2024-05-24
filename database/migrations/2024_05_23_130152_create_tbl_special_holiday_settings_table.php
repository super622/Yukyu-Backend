<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblSpecialHolidaySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_special_holiday_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('priority')->default(0);
            $table->integer('status')->default(1);
            $table->integer('notice_excess_consumption')->default(0);
            $table->integer('expire_year')->default(0);
            $table->integer('expire_month')->default(0);
            $table->integer('expire_day')->default(0);
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
        Schema::dropIfExists('tbl_special_holiday_settings');
    }
}
