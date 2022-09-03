<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Userinfo;

class VerifyLocationController extends Controller
{
    public function index(){
        return view('verify.location');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'region' => 'required',
            'province' => 'required',
            'city' => 'required',
            'baranggay' => 'required',
        ]);

        dd($request->all());
    }
}
