@component('mail::message')
# Verify Email

Dear {{$name}}

@component('mail::button', ['url' => $link])
Verify your email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
