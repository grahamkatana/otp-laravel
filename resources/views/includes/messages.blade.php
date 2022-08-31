@if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible alert-alt fade show">
        <strong>Error!</strong> {{ ucwords(session()->get('error')) }}.
    </div>
@endif
@if (session()->has('success'))
    <div class="alert alert-success alert-dismissible alert-alt fade show">
       
        <strong>Success!</strong> {{ ucwords(session()->get('success')) }}.
    </div>
@endif

@if (session()->has('warning'))
    <div class="alert alert-warning alert-dismissible alert-alt fade show">
       
        <strong>Warning!</strong> {{ ucwords(session()->get('warning')) }}.
    </div>
@endif
