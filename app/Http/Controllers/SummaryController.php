<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Main;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SummaryController extends Controller
{
    public function sum()
    {
        $w = [];
        $w[1] = 0.66;
        $w[2] = 0.34;
        $w[3] = 0.5;
        $w[4] = 0.5;

        $s = [];
        $s[1] = 526.00;
        $s[2] = 274.00;
        $s[3] = 850.00;
        $s[4] = 850.00;

        $S = [];
        $S[1] = 1432284.00;
        $S[2] = 1432284.00;
        $S[3] = 3034793.00;
        $S[4] = 3034793.00;

        $rows = [
            'หลอดไฟในบ้าน หลอดไส้'=>'no_ch1023_o329_ch101_o68',
            'หลอดไฟในบ้าน หลอดฟลูออเรสเซนต์ ชนิดกลม'=>'no_ch1023_o329_ch101_o69_ch102_o72',
            'หลอดไฟในบ้าน หลอดฟลูออเรสเซนต์ ชนิดตรง ขนาดยาว'=>'no_ch1023_o329_ch101_o69_ch102_o73',
            'หลอดไฟในบ้าน หลอดฟลูออเรสเซนต์ ชนิดตรง ขนาดสั้น'=>'no_ch1023_o329_ch101_o69_ch102_o74',
            'หลอดไฟในบ้าน หลอดคอมแพคฟลูออเรสเซนต์'=>'no_ch1023_o329_ch101_o70',
            'หลอดไฟในบ้าน หลอดแอลอีดี'=>'no_ch1023_o329_ch101_o71',
            'หลอดไฟนอกบ้าน หลอดไส้'=>'no_ch1023_o330_ch112_o68',
            'หลอดไฟนอกบ้าน หลอดฟลูออเรสเซนต์ ชนิดกลม'=>'no_ch1023_o330_ch112_o69_ch113_o72',
            'หลอดไฟนอกบ้าน หลอดฟลูออเรสเซนต์ ชนิดตรง ขนาดยาว'=>'no_ch1023_o330_ch112_o69_ch113_o73',
            'หลอดไฟนอกบ้าน หลอดฟลูออเรสเซนต์ ชนิดตรง ขนาดสั้น'=>'no_ch1023_o330_ch112_o69_ch113_o74',
            'หลอดไฟนอกบ้าน หลอดคอมแพคฟลูออเรสเซนต์'=>'no_ch1023_o330_ch112_o70',
            'หลอดไฟนอกบ้าน หลอดแอลอีดี'=>'no_ch1023_o330_ch112_o71'
        ];

        $whereIn = [];
        $answerIn = [];
        $answerOut = [];
        foreach ($rows as $key=>$value){
            $whereIn[] = $value;

            $p = [];
            $count = [];
            for ($i=1; $i<=4; $i++){
                $mainList = Main::getMainList($i);
                $count[$i] = Answer::where('unique_key', $value)
                    ->whereIn('main_id', $mainList)
                    ->groupBy('main_id')
                    ->get()
                    ->count();
                $p[$i] = $w[$i] * ((float)$count[$i]/ $s[$i]) * $S[$i];
            }
            $answerIn[$key] = (int)($p[1] + $p[2]);
            $answerOut[$key] = (int)($p[3] + $p[4]);
        }


        dd($answerIn, $answerOut);
    }
}
