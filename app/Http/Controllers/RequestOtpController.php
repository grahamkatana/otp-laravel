<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requester;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpNotification;
use App\Models\Request as OtpRequest;
use App\Rules\Checkotp;

class RequestOtpController extends Controller
{
    //
    public function requestOtp(Request $request){
        // dd();
        $email =$request->input('email');
        if($request->input('save')!=null){
            $check_time = $this->checkOTP($email,$request);
            dd($check_time);

            // do something (create a new otp)
            // dd("new request");
        }
        else{
            // dd($request);
            dd("new request otp");
        }
        $validated = $this->validate($request,[
            'email'=>'required|email'
        ]);
        
        $requester = Requester::firstOrCreate($validated);

        $data =[
            'otp'=>rand(100000,999999),
            'requester_id'=>$requester->id,
            'expires_in'=>date("m/d/Y h:i:s a",strtotime(getenv("OTP_EXPIRES"))),
            'current_requests_count'=>'1'
        ];

        $otp = OtpRequest::create($data);
        $data['email'] = $email;
        Mail::to($email)->send(new OtpNotification($data));
        // return redirect("send_otp/${email}");
        // dd($requester);
    }

    public function sendOtp(Request $request){
        // dd($request);
        $request->validate([
            'otp' => ['required', 'numeric', new Checkotp],
        ]);
    }

    public function otp(Request $request,$email){
        return view('enterotp');
    }
    // 
    public function checkOTP($email){
        $user = Requester::where('email',$email)->first();
        if($user){
            $current_time = strtotime(date("m/d/Y h:i:s a"));
            $record_time = strtotime($user->lastRequest->expires_in);
            $time_span = $current_time-$record_time;
            $minutes = $time_span/60;
            $current_requested = $user->lastRequest->current_requests_count;
            $is_valid = $user->lastRequest->is_valid;

            if(!$is_valid){
                return "invalid_otp";
            }

            else if($minutes<60 && $current_requested==getenv("OTP_MAX_REQUESTS")){
                return "please_wait";
            }
           
            else if($minutes<getenv("OTP_MINUTES")[1]&&$current_requested<getenv("OTP_MAX_REQUESTS") && $is_valid){
                return "check_inbox";
            }
            else if($minutes>=getenv("OTP_MINUTES")[1]&&$current_requested<getenv("OTP_MAX_REQUESTS") && $is_valid){
                $data =[
                    'otp'=>$user->lastRequest->otp,
                    'requester_id'=>$user->id,
                ];
                $id = $user->lastRequest->id;
                $update = OtpRequest::find($id)->update(['expires_in'=>date("m/d/Y h:i:s a",strtotime(getenv("OTP_EXPIRES"))),'otp'=>$user->lastRequest->otp]);
                $data['email'] = $email;
                Mail::to($email)->send(new OtpNotification($data));
                return "can_request_no_regenerate";
            }
            else if($minutes>60 && $current_requested==getenv("OTP_MAX_REQUESTS")){
                $id = $user->lastRequest->id;
                $otp = rand(100000,999999);
                $update = OtpRequest::find($id)->update(['expires_in'=>date("m/d/Y h:i:s a",strtotime(getenv("OTP_EXPIRES"))),'otp'=>$otp]);
                $data['email'] = $email;
                $data['otp'] = $email;
                Mail::to($email)->send(new OtpNotification($data));
                return "generate_new_otp";
                
            }
            return "";

        }
        return 'does_not_exist';

    }
}
