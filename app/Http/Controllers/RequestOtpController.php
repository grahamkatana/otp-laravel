<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequestOtpController extends Controller
{
    //
    public function requestOtp(Request $request){
        $validated = $this->validate($request,[
            'email'=>'required|email'
        ]);

        dd($validated);
    }
}
