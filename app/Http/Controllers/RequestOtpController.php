<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requester;

class RequestOtpController extends Controller
{
    //
    public function requestOtp(Request $request){
        $validated = $this->validate($request,[
            'email'=>'required|email'
        ]);
        $requester = Requester::firstOrCreate($validated);
        return view('enterotp');
        // dd($requester);
    }

    public function sendOtp(Request $request){
        dd($request);
    }

    public function otp(Request $request){
        return view('enterotp');
    }
}
