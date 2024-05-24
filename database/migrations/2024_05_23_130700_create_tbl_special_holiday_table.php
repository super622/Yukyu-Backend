<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblSpecialHolidayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_special_holiday', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('holiday_type');
            $table->integer('granted_days_type');
            $table->integer('granted_days');
            $table->longText('note');
            $table->timestamp('granted_at')->nullable();
            $table->timestamp('expired_at')->nullable();
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
        Schema::dropIfExists('tbl_special_holiday');
    }
}
