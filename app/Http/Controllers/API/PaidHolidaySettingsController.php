<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaidHolidaySettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaidHolidaySettingsController extends Controller
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
        $settings = PaidHolidaySettings::first();
        $settings->acquisition_order_label = $this->acquisition_order[$settings->acquisition_order];
        $settings->minimum_acquisition_unit_label = $this->minimum_acquisition_unit[$settings->minimum_acquisition_unit];
        $settings->scheduled_working_hours_label = $this->scheduled_working_hours[$settings->scheduled_working_hours];
        $settings->grant_implementation_date_label = $this->grant_implementation_date[$settings->grant_implementation_date];
        $settings->acquisition_order_container = $this->acquisition_order;
        $settings->minimum_acquisition_unit_container = $this->minimum_acquisition_unit;
        $settings->scheduled_working_hours_container = $this->scheduled_working_hours;
        $settings->grant_implementation_date_container = $this->grant_implementation_date;
        return response(['status' => 'success', 'data' => $settings]);
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

        if ($request->date_of_expiry_year && intval($request->date_of_expiry_year) < 2) {
            return response(['status' => 'failure', 'msg' => '有効期限は合計が2年以上になるように指定してください。']);
        }

        PaidHolidaySettings::where('id', 1)->update($data);
        return response(['status' =>'success']);
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
