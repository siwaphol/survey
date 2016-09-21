<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::get();

        return view('setting.index', compact('settings'));
    }

    public function edit($id)
    {
        $setting = Setting::findOrFail($id);
        if (is_null($setting))
            abort(404);

        return view('setting.edit', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $setting= Setting::find($id);
        if (is_null($setting))
            return redirect('/setting');

        $validator = \Validator::make($request->all(), [
            'name_th'=>'required',
            'code'=>'required',
            'value'=>'required',
            'unit_of_measure'=>'required',
            'group_id'=>'required|integer',
            'category'=>'required'
        ]);

        if ($validator->fails()){
            return redirect('setting/'. $id)
                ->withErrors($validator)
                ->withInput();
        }

        $setting->fill($request->input());
        $setting->save();

        return redirect('/setting');
    }
}
