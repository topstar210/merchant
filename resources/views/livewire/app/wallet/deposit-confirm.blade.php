<x-slot name="title">
    Confirm Deposit
</x-slot>

<div class="page-content">
    <x-utils.actionbar :title="'Confirm Deposit'" :showBack="'true'" wire:ignore/>
    <div class="container-fluid app-main">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-4 mx-auto">
                <div class="card mt-4">
                    <div class="card-header">
                        <h6><i class="ti-info-alt text-info"></i> Confirm Deposit Details to Continue</h6>
                    </div>
                    <div class="card-body">
                        <div wire:ignore>

                            <div class="d-flex flex-row">
                                <div class="col">
                                    <small class="font-10">Amount</small>
                                    <h4 class="mt-0"><small
                                            class="text-muted font-10 fw-light">{{$temp->data['from_currency']}}
                                        </small> {{number_format($temp->data['amount'], 2)}}</h4>
                                </div>

                                <div class="col text-end">
                                    <small class="font-10">Charge</small>
                                    <h6 class="mt-0"><small
                                            class="text-muted font-10 fw-light">{{$temp->data['from_currency']}}
                                        </small> {{number_format($temp->data['charge'], 2)}}</h6>


                                </div>
                            </div>
                            <hr>

                            <div class="text-end">
                                <small class="font-10">Total Amount</small>
                                <h3 class="mt-0"><small
                                        class="text-muted font-10 fw-light">{{$temp->data['from_currency']}}
                                    </small> {{number_format($temp->data['total'], 2)}}</h3>
                            </div>

                            @if($temp->data['converted'])
                                <hr>
                                <div class="py-2 text-end">
                                    <div class="text-start">
                                        <small><b>You will be charged in {{$temp->data['to_currency'] }}</b></small>
                                    </div>
                                    <h4 class="mb-1">
                                        <strong><small
                                                class="text-muted font-13 fw-light">{{ $temp->data['to_currency'] }}
                                            </small> {{ number_format($temp->data['exchange_amount'],2) }}</strong>
                                    </h4>
                                    <small class="mb-0 text-muted font-11">At the rate
                                        of <b>{{ $temp->data['to_currency'] }} {{$temp->data['exchange_rate']}}</b>
                                        <i class="fas fa-exchange-alt text-danger"></i>
                                        <b>{{$temp->data['from_currency'] }}
                                            1</b></small>
                                </div>
                            @endif
                            <hr>
                            <img src="{{asset('images/'.switchRouteName($temp->route->payment_method->name)[1])}}"
                                 class="img-fluid">
                            <hr>
                        </div>

                        @if($temp->route->payment_method->name === 'Payswitch')
                            <button class="btn btn-success w-100" onclick="startPayswitch()">Deposit</button>

                            <form>
                                <a class="ttlr_inline"
                                   data-APIKey="{{config('env.ps_api_key')}}"
                                   data-transid="{{$temp->reference}}"
                                   data-amount="{{$temp->data['exchange_amount']}}"
                                   {{--                                   data-customer_email="{{user()->email}}"--}}
                                   data-customer_email=""
                                   data-currency="{{$temp->data['to_currency']}}"
                                   data-redirect_url="{{url('app/deposit/webhook/Payswitch/'.$temp->reference)}}"
                                   data-pay_button_text="Deposit"
                                   data-custom_description="Imo Rapid Transfer Deposit"
                                   data-payment_method="both">
                                </a>
                            </form>
                            @push('scripts')
                                <script type="text/javascript"
                                        src="https://test.theteller.net/checkout/resource/api/inline/theteller_inline.js"></script>
                                <script>
                                    function startPayswitch() {
                                        $(".ttlr_inline :button").click(); // Click on the checkbox
                                    }
                                </script>
                            @endpush
                        @elseif($temp->route->payment_method->name === 'Flutterwave')
                            <button class="btn btn-success w-100" onclick="startFlutterwave()">Deposit</button>
                            <form style="display: none" method="POST"
                                  action="{{url('app/deposit/webhook/Flutterwave/'.$temp->reference)}}" id="flw_form">
                                @csrf
                                <input name="response" id="flw_response">
                            </form>

                            @push('scripts')

                                <script
                                    src="https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>

                                <script>
                                    function startFlutterwave() {
                                        var x = getpaidSetup({
                                            PBFPubKey: '{{config('env.fw_pub_key')}}',
                                            customer_email: '{{user()->email}}',
                                            amount: Number('{{$temp->data['exchange_amount']}}'),
                                            customer_phone: '{{user()->phone}}',
                                            currency: '{{$temp->data['to_currency']}}',
                                            txref: '{{$temp->reference}}',
                                            onclose: function () {
                                                // alert('Deposit Payment Cancelled');
                                            },
                                            callback: function (response) {
                                                if (response.tx.chargeResponseCode === '00') {
                                                    $('#flw_response').val(JSON.stringify(response.tx));
                                                    $('#flw_form').submit();
                                                }

                                                x.close();
                                            }
                                        });
                                    }

                                </script>
                            @endpush
                        @elseif($temp->route->payment_method->name === 'Orchard')

                            @if(is_null($orc_error))
                                <button class="btn btn-success w-100" wire:target="startOrchard"
                                        wire:click="startOrchard"
                                        wire:loading.attr="disabled"><span wire:target="startOrchard"
                                                                           wire:loading class="btn-spinner"></span>
                                    Deposit
                                </button>
                            @else
                                <div class="alert icon-custom-alert alert-danger b-round-sm fade show mt-2"
                                     role="alert">
                                    <i class="ti-info-alt alert-icon"></i>
                                    <div class="alert-text font-12">
                                        {{$orc_error}}
                                    </div>

                                </div>


                            @endif


                        @else

                            <div class="alert icon-custom-alert alert-danger b-round-sm fade show mt-2"
                                 role="alert">
                                <i class="ti-info-alt alert-icon"></i>
                                <div class="alert-text font-12">
                                    This Payment Option is currently not supported.
                                </div>

                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
