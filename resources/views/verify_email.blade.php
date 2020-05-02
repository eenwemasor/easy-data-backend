<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<div class="message-body">
    <h4 class="message-header">Email Verification
    </h4>
    <div class="message-body-inner">

        <h4>Hello, {{$name}}</h4>
        <p>Please click the below to verify your email address.</p>
        <br>
        <br><br>
        <a class="verify-btn" href="{{$url}}">Verify Email Address</a>
        <br>
        <br><br>
        <p>if you did not create account an with Subpay communication, no further actions is required.</p>
        <p>Regards</p>
        <a  href="{{env('WEB_URL')}}/contact">Subpay Communication.</a>
</body>
<style type="text/css">
    .message-body-inner a{
        background-color: orangered;
        padding:10px 40px;
        margin-bottom: 10px;
        font-size: 12px;
        color: white;
        text-decoration: none;
        text-transform: uppercase;
    }
    body {
        width: 60%;
        margin: 40px auto;
        text-align: center;
        background-color: azure;
        padding: 40px;
    }

    h4 {
        font-size: 2rem;

    }
    .verify-btn{
        width: 30px;
        background-color:green;
        padding: 20px;
        margin: 20px;
        color: white;
        text-decoration: none;
        text-transform: uppercase;
        margin-top: 50px;

    }
</style>
