@component('mail::message')
# Forgot Password

@component('mail::button', ['url' => $link])
Reset your password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
