<x-mail::message>

<h4>Hy {{$user->name}},</h4>
<p>Your {{$type}} was recently changed.</p>
<ul class="">
    <li><b>Device:</b> {{$user->last_login_location}}</li>
    <li><b>IP address:</b> {{$user->last_login_ip}}</li>
    <li><b>Date:</b> {{$user->updated_at}}</li>
</ul>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
