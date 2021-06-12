<div class="card" style="max-width: 540px;">
    <div>
        <div>
            <img style="max-width: 100px;margin: auto;"
                 src="https://subpay.com.ng/static/media/logo.369399ab.svg"
                 class="card-img" alt="...">
        </div>
        <div>
            <div>
                <h5 style="font-size: 30px;font-weight:bolder; text-transform:uppercase;margin-top: 0px;margin-bottom: 0px">
                    Subpay Data Transaction</h5>
                <hr style="margin-bottom: 60px"/>
                <p>Hello, {{$admin->full_name}}</p>
                <p>Data Transaction from: {{$user->full_name}}</p>
                <table>
                    <tbody>
                    <tr>
                        <td>Network:</td>
                        <td>{{$dataTransaction->network}}</td>
                    </tr>
                    <tr>
                        <td>Plan:</td>
                        <td>{{$dataTransaction->data}}</td>
                    </tr>
                    <tr>
                        <td>Amount:</td>
                        <td>{{$dataTransaction->amount}}</td>
                    </tr>

                    <tr>
                        <td>Beneficiary:</td>
                        <td>{{$dataTransaction->beneficiary}}</td>
                    </tr>

                    <tr>
                        <td>From:</td>
                        <td>{{$dataTransaction->method}}</td>
                    </tr>
                    </tbody>
                </table>
                <div style="padding: 10px;background-color:darkgray;color: white;margin-top: 60px;">
                    <p>
                        <small>This is an email from Subpay communication, thank you for your patronage.....if you
                            have any request, complain feel free to leave us a message
                            <a
                                    href="{{env('WEB_URL')}}/contact"
                                    target="_blank"
                                    style="text-decoration: underline; color: #828999; font-family: sans-serif; font-size: 13px; font-weight: 400; line-height: 150%;"
                            >using this link</a
                            >
                            anytime.
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
