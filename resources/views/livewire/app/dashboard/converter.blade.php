<div class="card">
    <div class="card-header">
        <h4><i data-feather="git-pull-request" class="align-self-center header-icon"></i> Currency Converter
            <span wire:target="amount, setFromWallet, setToCurrency"
                  wire:loading class="float-end btn-spinner btn-spinner-soft-danger"></span>
        </h4>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="sendNow" novalidate>
            <div class="row">
                <div class="col-md-4">
                    <x-utils.form.amount-input :key="'amount'" :js="''" :label="'Amount'"/>
                </div>
                <div class="col-md-4">
                    <x-utils.form.select :key="'from_currency'" :js="''" :class="'currency'" :not_required="''">
                        @foreach(user()->noLockWallets as $wallet)
                            <option
                                value="{{$wallet->id}}"
                                label="{{$wallet->currency->code}}">{{$wallet->currency->name}}
                                ({{$wallet->currency->code}}
                                )
                            </option>
                        @endforeach
                    </x-utils.form.select>
                </div>
                <div class="col-md-4">
                    <x-utils.form.select :key="'to_currency'" :js="''" :class="'currency'" :not_required="''">
                        @foreach($currencies ?? [] as $currency)
                            <option
                                value="{{$currency->id}}"
                                label="{{$currency->code}}">{{$currency->name}} ({{$currency->code}})
                            </option>
                        @endforeach
                    </x-utils.form.select>
                </div>
            </div>
            @if(!is_null($rates))
                <div class="row mt-2">
                    <div class="col-6 col-md-3">
                        <small class="font-10">Exchange Rate</small>
                        <h4 class="mt-0"><small
                                class="text-muted font-10 fw-light">{{$selectedToCurrency->code}}
                            </small> {{number_format($rates['exchange_rate'], 2)}} / <small
                                class="text-muted font-10 fw-light">{{$selectedFromCurrency->code}}
                            </small> 1</h4>
                    </div>
                    <div class="col-6 col-md-3 text-end">
                        <small class="font-10">Converted Amount</small>
                        <h4 class="mt-0"><small
                                class="text-muted font-10 fw-light">{{$selectedToCurrency->code}}
                            </small> {{number_format($rates['exchange_amount'], 2)}}</h4>
                    </div>
                    <div class="col-12 col-md-6 text-end @mobile mt-3 @endmobile">
                        <button class="btn btn-success @mobile w-100 @endmobile" wire:target="sendNow"
                                wire:loading.attr="disabled"><span wire:target="sendNow"
                                                                   wire:loading class="btn-spinner"></span> Send Now
                        </button>
                    </div>
                </div>
            @endif
        </form>

    </div>
</div>
@push('styles')
    <style>
        .converter .card {
            box-shadow: rgb(35 55 80 / 30%) 0px 6px 12px !important;
        }

        .header-icon {
            /*width: 18px;*/
            /*height: 18px;*/
            color: #7081b9;
            fill: rgba(112, 129, 185, 0.12);
            margin-right: 6px;
            stroke-width: 1px;
        }
    </style>
@endpush


@push('scripts')
    <script>
        $('#from_currency').on('select2:select', function (e) {
        @this.call('setFromWallet', e.target.value);
        });

        $('#to_currency').on('select2:select', function (e) {
        @this.call('setToCurrency', e.target.value);
        });
    </script>
@endpush
