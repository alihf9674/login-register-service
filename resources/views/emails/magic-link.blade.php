@component('mail::message')
# Login with magic link

@component('mail::button', ['url' => $link])
login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
