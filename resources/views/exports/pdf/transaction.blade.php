<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    {{--    <title>Invoice - #123</title>--}}

    <style type="text/css">
        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
        }

        * {
            font-family: Verdana, Arial, sans-serif;
        }

        a {
            color: #fff;
            text-decoration: none;
        }

        table {
            font-size: x-small;
            border: 1px solid #dddddd;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .invoice table {
            margin: 15px;
        }

        .invoice h3 {
            margin-left: 15px;
        }

        .information .logo {
            margin: 10px 0;
        }

        .information table {
            padding: 10px;
        }

        .information h3 {
            margin: 8px 0;
        }
    </style>

</head>
<body>

<div class="information">
    <table width="100%" style="border: none">
        <tr>
            <td align="left" style="width: 25%;">


            </td>
            <td align="center">
                <img src="{{public_path('images/default-logo.png')}}" alt="Logo" width="64" class="logo"/>
                <h2 style="margin-bottom: 2px">{{$transaction->merchant->merchant_name}}</h2>
                <small>{{$transaction->merchant->merchant_address}}</small><br/>
                <small>Tel: {{$transaction->merchant->merchant_phone}}</small>
                <table style="margin-top: 15px" width="100%">
                    <tbody>
                    <tr>
                        <td colspan="2" align="center">
                            <h2>{{$transaction->base_currency}} {{number_format($transaction->amount, 2)}}</h2></td>
                    </tr>
                    <tr>
                        <td>Transaction Type</td>
                        <td align="right"><h3>{{switchProducts($transaction->product)}}</h3></td>
                    </tr>
                    <tr>
                        <td>Reference</td>
                        <td align="right"><h3>{{$transaction->reference}}</h3></td>
                    </tr>
                    <tr>
                        <td>Exchange Amount</td>
                        <td align="right">
                            <h3>{{$transaction->exchange_currency}} {{number_format($transaction->exchange_amount, 2)}}</h3>
                        </td>
                    </tr>
                    @if(in_array($transaction->product, ['SB', 'SA']))
                        <tr>
                            <td>Recipient</td>
                            <td align="right"><h3>{{$transaction->account_name}}</h3>{{$transaction->account}}
                                <br/>{{$transaction->institution ?? ''}}</td>
                        </tr>
                    @endif
                    <tr>
                        <td>Date</td>
                        <td align="right">
                            <h3>{{\Carbon\Carbon::parse($transaction->created_at)->format('d F Y h:i A')}}</h3></td>
                    </tr>

                    <tr>
                        <td colspan="2" align="center">
                            <hr style="margin-top: 20px">
                            <h2 style="margin-bottom: 2px">{{$transaction->status}}</h2></td>
                    </tr>

                    <tr>
                        <td colspan="2" align="center">{{$transaction->message}}
                            <hr style="margin-top: 20px">
                        </td>

                    </tr>
                    <tr>
                        <td colspan="2" align="center">Thanks for transacting with us</td>

                    </tr>
                    <tr>
                        <td colspan="2" align="center"><h5>Powered by IMO Rapid Transfer (www.imorapidtransfer.com)</h5>
                        </td>

                    </tr>
                    </tbody>
                </table>
            </td>
            <td align="right" style="width: 25%;">

            </td>
        </tr>

    </table>
</div>
<br/>
</body>
</html>
