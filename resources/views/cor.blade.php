<div style="">
    <img src="https://subpay.com.ng/static/media/logo.369399ab.svg" style="width:100px;">
    <h2 style="margin-top: -40px">Subpay Communications</h2>
    <hr>
    <div>
        <h3>Hello,' . User::find($this->user_id)->full_name . '</h3>
        <p style="text-align: left">
            Below are your transactions between ' . Carbon::parse($this->from_date)->format('g:i a l jS F Y') . ' to ' .
            Carbon::parse($this->to_date)->format('g:i a l jS F Y') . '
        </p>
    </div>
    <hr>
    <div>
        ' . $this->convert_wallet_transaction_to_html() . '
    </div>
    <div>
        ' . $this->convert_airtime_transaction_to_html() . '
    </div>

    <div>
        ' . $this->convert_cable_transaction_to_html() . '
    </div>

    <div>
        ' . $this->convert_data_transaction_to_html() . '
    </div>

    <div>

        ' . $this->convert_electricity_transaction_to_html() . '
    </div>
    <br/>
    <hr/>
    <div style="text-align: right;font-size: 12px; margin-top: 150px">
        <p>Signed: Subpay Communication</p>
        <p style="margin-top: -10px">Date: ' . Carbon::now()->format('g:i a l jS F Y') . '</p>
    </div>
</div>
