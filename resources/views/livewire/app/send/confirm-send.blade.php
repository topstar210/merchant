<x-slot name="title">
    Confirm Send
</x-slot>
<div class="page-content">
    <x-utils.actionbar :title="'Confirm Send'" :showBack="''"/>

    <div class="container-fluid app-main">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-4 mx-auto">
                <div class="card mt-4">
                    <div class="card-header">
                        <h6><i class="ti-info-alt text-info"></i> Confirm Send Details
                        </h6>
                    </div>
                    <div class="card-body">
                        @if(in_array($temp->data['service'], ['SW', 'SA', 'SB']))
                            <small class="font-10">Send From</small>
                            <div class="d-flex flex-row mt-1">
                                                <span
                                                    class="font-18 me-3 flag-icon flag-icon-{{$wallet->currency->code == 'EUR' ? 'eu' : strtolower(substr($wallet->currency->code, 0,2))}}"></span>
                                <div class=" align-self-center">
                                    <h6 class="m-0"><span>{{$wallet->currency->name}} <span
                                                class="fw-light text-muted">({{$wallet->currency->code}})</span>
                    </span></h6>
                                    <small class="text-primary font-10">Available
                                        Balance: {{$wallet->currency->code}} {{number_format($wallet->balance, 2)}}</small>

                                </div>
                            </div>
                            <hr>
                            @if(in_array($temp->data['service'], ['SW', 'SA']))

                                @if($temp->data['service'] == 'SA')
                                    <small class="font-10">Recipient Account</small>
                                    <h6 class="mt-1 mb-0">{{$temp->data['account_name']}}</h6>
                                    <small
                                        class="fw-light text-muted">{{$temp->data['account']}}</small>
                                    <hr>

                                @endif

                                <small class="font-10">Recipient Wallet</small>
                                <div class="d-flex flex-row mt-1">
                                                <span
                                                    class="font-18 me-3 flag-icon flag-icon-{{$temp->recipient_wallet->currency->code == 'EUR' ? 'eu' : strtolower(substr($temp->recipient_wallet->currency->code, 0,2))}}"></span>
                                    <div class=" align-self-center">
                                        <h6 class="m-0"><span>{{$temp->recipient_wallet->currency->name}} <span
                                                    class="fw-light text-muted">({{$temp->recipient_wallet->currency->code}})</span>
                    </span></h6>

                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex flex-row">
                                    <div class="col">
                                        <small class="font-10">Amount</small>
                                        <h4 class="mt-0"><small
                                                class="text-muted font-10 fw-light">{{$temp->data['from_currency']}}
                                            </small> {{number_format($temp->data['amount'], 2)}}</h4>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <i class="fas fa-exchange-alt text-danger"></i>
                                    </div>
                                    <div class="col text-end">
                                        <small class="font-10">Recipient Receives</small>
                                        <h4 class="mt-0"><small
                                                class="text-muted font-10 fw-light">{{$temp->data['to_currency']}}
                                            </small> {{number_format($temp->data['exchange_amount'], 2)}}</h4>

                                    </div>


                                </div>
                                <div class=" text-center">
                                    <small class="font-10 text-muted">At the rate
                                        of <b>{{ $temp->data['to_currency'] }} {{$temp->data['exchange_rate']}}</b>
                                        to
                                        <b>{{$temp->data['from_currency'] }}
                                            1</b> </small>
                                </div>
                                <hr>
                            @endif

                            @if($temp->data['service'] == "SB")
                                <small class="font-10">Recipient Account</small>
                                <h6 class="mt-1 mb-0">{{$temp->data['account_name']}}</h6>
                                <small
                                    class="fw-light"><span
                                        class="fw-light text-muted">{{$temp->data['account']}}</span>
                                    - {{$temp->data['bank']['Name'] ?? $temp->data['bank']['bankName']}}
                                </small>

                                <hr>
                                <div class="d-flex flex-row">
                                    <div class="col">
                                        <small class="font-10">Amount</small>
                                        <h4 class="mt-0"><small
                                                class="text-muted font-10 fw-light">{{$temp->data['from_currency']}}
                                            </small> {{number_format($temp->data['amount'], 2)}}</h4>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <i class="fas fa-exchange-alt text-danger"></i>
                                    </div>
                                    <div class="col text-end">
                                        <small class="font-10">Recipient Receives</small>
                                        <h4 class="mt-0"><small
                                                class="text-muted font-10 fw-light">{{$temp->data['to_currency']}}
                                            </small> {{number_format($temp->data['exchange_amount'], 2)}}</h4>

                                    </div>


                                </div>
                                <div class=" text-center">
                                    <small class="font-10 text-muted">At the rate
                                        of <b>{{ $temp->data['to_currency'] }} {{$temp->data['exchange_rate']}}</b>
                                        to
                                        <b>{{$temp->data['from_currency'] }}
                                            1</b> </small>
                                </div>
                                <hr>

                                <div class="d-flex flex-row">
                                    <div class="col-3 me-2">
                                        <small class="font-10">Charge</small>
                                        <h6 class="mt-0"><small
                                                class="text-muted font-10 fw-light">{{$temp->data['from_currency']}}
                                            </small> {{number_format($temp->data['charge'], 2)}}</h6>
                                    </div>
                                    <div class="col-3">
                                        <small class="font-10">Commission</small>
                                        <h6 class="mt-0"><small
                                                class="text-muted font-10 fw-light">{{$temp->data['from_currency']}}
                                            </small> {{number_format($temp->data['commission'], 2)}}</h6>
                                    </div>

                                    <div class="col text-end">
                                        <small class="font-10">Total</small>
                                        <h3 class="mt-0 mb-0"><small
                                                class="text-muted font-10 fw-light">{{$temp->data['from_currency']}}
                                            </small> {{number_format($temp->data['total'], 2)}}</h3>

                                    </div>


                                </div>
                                <hr>
                            @endif
                        @endif

                        @if($temp->data['service'] == "CW")

                            <small class="font-10">Commission Wallet</small>
                            <div class="d-flex flex-row mt-1">
                                                <span
                                                    class="font-18 me-3 flag-icon flag-icon-{{$wallet->currency->code == 'EUR' ? 'eu' : strtolower(substr($wallet->currency->code, 0,2))}}"></span>
                                <div class=" align-self-center">
                                    <h6 class="m-0"><span>{{$wallet->currency->name}} <span
                                                class="fw-light text-muted">({{$wallet->currency->code}})</span>
                    </span></h6>
                                    <small class="text-primary font-10">Available
                                        Commission
                                        Balance: {{$wallet->currency->code}} {{number_format($wallet->commission, 2)}}</small>

                                </div>
                            </div>
                            <hr>
                            <div class="d-flex flex-row">
                                <div class="col text-end">
                                    <small class="font-10">Amount</small>
                                    <h4 class="mt-0"><small
                                            class="text-muted font-10 fw-light">{{$temp->data['from_currency']}}
                                        </small> {{number_format($temp->data['amount'], 2)}}</h4>
                                </div>
                            </div>
                            <hr>
                        @endif

                        <p class="font-10 mb-2">Enter Your Transaction Pin</p>
                        <form wire:submit.prevent="handleSend">
                            <x-utils.pin-entry/>
                            <button class="btn btn-success w-100" type="submit" @if($errors->any()) disabled
                                    @endif wire:target="handleSend"
                                    wire:loading.attr="disabled"><span wire:target="handleSend"
                                                                       wire:loading class="btn-spinner"></span>
                                Send
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $('#pincode').pincodeInput({
            inputs: 4,
            placeholders: "- - - - - -",
            hidedigits: true,
            change: function (input, value, inputnumber) {

            },
            complete: function (value, e, errorElement) {
            @this.set('pin', value);
            }
        });
    </script>
@endpush
