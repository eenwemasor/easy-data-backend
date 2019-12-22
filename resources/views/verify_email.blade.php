<html>

<head>
</head>

<body>
<div class="body">
    <div>
        <h4>Hello, {{$name}}</h4>
        <p>Please click the below to verify your email address.</p>
        <br>
        <br><br>
        <a class = "verify_btn" href={{$url}}>Verify Email Address</a>
        <br>
        <br><br>
        <p>if you did not create an with Gtserviz communication, no further actions is required.</p>
        <p>Regards</p>
        <a href="https://gtserviz.com">Gtserviz Communication.</a>
    </div>
</div>
</body>
<style>
    .body {
        width: 60%;
        margin: 40px auto;
        text-align: center;
        background-color: azure;
        padding: 40px;
    }

    h4 {
        font-size: 2rem;

    }
    .verify_btn{
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

</html>