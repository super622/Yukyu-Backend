<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::get();
        return response(['status' => 'success', 'data' => $employees]);
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

        $validator = Validator::make($data, [
            'name' =>'required',
            'hire_date' =>'required',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'failure', 'error' => $validator->errors()]);
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
        return response(['status' => 'failure']);
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
            return response(['status' => 'failure', 'error' => $validator->errors()]);
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
        return response(['status' =>'failure', 'data' => $res]);
    }
}
