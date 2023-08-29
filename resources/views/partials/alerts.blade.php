@if(session('registered'))
    <div class="alert alert-success">
        @lang('auth.your registration was successful')
    </div>
@endif
@if(session('failed'))
    <div class="alert alert-danger">
    </div>
@endif
