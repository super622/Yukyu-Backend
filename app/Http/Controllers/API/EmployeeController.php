<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = $request->name ? $request->name : '';
        $department = $request->department ? $request->department : '';
        $emp_number = $request->emp_number ? $request->emp_number : '';
        $working_type = $request->working_type ? $request->working_type : '';
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

        if($working_type != '') {
            $sql .= " AND working_type = ?";
            array_push($params, $working_type);
        }

        if($status != '') {
            $sql .= " AND `status` = ?";
            array_push($params, $status);
        }

        $employees = DB::select($sql, $params);
        foreach($employees as $employee) {
            if($employee->status == 0) {
                $employee->status_label = '在職中';
            } else if($employee->status == 1) {
                $employee->status_label = '休職中';
            } else if($employee->status == 2) {
                $employee->status_label = '退職済み';
            }
            $employee->paid_holidays = '1日と6時間';
            $employee->special_holidays = '0日と2時間';
            $employee->acquisition_rate = '12 %';
            $employee->grant_date = '2022/09/01';
            $employee->working_type_label = $this->working_type[$employee->working_type];
        }
        return response(['status' => 'success', 'data' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function csv_store(Request $request)
    {
        $data = $request->all();
        $departments = Department::select('name, id')->get();

        $validator = Validator::make($data, [
            'file' => 'required|file|mimes:csv,txt',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'failure', 'msg' => 'CSVファイルの読み込みに失敗しました']);
        }

        $file = $request->file('file');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));

        $res = array_diff($data[0], $this->csv_headers);

        if (!empty($res)) {
            return response(['status' => 'error', 'message' => 'CSVファイルの形式が正しくありません']);
        }

        $i = 0;
        foreach($data as $row) {
            if($i != 0) {
                $working_type = array_search($row[2], $this->working_type);
                $status = strlen($row[4]) > 1 ? 2 : 0;
                $status = strlen($row[5]) > 1 ? 1 : 0;

                $department = '';
                foreach($departments as $dep) {
                    if($dep->name == $row[7]) {
                        $department = $dep->id;
                    }
                }

                $employee = Employee::create([
                    'name' => $row[0],
                    'kana_name' => $row[1],
                    'working_type' => $working_type,
                    'hire_date' => $row[3],
                    'status' => $status,
                    'employee_number' => $row[6],
                    'department' => $department,
                    'working_hours' => $row[8],
                ]);
            }
            $i ++;
        }
        return response(['status' =>'success', 'data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        if ($request->name == '') {
            return response(['status' => 'failure', 'msg' => '名前を入力してください']);
        }

        if ($request->hire_date == '') {
            return response(['status' => 'failure', 'msg' => '入社日を入力してください']);
        }

        $employee = Employee::create($data);
        return response(['status' =>'success', 'data' => $employee]);
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
        $employee = Employee::find($request->id);
        if($employee) {
            $department = Department::find($employee->department);
            $employee->department_label = $department->name;
            $employee->working_type_label = $this->working_type[$employee->working_type];
            $employee->working_hours_label = $employee->working_hours . '時間';
            return response(['status' => 'success', 'data' => $employee]);
        }
        return response(['status' => 'failure', 'msg' => '該当する資料が存在しません。']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function get_employee_without_department(Request $request)
    {
        $employee = Employee::select("tbl_employee.*", "tbl_department.name as department_name")
                                ->where('department', '=', null)
                                ->where('name', '!=', '')
                                ->leftjoin("tbl_employee", "tbl_employee.department", "tbl_department.id")
                                ->get();
        if($employee) {
            return response(['status' => 'success', 'data' => $employee]);
        }
        return response(['status' => 'failure', 'msg' => '該当する資料が存在しません。']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'id' => 'required',
            'name' =>'required',
            'hire_date' =>'required',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'failure', 'msg' => '必須情報を正確に入力してください。']);
        }

        Employee::where('id', $request->id)->update($data);
        return response(['status' =>'success']);
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
        $res = Employee::where('id', $request->id)->delete();
        if($res) {
            return response(['status' =>'success', 'data' => $res]);
        }
        return response(['status' =>'failure', 'msg' => 'データを削除できませんでした。']);
    }
}
