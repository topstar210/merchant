<x-slot name="title">
    Transaction Processing
</x-slot>

<div class="page-content">
    <x-utils.actionbar :title="'Reference: '.$transaction->reference" wire:ignore/>
    <div class="container-fluid app-main">
        <div class="row d-flex justify-content-center">
            @if($count == 20 && $transaction->status == 'Pending')
                <div class="col-lg-4 mx-auto">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h3><i class="fas fa-stop-circle text-warning"></i> Pending</h3>
                        </div>
                        <div class="card-body pt-2">
                            <h4>Transaction Process in Progress</h4>
                            <p class="text-muted font-11">This transaction is still in progress, you will be notified as
                                soon as the transaction is completed.</p>
                            <p class="text-muted font-11">You can also manually query this transaction by viewing the
                                transaction and clicking <b>"Requery"</b></p>

                            <hr>
                            <a class="btn btn-soft-primary" href="{{url('/app/report/transactions/view/'.$transaction->reference)}}">View Transaction</a>
                        </div>
                    </div>
                </div>

            @else
                @if($transaction->status == 'Pending')
                    <div class="col-lg-4 mx-auto" wire:poll.3s="checkTransaction">
                        <div class="card mt-4">
                            <div class="card-header">
                                <h6><i class="ti-info-alt text-danger"></i> Do not refresh this page</h6>
                            </div>
                            <div class="card-body text-center pb-4">
                                <div class="loading-spinner mt-4 mb-5"></div>
                                <h4>Processing Transaction</h4>
                                <p class="text-muted font-11">Please wait, we are confirming your transaction. Kindly
                                    stay
                                    on this page until
                                    this process is completed</p>
                            </div>
                        </div>
                    </div>

                @else
                    <div class="col-lg-4 mx-auto">
                        <div class="card mt-4">
                            <div class="card-header">
                                <h3>@if($transaction->status == 'Success')
                                        <i class="fas fa-check-circle text-success"></i>
                                    @elseif($transaction->status == 'Failed')
                                        <i class="fas fa-times-circle text-danger"></i>
                                    @else
                                        <i class="fas fa-stop-circle text-warning"></i>
                                    @endif
                                    {{$transaction->status}}</h3>
                            </div>
                            <div class="card-body">


                                <div class="d-flex flex-row">
                                    <div class="col">
                                        <small class="font-10">Amount</small>
                                        <h4 class="mt-0"><small
                                                class="text-muted font-11 fw-light">{{$transaction->base_currency}}
                                            </small> {{number_format($transaction->amount, 2)}}</h4>
                                    </div>

                                    <div class="col text-end">
                                        <small class="font-10">Charge</small>
                                        <h4 class="mt-0">

                                            <small
                                                class="text-muted font-11 fw-light">{{$transaction->base_currency}}
                                            </small> {{number_format($transaction->charge,2)}}</h4>

                                    </div>
                                </div>
                                <hr>

                                <div class="d-flex flex-row">
                                    <div class="col">
                                        <small class="font-10">Transaction Reference</small>
                                        <h6 class="mt-1">{{$transaction->reference}}</h6>
                                    </div>

                                    <div class="col text-end">
                                        <small class="font-10">Total</small>
                                        <h3 class="mt-0 mb-0">

                                            <small
                                                class="text-muted font-11 fw-light">{{$transaction->base_currency}}
                                            </small> {{number_format($transaction->total_amount,2)}}</h3>

                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex flex-row">
                                    <div class="col">
                                        <small class="font-10">Transaction Type</small>
                                        <h6 class="mt-1 mb-0">{{switchProducts($transaction->product)}}</h6>
                                    </div>

                                    <div class="col text-end">
                                        <small class="font-10">Wallet Balance</small>
                                        <h4 class="mt-0 mb-0">

                                            <small
                                                class="text-muted font-11 fw-light">{{$transaction->wallet->currency->code}}
                                            </small> {{number_format($transaction->wallet->balance,2)}}</h4>

                                    </div>
                                </div>
                                <hr>
                                <small class="font-10">Summary</small>
                                <h6 class="mt-1 font-12 fw-light">{{$transaction->transaction->note}}</h6>
                                <hr>

                                <a class="btn btn-soft-danger"  target="_blank" href="{{url('app/report/transactions/receipt/'.$transaction->reference)}}"><i class="far fa-file-pdf"></i> Download Receipt</a>
                            </div>
                        </div>
                    </div>


                @endif
            @endif
        </div>
    </div>

</div>
