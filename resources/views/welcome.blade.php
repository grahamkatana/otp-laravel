@extends('layouts.app')

@section('content')
    <div class="col-lg-6 mx-auto" style="margin-top:20vh;">
        <div class="card">
            <div class="card-body">
                <h5 class="text-center mt-3">Request an OTP</h5>
                @include('includes.messages')
                <div class="mb-3 mt-3">
                    <form action="{{ url('request_otp') }}" method="post">
                        @csrf
                        <label for="exampleFormControlInput1" class="form-label">Your email</label>
                        <input name="email" value="{{ old('email') }}" type="email"
                            class="form-control  @error('email') is-invalid @enderror form-control-lg"
                            id="exampleFormControlInput1" placeholder="name@example.com">
                        @error('email')
                            <span class="text-danger"><b>{{ $message }}</b></span>
                        @enderror
                </div>
                <div class="container">
                    <div class="row mb-3">
                        <button class="btn btn-success btn-lg" name="save" value="save" type="submit">Submit</button>
                    </div>
                </div>
                <div class="container">
                    <div class="row mb-3">
                        <button class="btn btn-dark btn-lg mb-3" value="new" name="new" type="submit">Request new OTP</button>
                    </div>
                </div>
                </form>
            </div>
        </div>

    </div>
@endsection
