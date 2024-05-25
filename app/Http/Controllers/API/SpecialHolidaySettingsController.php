<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SpecialHolidaySettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SpecialHolidaySettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $spcialholidaysettings = SpecialHolidaySettings::get();
        return response(['status' => 'success', 'data' => $spcialholidaysettings]);
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
        ]);

        if ($validator->fails()) {
            return response(['status' => 'failure', 'msg' => '必須情報を正確に入力してください。']);
        }

        $spcialholidaysettings = SpecialHolidaySettings::create($data);
        return response(['status' =>'success', 'data' => $spcialholidaysettings]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $spcialholidaysettings = SpecialHolidaySettings::find($request->id);
        if($spcialholidaysettings) {
            return response(['status' => 'success', 'data' => $spcialholidaysettings]);
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
        ]);

        if ($validator->fails()) {
            return response(['status' => 'failure', 'msg' => '必須情報を正確に入力してください。']);
        }

        SpecialHolidaySettings::where('id', $request->id)->update($data);
        return response(['status' =>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $res = SpecialHolidaySettings::where('id', $request->id)->delete();
        if($res) {
            return response(['status' =>'success', 'data' => $res]);
        }
        return response(['status' =>'failure', 'msg' => 'データを削除できませんでした。']);
    }
}
