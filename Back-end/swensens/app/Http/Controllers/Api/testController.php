<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class testController extends Controller
{
    public function hello(Request $req){
        return response()->json(['message'=> 'hello world this is API']);
        // return response()->json($req->all());
    }
}
