<x-slot name="title">
    Transaction - {{$transaction->reference}}
</x-slot>

<div class="page-content">
    <x-utils.actionbar :title="$transaction->reference" :showBack="''" wire:ignore.self>
        <x-slot name="action">
            @if($transaction->status == 'Pending' && in_array($transaction->product, ['SB']))
                <button class="btn btn-purple @mobile btn-sm @endmobile" wire:click="requery" wire:target="requery"
                        wire:loading.attr="disabled"><span
                        wire:target="requery" wire:loading class="btn-spinner"></span> <i wire:loading.class="d-none"
                                                                                         class="fas fa-redo me-2"></i>Requery
                </button>
            @endif
            <span class="d-md-none dropdown">
                        <button class="btn btn-soft-primary btn-sm me-1  dropdown-toggle" id="mobileWalletMenu"
                                data-bs-toggle="dropdown" aria-expanded="false">
                        <i
                            class="fas fa-ellipsis-v"></i>
                    </button>
                  <ul class="dropdown-menu" aria-labelledby="mobileWalletMenu">
                        <li><a class="dropdown-item"  target="_blank" href="{{url('app/report/transactions/receipt/'.$transaction->reference)}}"><i
                                    class="far fa-file-pdf me-2"></i>Download Receipt</a></li>
                  </ul>

                </span>
            <span class="hidden-sm">
            <a class="btn btn-soft-danger" target="_blank" href="{{url('app/report/transactions/receipt/'.$transaction->reference)}}" ><i class="far fa-file-pdf me-2"></i>Download Receipt</a>
            </span>
        </x-slot>
    </x-utils.actionbar>

    <div class="container-fluid app-main">
        <div class="row ">
            <div class="col-lg-4">
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Transaction Details
                            <x-utils.ui.badge :title="$transaction->status" :type="strtolower($transaction->status)"
                                              class="float-end"/>
                        </h5>
                    </div>
                    <div class="card-body">
                        <small class="font-10">Transaction Type</small>
                        <h6 class="mt-0">{{switchProducts($transaction->product)}}</h6>
                        <hr>
                        <small class="font-10">Wallet</small>
                        <div class="d-flex flex-row mt-1">
                                                <span
                                                    class="font-18 me-2 flag-icon flag-icon-{{$transaction->base_currency == 'EUR' ? 'eu' : strtolower(substr($transaction->base_currency, 0,2))}}"></span>
                            <div class=" align-self-center">
                                <h6 class="m-0"><span>{{$transaction->wallet->currency->name}} <span
                                            class="fw-light text-muted">({{$transaction->base_currency}})</span>
                    </span></h6>

                            </div>
                        </div>
                        <hr>
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
                                <small class="font-10">Balance Before</small>
                                <h6 class="mt-0 mb-0">
                                    <small
                                        class="text-muted font-11 fw-light">{{$transaction->base_currency}}
                                    </small> {{number_format($transaction->balance_before,2)}}</h6>
                            </div>

                            <div class="col text-end">
                                <small class="font-10">Balance After</small>
                                <h6 class="mt-0 mb-0">
                                    <small
                                        class="text-muted font-11 fw-light">{{$transaction->base_currency}}
                                    </small> {{number_format($transaction->balance_after,2)}}</h6>

                            </div>
                        </div>
                        <hr>
                        <div class="col text-end">
                            <small class="font-10">Commission Earned</small>
                            <h4 class="mt-0 mb-0">
                                <small
                                    class="text-muted font-11 fw-light">{{$transaction->base_currency}}
                                </small> {{number_format($transaction->commission,3)}} </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Exchange Details</h5>
                    </div>
                    <div class="card-body">
                        <small class="font-10">Exchanged Currency</small>
                        <div class="d-flex flex-row mt-1">
                                                <span
                                                    class="font-18 me-2 flag-icon flag-icon-{{$transaction->exchange_currency == 'EUR' ? 'eu' : strtolower(substr($transaction->exchange_currency, 0,2))}}"></span>
                            <div class=" align-self-center">
                                <h6 class="m-0"><span> {{$transaction->exchange_currency}}</span>
                                </h6>

                            </div>
                        </div>
                        <hr>
                        <div class="col text-end">
                            <small class="font-10">Exchange Rate</small>
                            <h5 class="mt-0 mb-0">
                                <small
                                    class="text-muted font-11 fw-light">{{$transaction->exchange_currency}}
                                </small> {{number_format($transaction->exchange_rate,2)}} / <small
                                    class="text-muted font-11 fw-light">{{$transaction->base_currency}}
                                </small> 1.00</h5>
                        </div>
                        <hr>
                        <div class="col text-end">
                            <small class="font-10">Exchanged Amount</small>
                            <h5 class="mt-0 mb-0">
                                <small
                                    class="text-muted font-11 fw-light">{{$transaction->exchange_currency}}
                                </small> {{number_format($transaction->exchange_amount,2)}}</h5>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @if(in_array($transaction->service, ['WITHDRAWAL']))
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Recipient Details</h5>
                        </div>
                        <div class="card-body">
                            @if(in_array($transaction->product,  ['SA', 'SW']))
                                @if(in_array($transaction->product,  ['SA']))
                                    <small class="font-10">Recipient Account</small>
                                    <h6 class="mt-1 mb-0">{{$transaction->account_name}}</h6>
                                    <small
                                        class="fw-light text-muted">{{$transaction->account}}</small>
                                    <hr>
                                @endif


                                <small class="font-10">Recipient Wallet</small>
                                <div class="d-flex flex-row mt-1">
                                                <span
                                                    class="font-18 me-3 flag-icon flag-icon-{{$transaction->recipient_wallet->currency->code == 'EUR' ? 'eu' : strtolower(substr($transaction->recipient_wallet->currency->code, 0,2))}}"></span>
                                    <div class=" align-self-center">
                                        <h6 class="m-0"><span>{{$transaction->recipient_wallet->currency->name}} <span
                                                    class="fw-light text-muted">({{$transaction->recipient_wallet->currency->code}})</span>
                    </span></h6>

                                    </div>
                                </div>
                            @endif

                            @if(in_array($transaction->product,  ['SB']))
                                <small class="font-10">Recipient Account</small>
                                <h6 class="mt-1 mb-0">{{$transaction->account_name}}</h6>
                                <small
                                    class="fw-light"><span
                                        class="fw-light text-muted">{{$transaction->account}}</span>
                                    - {{$transaction->institution}}
                                </small>



                            @endif
                        </div>
                    </div>
                @endif
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>More Details</h5>
                    </div>
                    <div class="card-body">
                        <small class="font-10">Summary</small>
                        <h6 class="mt-0 fw-normal font-12">{{$transaction->transaction->note}}</h6>
                        <hr>
                        <small class="font-10">IP</small>
                        <h6 class="mt-0">{{$transaction->response['ip'] ?? 'N/A'}}</h6>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="position-absolute top-0 end-0 p-3" style="z-index: 1050">
            <div id="bs_toast" role="alert" aria-live="assertive" aria-atomic="true" class="toast bg-white">
                <div class="toast-header">

                    <span class="me-auto badge {{session('error') ? 'badge-soft-danger' : 'badge-soft-success'}}"
                          style="    padding: .55em .9em;"><span
                            class=" font-14">{{session('error') ? "Error" : "Success"}}</span></span>
                    <small class="text-muted">just now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{session('error_message')}}
                </div>
            </div>
        </div>
    @endif
</div>
@push('scripts')
    <script>
        Livewire.on('showAlert', (e) => {
            initializeToast();
        });
    </script>
@endpush
