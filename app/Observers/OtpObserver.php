<?php

namespace App\Observers;

use App\Models\Request;

class OtpObserver
{
    /**
     * Handle the Request "created" event.
     *
     * @param  \App\Models\Request  $request
     * @return void
     */
    public function created(Request $request)
    {
        //
    }

    /**
     * Handle the Request "updated" event.
     *
     * @param  \App\Models\Request  $request
     * @return void
     */
    public function updated(Request $request)
    {
    
    }

    public function updating(Request $request)
    {
        $current = $request->current_requests_count+1;
        $request->current_requests_count=$current;
        $request->expires_in =date("m/d/Y h:i:s a",strtotime(getenv("OTP_EXPIRES")));
        if($request->current_requests_count==getenv("OTP_MAX_REQUESTS")){
            $request->is_valid=false;
        }
    }

    /**
     * Handle the Request "deleted" event.
     *
     * @param  \App\Models\Request  $request
     * @return void
     */
    public function deleted(Request $request)
    {
        //
       
    }

    /**
     * Handle the Request "restored" event.
     *
     * @param  \App\Models\Request  $request
     * @return void
     */
    public function restored(Request $request)
    {
        //
    }

    /**
     * Handle the Request "force deleted" event.
     *
     * @param  \App\Models\Request  $request
     * @return void
     */
    public function forceDeleted(Request $request)
    {
        //
    }
}
