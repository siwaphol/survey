<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class AnswerController extends Controller
{
    public function testPost(Request $request)
    {
        dd($request->input());
    }
}
