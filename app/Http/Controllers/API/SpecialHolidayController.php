<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SpecialHoliday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SpecialHolidayController extends Controller
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

        if($request->type == "given_new") {
            $validator = Validator::make($data, [
                'granted_at' => 'required',
                'expired_at' => 'required',
                'granted_days_type' => 'required',
                'granted_days' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response(['status' => 'failure', 'msg' => $validator->errors()]);
            }

            $granted_at = Carbon::parse($request->granted_at);
            $expired_at = Carbon::parse($request->expired_at);

            $differenceInYears = $granted_at->diffInYears($expired_at);
            if($differenceInYears < 2) {
                return response(['status' => 'failure', 'msg' => '有効期限は付与日の2年以降の値にしてください。']);
            }
            $data['id'] = SpecialHoliday::max('id') + 1;
            $specialholiday = SpecialHoliday::create($data);
            return response(['status' => 'success', 'data' => $specialholiday]);
        } else {
            $validator = Validator::make($data, [
                'granted_at' => 'required',
                'granted_end_at' => 'required',
                'grant_type' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response(['status' => 'failure', 'msg' => $validator->errors()]);
            }

            $startDay = Carbon::parse($request->granted_at);
            $endDay = Carbon::parse($request->granted_end_at);
    
            if($startDay > $endDay) {
                return response(['status' => 'failure', 'msg' => 'absence_day < absence_end_day']);
            }
    
            $specialholidays = [];
            $id = SpecialHoliday::max('id') + 1;
            $date = $startDay;
            while($date <= $endDay) {
                $specialholidays[] = SpecialHoliday::create([
                    'id' => $id,
                    'type' => $data['type'],
                    'employee_id' => $data['employee_id'],
                    'granted_at' => $date->toDateString(),
                    'grant_type' => $data['grant_type'],
                    'holiday_type' => $data['holiday_type'] ? $data['holiday_type'] : 0,
                    'note' => $data['note'] ? $data['note'] : '',
                ]);
                $date = $date->addDay();
            }
            return response(['status' => 'success', 'data' => $specialholidays]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $specialholiday = SpecialHoliday::where('id', '=', $request->id)->get();
        if($specialholiday) {
            return response(['status' => 'success', 'data' => $specialholiday]);
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

        if($request->type == "given_new") {
            $validator = Validator::make($data, [
                'granted_at' => 'required',
                'expired_at' => 'required',
                'granted_days_type' => 'required',
                'granted_days' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response(['status' => 'failure', 'msg' => $validator->errors()]);
            }

            $granted_at = Carbon::parse($request->granted_at);
            $expired_at = Carbon::parse($request->expired_at);

            $differenceInYears = $granted_at->diffInYears($expired_at);
            if($differenceInYears < 2) {
                return response(['status' => 'failure', 'msg' => '有効期限は付与日の2年以降の値にしてください。']);
            }
            SpecialHoliday::where('id', $request->id)->update($data);
            return response(['status' => 'success']);
        } else {
            $validator = Validator::make($data, [
                'granted_at' => 'required',
                'granted_end_at' => 'required',
                'grant_type' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response(['status' => 'failure', 'msg' => $validator->errors()]);
            }

            $startDay = Carbon::parse($request->granted_at);
            $endDay = Carbon::parse($request->granted_end_at);
    
            if($startDay > $endDay) {
                return response(['status' => 'failure', 'msg' => 'absence_day < absence_end_day']);
            }
    
            SpecialHoliday::where('id', $request->id)->delete();

            $specialholidays = [];
            $id = SpecialHoliday::max('id') + 1;
            $date = $startDay;
            while($date <= $endDay) {
                $specialholidays[] = SpecialHoliday::create([
                    'id' => $id,
                    'type' => $data['type'],
                    'employee_id' => $data['employee_id'],
                    'granted_at' => $date->toDateString(),
                    'grant_type' => $data['grant_type'],
                    'holiday_type' => $data['holiday_type'] ? $data['holiday_type'] : 0,
                    'note' => $data['note'] ? $data['note'] : '',
                ]);
                $date = $date->addDay();
            }
            return response(['status' => 'success', 'data' => $specialholidays]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = SpecialHoliday::where('id', $request->id)->delete();
        if($res) {
            return response(['status' =>'success', 'data' => $res]);
        }
        return response(['status' =>'failure', 'data' => $res]);
    }
}
