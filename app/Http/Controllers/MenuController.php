<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;

use App\Http\Requests;

class MenuController extends Controller
{
    public function index()
    {
        $result = collect();
        $menus = Menu::orderBy('parent_id','ASC')
            ->orderBy('order', 'ASC')
            ->get();

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
