<html>
    <body>
        <h4>Hi,</h4>
        <p>You have been invited to join {{$account->name}} on {{config("app.name")}}</p>
        <div style="text-align: center;">
            <a href="{{$rdr_url}}/{{$invite->token}}">Accept Invitation</a>
        </div>
        <small><b>Note:</b> Invitation link will expire after {{config("services.utils.invitiation_active_days")}}days</small>
    </body>
</html>
