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

    public function importExcelSettingParameter()
    {
//        $filename = "setting_parameters.xlsx";
        $filename = "parameter_fuel.xlsx";
        $category = 'fuel_';

        $path = storage_path("excel\\" . $filename);

        if(explode('.', $filename)[1] === 'xls'){
            $objReader = \PHPExcel_IOFactory::createReader("Excel5");
        }
        else if(explode('.', $filename)[1] === 'xlsx')
        {
            $objReader = \PHPExcel_IOFactory::createReader("Excel2007");
        }

        $objReader->setReadDataOnly(true);
        $worksheetData = $objReader->listWorksheetInfo($path);

        $chunkSize = 10000;
        $chunkFilter = new \App\Custom\ChunkReadFilter();
        $objReader->setReadFilter($chunkFilter);

        $optionSheetNo = 0;
        $totalRows = $worksheetData[$optionSheetNo]['totalRows'];
        $parameterId = 1;
        $aText = $bText = $cText = $dText = '';

        $settingGroup = SettingGroup::findOrNew(8);
        $settingGroup->name_en = ' ';
        $settingGroup->name_th = ' ';
        $settingGroup->save();
        $settingGroup = SettingGroup::findOrNew(9);
        $settingGroup->name_en = 'tool_factor';
        $settingGroup->name_th = 'แฟกเตอร์อุปกรณ์';
        $settingGroup->save();
        $settingGroup = SettingGroup::findOrNew(10);
        $settingGroup->name_en = 'season_factor';
        $settingGroup->name_th = 'แฟกเตอร์ฤดูกาล';
        $settingGroup->save();
        $settingGroup = SettingGroup::findOrNew(11);
        $settingGroup->name_en = 'usage_factor';
        $settingGroup->name_th = 'แฟกเตอร์การใช้งาน';
        $settingGroup->save();
        $settingGroup = SettingGroup::findOrNew(12);
        $settingGroup->name_en = 'volume';
        $settingGroup->name_th = 'ปริมาตร';
        $settingGroup->save();
        $settingGroup = SettingGroup::findOrNew(13);
        $settingGroup->name_en = 'fuel_price';
        $settingGroup->name_th = 'ราคาน้ำมัน';
        $settingGroup->save();

        for ($startRow = 5; $startRow <= $totalRows; $startRow += $chunkSize) {
            $chunkFilter->setRows($startRow,$chunkSize);

            $objPHPExcel = $objReader->load($path);

            $sheetData = $objPHPExcel
                ->getSheet($optionSheetNo)
                ->toArray(null,true,true,true);

            \DB::transaction(function () use ($startRow, $chunkSize, $totalRows,$sheetData,$parameterId,$aText,$bText,$cText,$dText,$category){
//                Setting::whereIn('group_id',array(1,9,10,11))->delete();
                for($i=$startRow; $i <= ($startRow+$chunkSize-1); $i++) {
                    if ($i > $totalRows) {
                        break;
                    }

                    if (!empty(trim($sheetData[$i]["A"])))
                        $aText = $sheetData[$i]["A"];
                    if (!empty(trim($sheetData[$i]["B"])))
                        $bText = $sheetData[$i]["B"];
                    if (!empty(trim($sheetData[$i]["C"])))
                        $cText = $sheetData[$i]["C"];
                    if (!empty(trim($sheetData[$i]["D"])))
                        $dText = $sheetData[$i]["D"];

//                    $newSetting = new Setting();
//                    $newSetting->name_en = ' ';
//                    $newSetting->name_th = $aText.'/'.$bText.'/'.$cText.'/'.$dText;
//                    $newSetting->code = 'electric_power_'.$parameterId;
//                    $newSetting->value = (float)$sheetData[$i]["E"];
//                    $newSetting->unit_of_measure = 'กิโลวัตต์';
//                    $newSetting->group_id = 1;
//                    $newSetting->save();

                    $newSetting = new Setting();
                    $newSetting->name_en = ' ';
                    $newSetting->name_th = $aText.'/'.$bText.'/'.$cText.'/'.$dText;
                    $newSetting->code = 'tool_factor_' . $category .$parameterId;
                    $newSetting->value = (float)$sheetData[$i]["F"];
                    $newSetting->group_id = 9;
                    $newSetting->save();

                    $newSetting = new Setting();
                    $newSetting->name_en = ' ';
                    $newSetting->name_th = $aText.'/'.$bText.'/'.$cText.'/'.$dText;
                    $newSetting->code = 'season_factor_' . $category .$parameterId;
                    $newSetting->value = (float)$sheetData[$i]["G"];
                    $newSetting->group_id = 10;
                    $newSetting->save();

                    $newSetting = new Setting();
                    $newSetting->name_en = ' ';
                    $newSetting->name_th = $aText.'/'.$bText.'/'.$cText.'/'.$dText;
                    $newSetting->code = 'usage_factor_' . $category .$parameterId;
                    $newSetting->value = (float)$sheetData[$i]["H"];
                    $newSetting->group_id = 11;
                    $newSetting->save();
                    // ปริมาตร
                    if (trim($sheetData[$i]["I"])!==""){
                        $newSetting = new Setting();
                        $newSetting->name_en = ' ';
                        $newSetting->name_th = $aText.'/'.$bText.'/'.$cText.'/'.$dText;
                        $newSetting->code = 'volume_' . $category .$parameterId;
                        $newSetting->value = (float)$sheetData[$i]["I"];
                        $newSetting->group_id = 12;
                        $newSetting->save();
                    }

                    // รคาน้ำมัน
                    if (trim($sheetData[$i]["J"])!==""){
                        $newSetting = new Setting();
                        $newSetting->name_en = ' ';
                        $newSetting->name_th = $aText.'/'.$bText.'/'.$cText.'/'.$dText;
                        $newSetting->code = 'fuel_price_' . $category .$parameterId;
                        $newSetting->value = (float)$sheetData[$i]["J"];
                        $newSetting->group_id = 13;
                        $newSetting->save();
                    }

                    $parameterId++;
                }
            });
        }
        dd("complete");

    }

}
