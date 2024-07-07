<x-mail::message>
# Introduction

<h5>Hy {{$user->name}},</h5>
<p>There's new login to your account</p>
<ul class="">
    <li><b>Device:</b> {{$user->last_login_location}}</li>
    <li><b>IP address:</b> {{$user->last_login_ip}}</li>
    <li><b>Date:</b> {{$user->updated_at}}</li>
</ul>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
