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
    public function requestOtp(Request $request)
    {
        $email = $request->input('email');
        $validated = $this->validate($request, [
            'email' => 'required|email'
        ]);

        if ($request->input('save') != null) {
            $requester = Requester::where('email',$email)->first();
            if(!$requester){
                $requester = Requester::create($validated);
                $data = [
                    'otp' => rand(100000, 999999),
                    'requester_id' => $requester->id,
                    'expires_in' => date("m/d/Y h:i:s a", strtotime(getenv("OTP_EXPIRES"))),
                    'current_requests_count' => '1'
                ];
    
                $otp = OtpRequest::create($data);
                $data['email'] = $email;
                Mail::to($email)->send(new OtpNotification($data));
                return redirect("send_otp/${email}");

            }
            else{
                    return redirect('/')->with('error','Your details exists,request a new OTP instead');

            }
        } else {
            $check_otp = $this->checkOTP($email, $request);
            $message = "";
            $type="warning";
            switch ($check_otp) {
                case 'check_inbox':
                    $message = "Please check your email before trying again...";
                    break;

                case 'can_request_no_regenerate':
                    $type="success";
                    $message = "We have resent you your OTP";
                    break;

                case 'please_wait':
                    $message = "Your requests have reached the maximum limit, please wait";
                    break;
                case 'generate_new_otp':
                    $type="success";
                    $message = "We have sent you a new OTP";
                    return redirect("send_otp/${email}");
                    break;
                case 'invalid_otp':
                    $type="error";
                    $message = "Your otp is invalid";
                    break;
                case 'max_reached':
                    $type="error";
                    $message = "You have reached your hourly limit";
                    break;

                default:
                    $type="error";
                    $message = "An error has been encountered, try again later";
                    break;
            }
            return redirect('/')->with($type,$message);
        }
        


    }

    public function sendOtp(Request $request,$email)
    {
        $request->validate([
            'otp' => ['required', 'numeric', new Checkotp],
        ]);
        $check = OtpRequest::where('otp',$request->input('otp'))->first();
        $check->update(["is_valid"=>false]);
        return redirect('/')->with('success','You have verified your OTP, thanks');
    }

    public function otp(Request $request, $email)
    {
        return view('enterotp');
    }
    // 
    public function checkOTP($email)
    {
        $user = Requester::where('email', $email)->first();
        if ($user) {
            $current_time = strtotime(date("m/d/Y h:i:s a"));
            $record_time = strtotime($user->lastRequest->expires_in);
            $time_span = $current_time - $record_time;
            $minutes = $time_span / 60;
            $current_requested = $user->lastRequest->current_requests_count;
            $is_valid = $user->lastRequest->is_valid;

            if ($minutes < 60&&!$is_valid) {
                return $user->lastRequest->current_requests_count==3?"max_reached":"invalid_otp";
            } else if ($minutes < 60 && $current_requested == getenv("OTP_MAX_REQUESTS")) {
                return "please_wait";
            } else if ($minutes < getenv("OTP_MINUTES")[1] && $current_requested < getenv("OTP_MAX_REQUESTS") && $is_valid) {
                return "check_inbox";
            } else if ($minutes >= getenv("OTP_MINUTES")[1] && $current_requested < getenv("OTP_MAX_REQUESTS") && $is_valid) {
                $data = [
                    'otp' => $user->lastRequest->otp,
                    'requester_id' => $user->id,
                ];
                $id = $user->lastRequest->id;
                $update = OtpRequest::find($id)->update(['expires_in' => date("m/d/Y h:i:s a", strtotime(getenv("OTP_EXPIRES"))), 'otp' => $user->lastRequest->otp]);
                $data['email'] = $email;
                Mail::to($email)->send(new OtpNotification($data));
                return "can_request_no_regenerate";
            } else if ($minutes > 60 && $current_requested == getenv("OTP_MAX_REQUESTS")) {
                $id = $user->lastRequest->id;
                $otp = rand(100000, 999999);
                $update = OtpRequest::find($id)->update(['expires_in' => date("m/d/Y h:i:s a", strtotime(getenv("OTP_EXPIRES"))), 'otp' => $otp]);
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
