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

        $settingGroup = SettingGroup::lists('name_th','id')->toArray();
        array_unshift($settingGroup,'ทั้งหมด');

        return view('setting.index', compact('settings', 'settingGroup'));
    }

    public function create()
    {
        $settingGroups = SettingGroup::get();

        return view('setting.create', compact('settingGroups'));
    }

    public function edit($id)
    {
        $setting = Setting::findOrFail($id);

        if (is_null($setting))
            abort(404);

        $settingGroups = SettingGroup::get();

        return view('setting.edit', compact('setting', 'settingGroups'));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name_th'=>'required',
            'code'=>'required',
            'value'=>'required|numeric',
            'unit_of_measure'=>'required',
            'group_id'=>'required|integer',
            'category'=>'required'
        ]);

        if ($validator->fails()){
            return redirect('setting/create')
                ->withErrors($validator)
                ->withInput();
        }

        $duplicateCode = Setting::where('code', $request->input('code'))
            ->first();

        if (!is_null($duplicateCode)){
            return redirect('setting/create')
                ->withErrors([
                    'code'=>'รหัสซ้ำกับ' . $duplicateCode->name_th
                ])
                ->withInput();
        }

        $setting = Setting::create($request->input());

        flash('เพิ่มค่าได้สำเร็จ', 'success');

        return redirect('/setting');
    }
    
    public function update(Request $request, $id)
    {
        $setting= Setting::find($id);
        if (is_null($setting))
            return redirect('/setting');

        $validator = \Validator::make($request->all(), [
            'name_th'=>'required',
            'code'=>'required',
            'value'=>'required|numeric',
            'unit_of_measure'=>'required',
            'group_id'=>'required|integer',
            'category'=>'required'
        ]);

        if ($validator->fails()){
            return redirect('setting/'. $id)
                ->withErrors($validator)
                ->withInput();
        }

        $duplicateCode = Setting::where('code', $request->input('code'))
            ->where('id','!=',$id)
            ->first();
        
        if (!is_null($duplicateCode)){
            return redirect('setting/'. $id)
                ->withErrors([
                    'code'=>'รหัสซ้ำกับ' . $duplicateCode->name_th
                ])
                ->withInput();
        }

        $setting->fill($request->input());
        $setting->save();

        flash('บันทึกค่าสำเร็จ', 'success');

        return redirect('/setting');
    }
}
