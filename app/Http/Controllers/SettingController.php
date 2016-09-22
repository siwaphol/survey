<?php

namespace App\Http\Controllers;

use App\Setting;
use App\SettingGroup;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::leftJoin('setting_groups','settings.group_id','=','setting_groups.id')
            ->select('settings.*','setting_groups.name_th as group_name_th')
        ->get();

        return view('setting.index', compact('settings'));
    }

    public function edit($id)
    {
        $setting = Setting::findOrFail($id);
        if (is_null($setting))
            abort(404);

        $settingGroups = SettingGroup::get();

        return view('setting.edit', compact('setting', 'settingGroups'));
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

        flash('บันทึกค่าสำเร็จ', 'success');

        return redirect('/setting');
    }
}
