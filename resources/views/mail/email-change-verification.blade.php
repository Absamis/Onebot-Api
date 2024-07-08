<x-mail::message>
    <h4>Hello,</h4>
    <p>
        Please use the following verification code to verify your email address:
        <br>
        <strong>Verification Code:</strong> {{ $token }}
    </p>
    <p>
        If you did not request this change, please ignore this email.
    </p>
</x-mail::message>