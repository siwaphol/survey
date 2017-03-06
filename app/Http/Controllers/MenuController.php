<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Menu;
use Illuminate\Http\Request;

use App\Http\Requests;

class MenuController extends Controller
{
    public function index()
    {
        $result = collect();
//        $menus = Menu::orderBy('parent_id','ASC')
//            ->orderBy('order', 'ASC')
//            ->get();
        $mainId = (int)\Session::get('main_id');

        $sqlStr = "SELECT menus.*
                      ,m2.name as parent_name
                      ,a.answer_count as section_count
                      ,a2.answer_count as sub_section_count
                    FROM menus
                    LEFT JOIN menus m2 ON m2.id=menus.parent_id
                    LEFT JOIN
                    (
                      SELECT count(*) as answer_count,section_id,sub_section_id
                      FROM answers
                      WHERE sub_section_id is null AND main_id={$mainId}
                      GROUP BY section_id,sub_section_id
                    ) a
                    ON menus.id=a.section_id
                    LEFT JOIN
                    (
                      SELECT count(*) as answer_count,section_id,sub_section_id
                      FROM answers
                      WHERE main_id={$mainId}
                      GROUP BY section_id,sub_section_id
                    ) a2
                    ON m2.id=a2.section_id and menus.id=a2.sub_section_id
                    ORDER BY menus.parent_id,menus.order";
        $menus = \DB::select($sqlStr);

        foreach ($menus as $row){
            if (is_null($row->parent_id)){
                $result->push($row);
                continue;
            }

            $parentId = $row->parent_id;
            $foundParent = $result->first(function ($key, $value) use ($parentId){
                return (int)$value->id===(int)$parentId;
            });

            if ($foundParent){
                if (!isset($foundParent->children)){
                    $foundParent->pages = collect();
                    $foundParent->children = collect();
                }

                $foundParent->pages->push($row);
                $foundParent->children->push($row);
                continue;
            }

            $result->push($row);
        }

        return $result->toJson();
    }

    public static function getMenuTree()
    {
        $sqlStr = "SELECT menus.*
                      ,m2.name as parent_name
                    FROM menus
                    LEFT JOIN menus m2 ON m2.id=menus.parent_id
                    ON menus.id=a2.sub_section_id
                    ORDER BY menus.parent_id,menus.order";
        $menus = \DB::select($sqlStr);

        return $menus;
    }

    public static function getReportDownloadLink($menu_id, $sub_menu_id=null){

        $mainMenuArr = array(
            2=>'get-report81',
            3=>'get-report82',
            4=>'get-report83',
            34=>'get-report84',
            35=>'get-report85',
            5=>'get-report911',
            7=>'get-report912',
            11=>'get-report913',
            13=>'get-report914',
            16=>'get-report915',
            18=>'get-report916',
            21=>'get-report917',
            23=>'get-report918',
            25=>'get-report11-1',
            26=>'get-report11-2',
            27=>'get-report121',
            28=>'get-report122',
            29=>'get-report123',
            30=>'get-report124',
            31=>'get-report131',
            32=>'get-report132',
            33=>'get-report133'
        );

        if (array_key_exists((int)$menu_id,$mainMenuArr))
            return $mainMenuArr[(int)$menu_id];

        return null;
    }
}
