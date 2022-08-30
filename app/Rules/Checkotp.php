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
        $check_hours = date("m/d/Y h:i:s a")-$check->expires_in;
        dd($check_hours);
        // if($check_hours>1){

        // }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The otp is not valid,please request another.';
    }
}
