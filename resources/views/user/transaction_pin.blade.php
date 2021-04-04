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
                    Subcity</h5>
                <hr style="margin-bottom: 60px"/>
                <p>Hello, {{$user->full_name}}</p>
                <p>{{$m}}</p>
                <p>You have successfully created your subpay transaction pin.</p>
                <p>
                    Below is your newly generated transaction pin, if you did not initiate this action, kindly leave us
                    a mail and we will fix it.

                </p>
                <h2>Transaction Pin: {{$user->transaction_pin}}</h2>

                <div style="padding: 10px;background-color:darkgray;color: white;margin-top: 60px;">
                    <p>
                        <small>This is an email from subpay, thank you for your patronage.....if you
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
