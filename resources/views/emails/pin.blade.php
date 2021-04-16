@component('mail::message')
# Registeration Pin

Your pin is {{$pin}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent