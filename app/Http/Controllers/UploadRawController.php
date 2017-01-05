<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UploadRawController extends Controller
{
    public function index()
    {
        if (in_array(auth()->user()->email, array('test@email.com','boy.kittikun@gmail.com','aiw_w@hotmail.com','pimphram.setaphram@gmail.com'))){
            $menus = Menu::whereNull('parent_id')
                ->orderBy('order')
                ->get();
            return view('upload.index', compact('menus'));
        }else
            return abort(404);
    }
}
