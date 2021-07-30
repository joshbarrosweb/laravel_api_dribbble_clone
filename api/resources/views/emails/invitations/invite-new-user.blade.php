@component('mail::message')
# Hello

You have been invited to join the team
**{{ $invitation->team->name }}**.
Because you are not yet signed up to the platform, please
[Register for free]({{ $url }}), then you can accept of reject the invitation in your team management console.


@component('mail::button', ['url' => $url])
Register for free
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
