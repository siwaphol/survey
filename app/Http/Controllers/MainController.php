<?php

namespace App\Http\Controllers;

use App\Main;
use Illuminate\Http\Request;

use App\Http\Requests;

class MainController extends Controller
{
    public function index()
    {
        return view('main_id_input');
    }

    public function postHandle(Request $request)
    {
        $user_id = \Auth::user()->id;
        $main = Main::where([
            'main_id'=>$request->input('main_id'),
            'recorder_id'=>$user_id
        ])->first();
        if (is_null($main))
            $main = Main::create([
                'main_id'=>$request->input('main_id'),
                'recorder_id'=>$user_id
            ]);

        \Session::put('main_id', $main->main_id);
        \Session::put('recorder_id', $user_id);

        return redirect('html-loop-2/1');
    }
}
