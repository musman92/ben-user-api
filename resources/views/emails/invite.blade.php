@component('mail::message')
# Invite for Registeration

You are invited to get Registered.
Your invite code is {{$invitation_token}}

@component('mail::button', ['url' => $url])
Click to get Register
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent