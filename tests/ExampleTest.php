<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $input = ['no_te1'=>'123'];
        $a = explode("_", 'no_te1');
        $this->assertTrue(\App\Answer::parentIsSelectedOrNoParent($a, $input));

        $input = ['no_ch1_o2_nu3'=>1, 'no_ch1_o2'=>true];
        $a = explode("_",'no_ch1_o2_nu3');
        $this->assertTrue(\App\Answer::parentIsSelectedOrNoParent($a, $input));

        $input = ['no_ti1_nu3'=>1];
        $a = explode("_",'no_ti1_nu3');
        $this->assertTrue(\App\Answer::parentIsSelectedOrNoParent($a, $input));
        //With title between input
        $input = ['no_te1_ti2_nu3'=>1, 'no_te1'=>'123'];
        $a = explode("_",'no_te1_ti2_nu3');
        $this->assertTrue(\App\Answer::parentIsSelectedOrNoParent($a, $input));

        // advance
        $input = ["no_ra808"=> "81",
            "no_ra808_o81_ti809_ch810_o228" => true,
            "no_ra808_o81_ti809_ch810_o228_nu819"=> 2,
            "no_ra808_o81_ti809_ch810_o228_nu820"=> 3,
            "no_ra808_o81_ti809_ch810_o228_ra811"=>"326",
            "no_ra808_o81_ti809_ch810_o228_ra812"=> "225"];
        $a = explode("_", "no_ra808_o81_ti809_ch810_o228_ra811");
        $this->assertTrue(\App\Answer::parentIsSelectedOrNoParent($a, $input));

    }

}
