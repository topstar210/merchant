<x-email>
    <style>
        hr{
           border: 1px solid #b1b2b354 !important
        }
    </style>
    Dear {{$trans->user->full_name}}<br/><br/>
    A transaction occurred on your IMO Rapid Transfer account. The details of this transaction are shown below:<br/><br/>

    <table width="85%" align="center" style="border: 1px solid #ddd; padding: 20px; border-radius: 10px">
        <tbody>
        <tr>
            <td><small>Amount</small></td>
            <td align="right"><b>{{$trans->base_currency}} {{number_format($trans->amount, 2)}}</b></td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
        <tr>
            <td><small>Transaction Type</small></td>
            <td align="right">{{switchProducts($trans->product)}}</td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
        <tr>
            <td><small>Reference</small></td>
            <td align="right">{{$trans->reference}}</td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
        <tr>
            <td><small>Exchange Amount</small></td>
            <td align="right">{{$trans->exchange_currency}} {{number_format($trans->exchange_amount, 2)}}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
        <tr>
            <td><small>Current Balance</small></td>
            <td align="right">{{$trans->base_currency}} {{number_format($trans->balance_after, 2)}}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
        @if(in_array($trans->product, ['SB', 'SA']))
            <tr>
                <td><small>Recipient</small></td>
                <td align="right"><b>{{$trans->account_name}}</b><br/>{{$trans->account}}
                    <br/>{{$trans->institution ?? ''}}</td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr>
                </td>
            </tr>
        @endif
        <tr>
            <td><small>Date</small></td>
            <td align="right">{{\Carbon\Carbon::parse($trans->created_at)->format('d F Y h:i A')}}</td>
        </tr>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
              {{$trans->status}}</h2></td>
        </tr>

        <tr>
            <td colspan="2" align="center">{{$trans->transaction->note}}
            </td>

        </tr>
        </tbody>
    </table>
<br/>
   <p align="center">Thank you for choosing IMO Rapid Transfer</p><br/>
</x-email>
