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
        //TODO-nong database need to be change
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

    protected function createTree($data, &$result)
    {

    }
}
