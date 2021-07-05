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
                                <h6 class="mt-1">{{$temp->data['account_name']}} <small
                                        class="fw-light text-muted">({{$temp->data['account']}})</small></h6>
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
                            <p class="font-10 mb-2">Enter Your Transaction Pin</p>
                            <x-utils.pin-entry/>
                            <button class="btn btn-success w-100" @if($errors->any()) disabled
                                    @endif wire:target="handleSWSA"
                                    wire:click="handleSWSA"
                                    wire:loading.attr="disabled"><span wire:target="handleSWSA"
                                                                       wire:loading class="btn-spinner"></span>
                                Send
                            </button>
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

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
