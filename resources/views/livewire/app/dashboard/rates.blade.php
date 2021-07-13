<div class="card">
    <div class="card-header">
        <h6>Exchange Rates
            <span wire:target="getCurrencyExchange" wire:loading
                  class="float-end btn-spinner btn-spinner-soft-danger"></span>
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="px-3 pt-3">
            <small>
            <span
                class="font-13 flag-icon flag-icon-{{$wallet->currency->code == 'EUR' ? 'eu' : strtolower(substr($wallet->currency->code, 0,2))}}"></span> {{$wallet->currency->name}}
                <span class="fw-light text-muted">({{$wallet->currency->code}})</span>
            </small>
            <div class="float-end"></div>
        </div>
        <hr class="mb-0">
        <div class="pt-2 mb-2" style="height: 275px; overflow-y: scroll; overflow-x: hidden; scrollbar-width: none;">

            <div class="p-2 px-3" wire:init="getCurrencyExchange"
                 wire:poll.300s="getCurrencyExchange">
                @foreach($currencyExchange as $exchange)
                    <div class="font-12">
                    <span
                        class="font-13 flag-icon flag-icon-{{$exchange['to_currency'] == 'EUR' ? 'eu' : strtolower(substr($exchange['to_currency'], 0,2))}}"></span> {{$exchange['to_currency']}}
                        <div class="float-end"><b>{{number_format($exchange['exchange_rate'], 4)}}</b></div>
                        <hr>
                    </div>
                @endforeach

            </div>

        </div>
    </div>
</div>
