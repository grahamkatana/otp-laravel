@extends('layouts.app')

@section('content')
<div class="col-lg-6 mx-auto" style="margin-top:20vh;">
    <div class="card">
        <div class="card-body">
            <h5 class="text-center mt-3">Activate</h5>
            <div class="mb-3 mt-3">
           <form action="{{url('send_otp')}}" method="post">
            @csrf
            <label for="exampleFormControlInput1" class="form-label">OTP</label>
            <input name="otp" min="100000" max="999999" value="{{old('otp')}}" type="number" class="form-control  @error('otp') is-invalid @enderror form-control-lg" id="exampleFormControlInput1" placeholder="######">
            @error('otp')
            <span class="text-danger"><b>{{$message}}</b></span>
            @enderror
          </div>
         <div class="container">
            <div class="row">
                <input class="btn btn-success btn-lg mb-3" type="submit" value="Submit">
                <a href="{{url('/')}}">Request another</a>
             </div>
         </div>
           </form>
        </div>
    </div>

</div>
    
@endsection