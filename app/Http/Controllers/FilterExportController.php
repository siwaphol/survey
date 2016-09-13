<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;

use App\Http\Requests;

class FilterExportController extends Controller
{
    public function index()
    {
        $menus = Menu::whereNull('parent_id')
            ->with('submenu')
            ->get();



        return view('filter.index', compact('menus'));
    }
}
