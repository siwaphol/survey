<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;

use App\Http\Requests;

class MenuController extends Controller
{
    public function index()
    {
        $result = [];
        $menus = Menu::orderBy('parent_id','ASC')
            ->orderBy('order', 'ASC')
            ->get();

        foreach ($menus as $row){
            
        }

        return json_encode(['data'=>[]]);
    }

    protected function createTree($data, &$result)
    {

    }
}
