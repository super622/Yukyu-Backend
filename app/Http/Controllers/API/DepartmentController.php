<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::get();
        return response(['status' => 'success', 'data' => $departments]);
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
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'failure', 'error' => $validator->errors()]);
        }

        $department = Department::create($data);
        if($data['member']) {
            $members = explode(',', $data['member']);
            Employee::whereIn('id', $members)->update(['department' => $department->id]);
        }
        return response(['status' => 'success', 'message' => $department]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $product
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function show(Request $request)
    {
        $department = Department::find($request->id);
        if($department) {
            $members = Employee::select("id", "name")->where('department', $request->id)->get();
            $department->members = $members;
            return response(['status' => 'success', 'data' => $department]);
        }
        return response(['status' => 'failure']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['status' => 'failure', 'error' => $validator->errors()]);
        }

        Department::where('id', $request->id)->update(['name' => $request->name, 'priority' => $request->priority, 'number' => $request->number]);
        if($data['member']) {
            $members = explode(',', $data['member']);
            Employee::where("department", "=", $request->id)->whereNotIn('id', $members)->update(['department' => null]);
        }
        return response(['status' =>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $res = Department::where('id', $request->id)->delete();
        if($res) {
            return response(['status' => 'success']);
        }
        return response(['status' =>'failure']);
    }
}
