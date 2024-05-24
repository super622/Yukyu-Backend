<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DepartmentController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\PaidHolidaySettingsController;
use App\Http\Controllers\API\SpecialHolidaySettingsController;
use App\Http\Controllers\API\AbsenceRegistrationController;
use App\Http\Controllers\API\PaidHolidayController;
use App\Http\Controllers\API\SpecialHolidayController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Departmenet
Route::post('/department_list', [DepartmentController::class, 'index']);
Route::post('/create_department', [DepartmentController::class, 'store']);
Route::post('/remove_department', [DepartmentController::class, 'destroy']);
Route::post('/update_department', [DepartmentController::class, 'update']);
Route::post('/show_department', [DepartmentController::class, 'show']);

// Employee
Route::post('/employee_list', [EmployeeController::class, 'index']);
Route::post('/create_employee', [EmployeeController::class, 'store']);
Route::post('/remove_employee', [EmployeeController::class, 'destroy']);
Route::post('/update_employee', [EmployeeController::class, 'update']);
Route::post('/show_employee', [EmployeeController::class, 'show']);

// PaidHolidaySettings
Route::post('/update_paidholidaysettings', [PaidHolidaySettingsController::class, 'update']);
Route::post('/show_paidholidaysettings', [PaidHolidaySettingsController::class, 'show']);

// SpecialHolidaySettings
Route::post('/speicalholidaysettings_list', [SpecialHolidaySettingsController::class, 'index']);
Route::post('/create_speicalholidaysettings', [SpecialHolidaySettingsController::class, 'store']);
Route::post('/remove_speicalholidaysettings', [SpecialHolidaySettingsController::class, 'destroy']);
Route::post('/update_speicalholidaysettings', [SpecialHolidaySettingsController::class, 'update']);
Route::post('/show_speicalholidaysettings', [SpecialHolidaySettingsController::class, 'show']);

// SpecialHolidaySettings
Route::post('/speicalholidaysettings_list', [SpecialHolidaySettingsController::class, 'index']);
Route::post('/create_speicalholidaysettings', [SpecialHolidaySettingsController::class, 'store']);
Route::post('/remove_speicalholidaysettings', [SpecialHolidaySettingsController::class, 'destroy']);
Route::post('/update_speicalholidaysettings', [SpecialHolidaySettingsController::class, 'update']);
Route::post('/show_speicalholidaysettings', [SpecialHolidaySettingsController::class, 'show']);

// AbsenceRegistration
Route::post('/create_absenceregistration', [AbsenceRegistrationController::class, 'store']);
Route::post('/remove_absenceregistration', [AbsenceRegistrationController::class, 'destroy']);
Route::post('/update_absenceregistration', [AbsenceRegistrationController::class, 'update']);
Route::post('/show_absenceregistration', [AbsenceRegistrationController::class, 'show']);

// PaidHoliday
Route::post('/create_paidholiday', [PaidHolidayController::class, 'store']);
Route::post('/remove_paidholiday', [PaidHolidayController::class, 'destroy']);
Route::post('/update_paidholiday', [PaidHolidayController::class, 'update']);
Route::post('/show_paidholiday', [PaidHolidayController::class, 'show']);

// SpecialHoliday
Route::post('/create_specialholiday', [SpecialHolidayController::class, 'store']);
Route::post('/remove_specialholiday', [SpecialHolidayController::class, 'destroy']);
Route::post('/update_specialholiday', [SpecialHolidayController::class, 'update']);
Route::post('/show_specialholiday', [SpecialHolidayController::class, 'show']);
