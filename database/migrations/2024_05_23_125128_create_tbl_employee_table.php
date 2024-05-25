<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_employee', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->nullable();
            $table->string('name')->default('')->nullable();
            $table->string('kana_name')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->integer('department')->nullable();
            $table->timestamp('hire_date')->nullable();
            $table->integer('onleave')->nullable();
            $table->integer('working_type')->nullable();
            $table->integer('working_hours')->nullable();
            $table->longText('note')->nullable();
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
        Schema::dropIfExists('tbl_employee');
    }
}
