<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AbsenceRegistration;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AbsenceRegistrationController extends Controller
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
        $data = $request->all();

        $validator = Validator::make($data, [
            'employee_id' => 'required',
            'absence_day' => 'required',
            'absence_end_day' => 'required',
            'absence_unit' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'failure', 'msg' => '必須情報を正確に入力してください。']);
        }

        $absenceDay = Carbon::parse($request->absence_day);
        $absenceEndDay = Carbon::parse($request->absence_end_day);

        if($absenceDay > $absenceEndDay) {
            return response(['status' => 'failure', 'msg' => '欠勤日をご確認ください。']);
        }

        $absences = [];
        $id = AbsenceRegistration::max('id') + 1;
        $date = $absenceDay;
        while($date <= $absenceEndDay) {
            $absences[] = AbsenceRegistration::create([
                'id' => $id,
                'employee_id' => $data['employee_id'],
                'absence_day' => $date->toDateString(),
                'absence_unit' => $data['absence_unit'],
                'note' => $data['note'],
            ]);
            $date = $date->addDay();
        }
        return response(['status' => 'success', 'data' => $absences]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $absences = AbsenceRegistration::where('id', '=', $request->id)->orderby('absence_day', 'asc')->get();
        if($absences) {
            $employee = Employee::find($absences[0]->employee_id);
            return response(['status' => 'success', 'data' => $absences, 'emp' => $employee]);
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
            'employee_id' => 'required',
            'absence_day' => 'required',
            'absence_end_day' => 'required',
            'absence_unit' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'failure', 'msg' => '必須情報を正確に入力してください。']);
        }

        $absenceDay = Carbon::parse($request->absence_day);
        $absenceEndDay = Carbon::parse($request->absence_end_day);

        if($absenceDay > $absenceEndDay) {
            return response(['status' => 'failure', 'msg' => '欠勤日をご確認ください。']);
        }

        AbsenceRegistration::where('id', $request->id)->delete();

        $absences = [];
        $date = $absenceDay;
        while($date <= $absenceEndDay) {
            $absences[] = AbsenceRegistration::create([
                'id' => $request->id,
                'employee_id' => $data['employee_id'],
                'absence_day' => $date->toDateString(),
                'absence_unit' => $data['absence_unit'],
                'note' => $data['note'],
            ]);
            $date = $date->addDay();
        }
        return response(['status' => 'success', 'data' => $absences]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $res = AbsenceRegistration::where('id', $request->id)->delete();
        if($res) {
            return response(['status' =>'success', 'data' => $res]);
        }
        return response(['status' =>'failure', 'msg' => 'データを削除できませんでした。']);
    }
}
