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

        $setting->fill($request->input());
        $setting->save();
    }
}
