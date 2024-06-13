<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ObligationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function show(Request $request)
    {
        $name = $request->name ? $request->name : '';
        $department = $request->department ? $request->department : '';
        $emp_number = $request->emp_number ? $request->emp_number : '';
        $digestion_day = $request->digestion_day ? $request->digestion_day : '';
        $status = $request->status ? $request->status : '';

        $sql = "SELECT tbl_employee.*, tbl_department.name AS department_name FROM tbl_employee LEFT JOIN tbl_department ON tbl_employee.department = tbl_department.id WHERE";
        $params = [];
        if($name != '') {
            $sql .= " tbl_employee.name LIKE ?";
            $name = '%' . $name . '%';
            array_push($params, $name);
        } else {
            $sql .= " tbl_employee.name != ''";
        }

        if($department != '') {
            $sql .= " AND department = ?";
            array_push($params, $department);
        }

        if($emp_number != '') {
            $sql .= " AND employee_number LIKE ?";
            $emp_number = '%' . $emp_number . '%';
            array_push($params, $emp_number);
        }

        // if($working_type != '') {
        //     $sql .= " AND working_type = ?";
        //     array_push($params, $working_type);
        // }

        // if($status != '') {
        //     $sql .= " AND `status` = ?";
        //     array_push($params, $status);
        // }

        $employees = DB::select($sql, $params);
        $count = 0;
        if($employees) {
            for($i = 0; $i < count($employees); $i ++) {
                if($employees[$i]->email) {
                    $count ++;
                }
                $employees[$i]->base_date = '';
                $employees[$i]->obligation_date = '';
                $employees[$i]->deadline = '';
                $employees[$i]->used_days = '';
                $employees[$i]->check = 1;
                $employees[$i]->note = '基準日がありません';
            }

            return response(['status' => 'success', 'data' => $employees, 'count' => $count]);
        }
        return response(['status' => 'failure', 'msg' => '該当する資料が存在しません。']);
    }

    /**
     * Send notification
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $members = explode(',', $request->members);
        var_dump($members);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

    }
}
