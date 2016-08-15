<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Requests;
use App\Main;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function dashboard()
    {
        $countAll = Main::select(\DB::raw("COUNT(DISTINCT main_id) as allCount"))
            ->whereNotNull('submitted_at')
            ->first();

        $table1Sql = "SELECT 
          sum(if(t1.bangkok=1 and t1.inborder=1,1,0)) as bangkokIN
          ,sum(if(t1.bangkok=1 and t1.outborder=1,1,0)) as bangkokOUT
          ,sum(if(t1.northern=1 and t1.inborder=1,1,0)) as northernIN
          ,sum(if(t1.northern=1 and t1.outborder=1,1,0)) as northernOUT
        FROM
          (SELECT sum(if(unique_key='no_ra14' and option_id=7,1,0)) as bangkok
        ,sum(if(unique_key='no_ra14' and option_id=6,1,0)) as northern
        ,sum(if(unique_key='no_ra11' and option_id=4,1,0)) as inborder
        ,sum(if(unique_key='no_ra11' and option_id=5,1,0)) as outborder
        from answers
        WHERE unique_key in ('no_ra11','no_ra14')
        GROUP BY main_id) as t1";
        $table1Result = \DB::select($table1Sql)[0];

        $table2Sql = "SELECT
          sum(if(t1.chiangmai=1 and t1.inborder=1,1,0)) as chiangmaiIN
          ,sum(if(t1.chiangmai=1 and t1.outborder=1,1,0)) as chiangmaiOUT
          ,sum(if(t1.nan=1 and t1.inborder=1,1,0)) as nanIN
          ,sum(if(t1.nan=1 and t1.outborder=1,1,0)) as nanOUT
          ,sum(if(t1.utaradit=1 and t1.inborder=1,1,0)) as utaraditIN
          ,sum(if(t1.utaradit=1 and t1.outborder=1,1,0)) as utaraditOUT
          ,sum(if(t1.pitsanurok=1 and t1.inborder=1,1,0)) as pitsanurokIN
          ,sum(if(t1.pitsanurok=1 and t1.outborder=1,1,0)) as pitsanurokOUT
          ,sum(if(t1.petchabul=1 and t1.inborder=1,1,0)) as petchabulIN
          ,sum(if(t1.petchabul=1 and t1.outborder=1,1,0)) as petchabulOUT
        
          ,sum(if(t1.bangkok=1 and t1.inborder=1,1,0)) as bangkokIN
          ,sum(if(t1.bangkok=1 and t1.outborder=1,1,0)) as bangkokOUT
          ,sum(if(t1.patumtani=1 and t1.inborder=1,1,0)) as patumtaniIN
          ,sum(if(t1.patumtani=1 and t1.outborder=1,1,0)) as patumtaniOUT
          ,sum(if(t1.nontaburi=1 and t1.inborder=1,1,0)) as nontaburiIN
          ,sum(if(t1.nontaburi=1 and t1.outborder=1,1,0)) as nontaburiOUT
          ,sum(if(t1.samutprakarn=1 and t1.inborder=1,1,0)) as samutprakarnIN
          ,sum(if(t1.samutprakarn=1 and t1.outborder=1,1,0)) as samutprakarnOUT
        FROM
          (SELECT
             sum(if(unique_key='no_ra14_o6_ra2002' and option_id=310,1,0)) as chiangmai
            ,sum(if(unique_key='no_ra14_o6_ra2002' and option_id=311,1,0)) as nan
            ,sum(if(unique_key='no_ra14_o6_ra2002' and option_id=312,1,0)) as utaradit
            ,sum(if(unique_key='no_ra14_o6_ra2002' and option_id=313,1,0)) as pitsanurok
            ,sum(if(unique_key='no_ra14_o6_ra2002' and option_id=314,1,0)) as petchabul
        
            ,sum(if(unique_key='no_ra14_o7_ra2003' and option_id=315,1,0)) as bangkok
            ,sum(if(unique_key='no_ra14_o7_ra2003' and option_id=316,1,0)) as patumtani
            ,sum(if(unique_key='no_ra14_o7_ra2003' and option_id=317,1,0)) as nontaburi
            ,sum(if(unique_key='no_ra14_o7_ra2003' and option_id=318,1,0)) as samutprakarn
        
            ,sum(if(unique_key='no_ra11' and option_id=4,1,0)) as inborder
            ,sum(if(unique_key='no_ra11' and option_id=5,1,0)) as outborder
        from answers
        WHERE unique_key in ('no_ra11','no_ra14_o6_ra2002','no_ra14_o7_ra2003')
        GROUP BY main_id) as t1";
        $table2Result = \DB::select($table2Sql)[0];

        $role = new \stdClass();
        $role->role = 'annotation';
        $chart1Data = json_encode([
            ['เขตปกครอง','ในเขตเทศบาล','นอกเขตเทศบาล', $role ],
            ['ภาคเหนือ', $table1Result->northernIN, $table1Result->northernOUT
                , 'ในเขต '.$table1Result->northernIN . ' นอกเขต ' . $table1Result->northernOUT . ' รวม '
                . ((int)$table1Result->northernIN + (int)$table1Result->northernOUT)],
            ['กทม. และปริมณฑล', $table1Result->bangkokIN, $table1Result->bangkokOUT
                , 'ในเขต '.$table1Result->bangkokIN. ' นอกเขต ' . $table1Result->bangkokOUT . ' รวม '
                . ((int)$table1Result->bangkokIN + (int)$table1Result->bangkokOUT)]
        ], JSON_NUMERIC_CHECK);
        \File::put(public_path('/json/chart1.json'), $chart1Data);
        $sumTable1 = (int)$table1Result->northernIN + (int)$table1Result->northernOUT + (int)$table1Result->bangkokIN + (int)$table1Result->bangkokOUT;

        $chart2Columns = array('จังหวัด','ในเขตเทศบาล','นอกเขตเทศบาล', $role);
        $chart2Data = [
            $chart2Columns
        ];
        $table2Arr = [];
        $template = [
            'เชียงใหม่'=>['chiangmaiIN','chiangmaiOUT']
            ,'น่าน'=>['nanIN','nanOUT']
            ,'อุตรดิตถ์'=>['utaraditIN','utaraditOUT']
            ,'พิษณุโลก'=>['pitsanurokIN','pitsanurokOUT']
            ,'เพชรบูรณ์'=>['petchabulIN','petchabulOUT']
            ,'กรุงเทพ'=>['bangkokIN','bangkokOUT']
            ,'ปทุมธานี'=>['patumtaniIN','patumtaniOUT']
            ,'นนทบุรี'=>['nontaburiIN','nontaburiOUT']
            ,'สมุทรปราการ'=>['samutprakarnIN','samutprakarnOUT']
        ];
        $sumTable2 = 0;
        foreach ($template as $key=>$value){
            $rowArr = [];
            $rowArr[] = $key;

            $i = 1;
            $sum = 0;
            $annotation = '';
            foreach ($value as $column){
                $rowArr[] = $table2Result->{$column};
                $annotation .= ' ' . $chart2Columns[$i] . ' ' . $table2Result->{$column};
                $sumTable2 += (int)$table2Result->{$column};
                $sum += (int)$table2Result->{$column};
                $i++;
            }
            $annotation .= ' รวม ' . $sum;
            $rowArr[] = $annotation;
            $chart2Data[] = $rowArr;
        }
        $chart2Data = json_encode($chart2Data, JSON_NUMERIC_CHECK);

        $table3Arr = Main::orderBy('submitted_at','asc')
            ->whereNotNull('submitted_at')
            ->groupBy(\DB::raw('DATE(submitted_at)'))
            ->select(\DB::raw('DATE(submitted_at) as submitted_at, COUNT(DISTINCT main_id) as count'))
            ->get();

        $table4Arr = Main::orderBy('mains.submitted_at','desc')
            ->leftJoin('users', 'mains.recorder_id','=','users.id')
            ->whereNotNull('mains.submitted_at')
            ->groupBy('users.name')
            ->select(\DB::raw('COUNT(DISTINCT mains.main_id) as count, users.name'))
            ->get();

//        dd($table1Arr, $table2Arr, $table3Arr, $table4Arr);
        return view('dashboard', compact('table1Arr', 'table2Arr', 'table3Arr','table4Arr', 'countAll', 'sumTable1'));
    }
}
