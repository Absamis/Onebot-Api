<div>
    <h4>Hello {{$data->user->name}},</h4>
    <p>You {{config("app.name")}} wallet has been successfully funded.</p>
    <h5><u>Details</u></h5>
    <ul>
        <li><b>Transaction:</b> {{$data->id}}</li>
        <li><b>Amount:</b> <?=$data->displayAmount?></li>
        <li><b>Payment channel:</b> {{$data->payment_gateway}}</li>
        <li><b>Date:</b> {{$data->transaction_date}}</li>
    </ul>
</div>
