@extends('layouts.app')

@section('content')
<div class="col-lg-6 mx-auto" style="margin-top:20vh;">
    <div class="card">
        <div class="card-body">
            <h5 class="text-center mt-3">Request an OTP</h5>
            <div class="mb-3 mt-3">
           <form action="{{url('request_otp')}}" method="post">
            @csrf
            <label for="exampleFormControlInput1" class="form-label">Your email</label>
            <input type="email" class="form-control  @error('email') is-invalid @enderror form-control-lg" id="exampleFormControlInput1" placeholder="name@example.com">
            @error('email')
            <span class="text-danger"><b>Error</b></span>
            @enderror
          </div>
          <input class="btn btn-success btn-lg mb-3" type="submit" value="Submit">
           </form>
        </div>
    </div>

</div>
    
@endsection