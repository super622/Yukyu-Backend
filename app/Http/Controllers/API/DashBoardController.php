<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DashBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $results = [];
        $results['employee_cnt'] = DB::table("tbl_employee")->count();
        $results['department_cnt'] = DB::table("tbl_department")->count();

        $settings = DB::table("tbl_paid_holiday_settings")->first();
        $settings->acquisition_order = $this->acquisition_order[$settings->acquisition_order];
        $settings->minimum_acquisition_unit = $this->minimum_acquisition_unit[$settings->minimum_acquisition_unit];
        $settings->scheduled_working_hours = $this->scheduled_working_hours[$settings->scheduled_working_hours];
        $settings->grant_implementation_date = $this->grant_implementation_date[$settings->grant_implementation_date];
        
        $results['settings'] = $settings;

        $eventData = [];
        $paidHoliday =      DB::table("tbl_paid_holiday")
                                ->select("tbl_paid_holiday.id as id", "tbl_employee.name as employee_name", "tbl_paid_holiday.employee_id", "tbl_paid_holiday.granted_at", "tbl_paid_holiday.expired_at", "tbl_paid_holiday.grant_type")
                                ->leftjoin("tbl_employee", "tbl_employee.id", "tbl_paid_holiday.employee_id")
                                ->get();
        $specialHoliday =   DB::table("tbl_special_holiday")
                                ->select("tbl_special_holiday.id as id", "tbl_employee.name as employee_name", "tbl_special_holiday.employee_id", "tbl_special_holiday.granted_at", "tbl_special_holiday.expired_at", "tbl_special_holiday.grant_type")
                                ->leftjoin("tbl_employee", "tbl_employee.id", "tbl_special_holiday.employee_id")
                                ->get();
        $absence =          DB::table("tbl_absence_registration")
                                ->select("tbl_absence_registration.id as id", "tbl_absence_registration.employee_id", "tbl_employee.name as employee_name", "tbl_absence_registration.absence_day")
                                ->leftjoin("tbl_employee", "tbl_employee.id", "tbl_absence_registration.employee_id")
                                ->get();

        for($i = 0; $i < count($absence); $i ++) {
            $start_date = $absence[$i]->absence_day;
            $end_date = '';
            
            for($j = $i; $j < count($absence); $j ++) {
                if($j == (count($absence) - 1)) {
                    $end_date = $absence[$j]->absence_day;
                    $i = $j;
                    break;
                }
                if ($absence[$i]->id != $absence[$j]->id) {
                    $end_date = $absence[$j - 1]->absence_day;
                    $i = $j - 1;
                    break;
                }
            }

            $temp = (object)[];
            $temp->start = $start_date;
            $temp->end = $end_date ? $end_date : $start_date;
            $temp->title = $absence[$i]->employee_name;
            $temp->url = '/treat_single?id=' . $absence[$i]->employee_id;
            $temp->color = $this->getColor('absence');
            array_push($eventData, $temp);
        }

        for($i = 0; $i < count($paidHoliday); $i ++) {
            if($paidHoliday[$i]->expired_at == null) {
                $start_date = $paidHoliday[$i]->granted_at;
                $end_date = '';

                for($j = $i; $j < count($paidHoliday); $j ++) {
                    if($j == (count($paidHoliday) - 1)) {
                        $end_date = $paidHoliday[$j]->granted_at;
                        $i = $j;
                        break;
                    }
                    if($paidHoliday[$i]->id != $paidHoliday[$j]->id) {
                        $end_date = $paidHoliday[$j - 1]->granted_at;
                        $i = $j - 1;
                        break;
                    }
                }

                $temp = (object)[];
                $temp->start = $start_date;
                $temp->end = $end_date;
                $temp->title = $paidHoliday[$i]->employee_name;
                $temp->url = '/treat_single?id='. $paidHoliday[$i]->employee_id;
                $temp->color = $this->getColor($paidHoliday[$i]->grant_type);
                array_push($eventData, $temp);
            }
        }

        for($i = 0; $i < count($specialHoliday); $i ++) {
            if($specialHoliday[$i]->expired_at == null) {
                $start_date = $specialHoliday[$i]->granted_at;
                $end_date = '';

                for($j = $i; $j < count($specialHoliday); $j ++) {
                    if($j == (count($specialHoliday) - 1)) {
                        $end_date = $specialHoliday[$j]->granted_at;
                        $i = $j;
                        break;
                    }
                    if($specialHoliday[$i]->id != $specialHoliday[$j]->id) {
                        $end_date = $specialHoliday[$j - 1]->granted_at;
                        $i = $j - 1;
                        break;
                    }
                }

                $temp = (object)[];
                $temp->start = $start_date;
                $temp->end = $end_date;
                $temp->title = $specialHoliday[$i]->employee_name;
                $temp->url = '/treat_single?id='. $specialHoliday[$i]->employee_id;
                $temp->color = $this->getColor($specialHoliday[$i]->grant_type);
                array_push($eventData, $temp);
            }
        }

        $department = DB::table("tbl_department")->get();
        for($i = 0; $i < count($department); $i ++) {
            $department[$i]->rate = '60 %';
        }
        $results['department'] = $department;

        $overallrate = '80 %';
        $results['overallrate'] = $overallrate;
        
        $results['eventData'] = $eventData;
        return response(['status' => 'success', 'data' => $results]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
