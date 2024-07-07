<x-mail::message>
# Introduction
<h4>Hy {{$user->name}},</h4>
<p>Welcome to {{config("app.name")}}. We provide you with easy to access service at the comfort of your livelihood</p>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
