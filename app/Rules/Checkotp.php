<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Request;

class Checkotp implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //@ value = otp
        $check = Request::where('otp',$value)->first();
        if(!$check){
            return false;
        }
        $current_time = strtotime(date("m/d/Y h:i:s a"));
        $record_time = strtotime($check->expires_in);
        $time_span = $current_time - $record_time;
        $minutes = $time_span / 60;
        if($minutes > getenv("OTP_MINUTES")[1]){
            return false;
        }
        if($check->current_requests_count==getenv("OTP_MAX_REQUESTS")){
            return false;
        }
        if(!$check->is_valid){
            return false;
        }
        return true;
      

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The otp is not valid,please request another one.';
    }
}
