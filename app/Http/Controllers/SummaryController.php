<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Controllers\Summary12\Summary121;
use App\Http\Controllers\Summary9\Summary91;
use App\Http\Controllers\Summary8\Summary81;
use App\Http\Controllers\Summary8\Summary82;
use App\Http\Controllers\Summary8\Summary83;
use App\Http\Controllers\Summary8\Summary84;
use App\Http\Controllers\Summary8\Summary85;
use App\Http\Controllers\Summary12\Summary122;
use App\Http\Controllers\Summary12\Summary123;
use App\Http\Controllers\Summary12\Summary124;
use App\Http\Controllers\Summary13\Summary131;
use App\Http\Controllers\Summary13\Summary132;
use App\Http\Controllers\Summary13\Summary133;
use App\Http\Controllers\Summary9\Summary912;
use App\Http\Controllers\Summary9\Summary913;
use App\Http\Controllers\Summary9\Summary914;
use App\Http\Controllers\Summary9\Summary915;
use App\Http\Controllers\Summary9\Summary916;
use App\Http\Controllers\Summary9\Summary917;
use App\Http\Controllers\Summary9\Summary918;
use App\Main;
use App\Menu;
use App\Parameter;
use App\Summary;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SummaryController extends Controller
{
    public function downloadSum81()
    {
        Summary81::report81();
        return response()->download(storage_path('excel/sum81.xlsx'), '8.1 สภาพภูมิศาสตร์ของครัวเรือน.xlsx');
    }

    public function downloadSum82()
    {
        Summary82::report82();
        return response()->download(storage_path('excel/sum82.xlsx'), '8.2 ข้อมูลพื้นฐานของครัวเรือน.xlsx');
    }

    public function downloadSum83()
    {
        Summary83::report83();
        return response()->download(storage_path('excel/sum83.xlsx'), '8.3 ระดับการศึกษาของครัวเรือน.xlsx');
    }

    public function downloadSum84()
    {
        Summary84::report84();
        return response()->download(storage_path('excel/sum84.xlsx'), '8.4 อาชีพหลักและอาชีพรองของครัวเรือน.xlsx');
    }

    public function downloadSum85()
    {
        Summary85::report85();
        return response()->download(storage_path('excel/sum85.xlsx'), '8.5 รายได้และรายจ่ายของครัวเรือน.xlsx');
    }

    public function downloadSum911()
    {
//        app('App\Http\Controllers\SummaryController')->report911();
        Summary91::report911();
        return response()->download(storage_path('excel/sum911.xlsx'), '9.1.1 หมวดแสงสว่าง.xlsx');
    }

    public function downloadSum912()
    {
        Summary912::report912();
        return response()->download(storage_path('excel/sum912.xlsx'), '9.1.2 หมวดประกอบอาหาร.xlsx');
    }

    public function downloadSum913()
    {
        Summary913::report913();
        return response()->download(storage_path('excel/sum913.xlsx'), '9.1.3 หมวดข่าวสารบันเทิง.xlsx');
    }

    public function downloadSum914()
    {
        Summary914::report914();
        return response()->download(storage_path('excel/sum914.xlsx'), '9.1.4 หมวดความสะดวกสบาย.xlsx');
    }

    public function downloadSum915()
    {
        Summary915::report915();
        return response()->download(storage_path('excel/sum915.xlsx'), '9.1.5 หมวดเพื่อความอบอุ่น.xlsx');
    }

    public function downloadSum916()
    {
        Summary916::report916();
        return response()->download(storage_path('excel/sum916.xlsx'), '9.1.6 หมวดไล่และล่อแมลง.xlsx');
    }
    public function downloadSum917()
    {
        Summary917::report917();
        return response()->download(storage_path('excel/sum917.xlsx'), '9.1.7 หมวดการเดินทางและคมนาคม.xlsx');
    }
    public function downloadSum918()
    {
        Summary918::report918();
        return response()->download(storage_path('excel/sum918.xlsx'), '9.1.8 หมวดเกษตรกรรม.xlsx');
    }

    public function deleteDuplicate()
    {
        $sqlStr = "SELECT main_id,unique_key FROM `answers` group BY main_id,unique_key HAVING COUNT(*) > 1";
        $result = \DB::select($sqlStr);

        foreach ($result as $row){
            $dupAns = Answer::where('main_id', $row->main_id)
                ->where('unique_key', $row->unique_key)
                ->orderBy('updated_at','DESC')
                ->get();
            if (count($dupAns)>1){
                $count = count($dupAns);
                for ($i=1;$i<$count;$i++){
//                    if ( $this->checkDuplicate($dupAns[0], $dupAns[$i])){
                        echo 'D: ' . $dupAns[$i]->main_id . '-' . $dupAns[$i]->unique_key . "\n";
                        $deleteAns = Answer::find($dupAns[$i]->id);
                        $deleteAns->delete();
//                    }
                }
            }
        }
    }

    protected function checkDuplicate($first, $second)
    {
        return $first->main_id==$second->main_id &&
        $first->question_id==$second->question_id &&
        $first->answer_numeric==$second->answer_numeric &&
        $first->answer_text==$second->answer_text;
    }

    public function index()
    {
        $main = Menu::whereNull('parent_id')
            ->orderBy('order')
            ->get();
        return view('summary.index', compact('main'));
    }

    public function testReport()
    {
        app('App\Http\Controllers\SummaryController')->sum();
        app('App\Http\Controllers\SummaryController')->average();
        app('App\Http\Controllers\SummaryController')->usage();
        app('App\Http\Controllers\SummaryController')->average2();

        return response()->download(storage_path('excel/sum91.xlsx'), 'ตารางสรุปหมวดแสงสว่าง.xlsx');
    }

    public function downloadSum121()
    {
        Summary121::report121();
        return response()->download(storage_path('excel/sum121.xlsx'), '12.1 การเผาถ่าน.xlsx');
    }

    public function downloadSum122()
    {
        Summary122::report122();
        return response()->download(storage_path('excel/sum122.xlsx'), '12.2 การผลิตก๊าซชีวภาพ.xlsx');
    }

    public function downloadSum123()
    {
        Summary123::report123();
        return response()->download(storage_path('excel/sum123.xlsx'), '12.3 การผลิตไฟฟ้าด้วยเครื่องปั่นไฟ.xlsx');
    }

    public function downloadSum124()
    {
        Summary124::report124();
        return response()->download(storage_path('excel/sum124.xlsx'), '12.4 การผลิตไฟฟ้าด้วยเซลล์แสงอาทิตย๋.xlsx');
    }

    public function downloadSum131()
    {
        Summary131::report131();
        return response()->download(storage_path('excel/sum131.xlsx'), '13.1 แนวโน้มการเปลี่ยนการใช้พลังงานในการประกอบอาหาร.xlsx');
    }

    public function downloadSum132()
    {
        Summary132::report132();
        return response()->download(storage_path('excel/sum132.xlsx'), '13.2 แนวโน้มการเปลี่ยนการใช้พลังงานในการเดินทางและคมนาคม.xlsx');
    }

    public function downloadSum133()
    {
        Summary133::report133();
        return response()->download(storage_path('excel/sum133.xlsx'), '13.3 แนวโน้มการเปลี่ยนการใช้ยานพาหนะในการเดินทาง.xlsx');
    }

    public function download($menu_id)
    {
        if (!$menu_id)
            return abort(404);
        $menu_id = (int)$menu_id;
        if ($menu_id===2)
            return response()->download(storage_path('excel/sum81.xlsx'), '8.1 สภาพภูมิศาสตร์ของครัวเรือน.xlsx');
        elseif ($menu_id===3)
            return response()->download(storage_path('excel/sum82.xlsx'), '8.2 ข้อมูลพื้นฐานของครัวเรือน.xlsx');
        elseif ($menu_id===4)
            return response()->download(storage_path('excel/sum83.xlsx'), '8.3 ระดับการศึกษาของครัวเรือน.xlsx');
        elseif ($menu_id===34)
            return response()->download(storage_path('excel/sum84.xlsx'), '8.4 อาชีพหลักและอาชีพรองของครัวเรือน.xlsx');
        elseif ($menu_id===35)
            return response()->download(storage_path('excel/sum85.xlsx'), '8.5 รายได้และรายจ่ายของครัวเรือน.xlsx');
        elseif ($menu_id===5)
            return response()->download(storage_path('excel/sum911.xlsx'), '9.1.1 หมวดแสงสว่าง.xlsx');
        elseif ($menu_id===7)
            return response()->download(storage_path('excel/sum912.xlsx'), '9.1.2 หมวดประกอบอาหาร.xlsx');
        elseif ($menu_id===11)
            return response()->download(storage_path('excel/sum913.xlsx'), '9.1.3 หมวดข่าวสารบันเทิง.xlsx');
        elseif ($menu_id===13)
            return response()->download(storage_path('excel/sum914.xlsx'), '9.1.4 หมวดความสะดวกสบาย.xlsx');
        elseif ($menu_id===16)
            return response()->download(storage_path('excel/sum915.xlsx'), '9.1.5 หมวดเพื่อความอบอุ่น.xlsx');
        elseif ($menu_id===18)
            return response()->download(storage_path('excel/sum916.xlsx'), '9.1.6 หมวดไล่และล่อแมลง.xlsx');
        elseif ($menu_id===21)
            return response()->download(storage_path('excel/sum917.xlsx'), '9.1.7 หมวดการเดินทางและคมนาคม.xlsx');
        elseif ($menu_id===23)
            return response()->download(storage_path('excel/sum918.xlsx'), '9.1.8 หมวดเกษตรกรรม.xlsx');
        elseif ($menu_id===25)
            return response()->download(storage_path('excel/sum11_1.xlsx'), '11.1 แหล่งพลังงานที่หาเองได้.xlsx');
        elseif ($menu_id===26)
            return response()->download(storage_path('excel/sum11_2.xlsx'), '11.2 แหล่งพลังงานที่ซื้อ.xlsx');
        elseif($menu_id===27)
            return response()->download(storage_path('excel/sum121.xlsx'), '12.1 การเผาถ่าน.xlsx');
        elseif($menu_id===28)
            return response()->download(storage_path('excel/sum122.xlsx'), '12.2 การผลิตก๊าซชีวภาพ.xlsx');
        elseif($menu_id===29)
            return response()->download(storage_path('excel/sum123.xlsx'), '12.3 การผลิตไฟฟ้าด้วยเครื่องปั่นไฟ.xlsx');
        elseif($menu_id===30)
            return response()->download(storage_path('excel/sum124.xlsx'), '12.4 การผลิตไฟฟ้าด้วยเซลล์แสงอาทิตย๋.xlsx');
        elseif ($menu_id===31)
            return response()->download(storage_path('excel/sum131.xlsx'), '13.1 แนวโน้มการเปลี่ยนการใช้พลังงานในการประกอบอาหาร.xlsx');
        elseif ($menu_id===32)
            return response()->download(storage_path('excel/sum132.xlsx'), '13.2 แนวโน้มการเปลี่ยนการใช้พลังงานในการเดินทางและคมนาคม.xlsx');
        elseif ($menu_id===33)
            return response()->download(storage_path('excel/sum133.xlsx'), '13.3 แนวโน้มการเปลี่ยนการใช้ยานพาหนะในการเดินทาง.xlsx');

        return;
    }
}
