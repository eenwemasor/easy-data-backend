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
                    Subpay Quick Buy Transaction</h5>
                <hr style="margin-bottom: 60px"/>
                <p>Hello, {{$admin->full_name}}</p>
                <p>Cable Transaction from: {{$user->full_name}}</p>
                <table>
                    <tbody>
                    <tr>
                        <td>Reference:</td>
                        <td>{{$cableTransaction->reference}}</td>
                    </tr>
                    <tr>
                        <td>Decoder:</td>
                        <td>{{$cableTransaction->decoder}}</td>
                    </tr>
                    <tr>
                        <td>Decoder Number:</td>
                        <td>{{$cableTransaction->decoder_number}}</td>
                    </tr>
                    <tr>
                        <td>Beneficiary Name: </td>
                        <td>{{$cableTransaction->beneficiary_name}}</td>
                    </tr>
                    <tr>
                        <td>Plan: </td>
                        <td>{{$cableTransaction->plan}}</td>
                    </tr>
                    <tr>
                        <td>Amount:</td>
                        <td>{{$cableTransaction->amount}}</td>
                    </tr>
                    <tr>
                        <td>From:</td>
                        <td>{{$cableTransaction->method}}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
</div>
