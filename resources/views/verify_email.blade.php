<!DOCTYPE html>
<html>

<head>
    <title></title>
</head>

<body>
<div style="width: 60%;
        margin: 40px auto;
        text-align: center;
        background-color: azure;
        padding: 40px;">
    <div>
        <img style="max-width: 100px;margin: auto;"
             src="https://subpay.com.ng/static/media/logo.369399ab.svg"
             class="card-img" alt="...">
    </div>
    <h4 class="message-header">Email Verification
    </h4>
    <div style="
        padding:10px 40px;
        margin-bottom: 10px;
        font-size: 12px;
        color: black;
        text-decoration: none;
        text-transform: uppercase;">

        <h4>Hello, {{$name}}</h4>
        <p>Please click the below to verify your email address.</p>
        <br>
        <a style="
            background-color:black;
            padding: 10px;
            margin: auto;
            width: 150px;
            color: white;
            display: block;
            text-decoration: none;
            text-transform: uppercase;" href="{{$url}}">Verify Email Address</a>
        <br>

        <p>Button not working? try pasting this link into your browser</p>
        <small style="background-color: gainsboro; padding: 10px;display: block;text-transform: lowercase">{{$url}}</small>
        <br><br>
        <p>if you did not create an account with Subpay Communication, no further actions is required.</p>
        <p>Regards</p>
        <a href="{{env('WEB_URL')}}/contact">Subpay Communication.</a>
    </div>
</div>
</body>