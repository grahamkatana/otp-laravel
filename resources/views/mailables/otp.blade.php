@component('mail::message')
# OTP

Hello, we have received an OTP request from your email ({{$data['email']}}). Your OTP is {{$data['otp']}}.



Thanks,<br>
{{ config('app.name') }}
@endcomponent
