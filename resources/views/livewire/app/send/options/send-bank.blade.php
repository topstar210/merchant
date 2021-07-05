<div class="accordion-item">
    <h3 class="accordion-header m-0" id="headingSBM" x-data="{ lock: @entangle('locked') }">
        <button class="accordion-button fw-normal collapsed" type="button" x-bind:disabled="lock" wire:ignore
                data-bs-toggle="collapse" data-bs-target="#collapseSBM"
                aria-expanded="false" aria-controls="collapseSBM">
            <i class="fas fa-archive text-warning me-2 font-16"></i> Send to Bank /
            Mobile Wallet
        </button>
    </h3>
    <div id="collapseSBM" class="accordion-collapse collapse" wire:ignore.self
         aria-labelledby="headingSBM" data-bs-parent="#sendAccordion" style="">
        <div class="accordion-body">
            <h5 class="mt-0"><small class="text-muted">Send money to Banks or a Mobile Money wallet</small></h5>
            <hr>
            <form wire:submit.prevent="continueSendBank">
                <x-utils.form.amount-input :key="'amount'" :js="''" :label="'Amount to Send'"/>

                <x-utils.form.select :key="'send_currency'" :js="''" :class="'currency'">
                    @foreach($currencies ?? [] as $currency)
                        <option
                            value="{{$currency->id}}"
                            label="{{$currency->code}}">{{$currency->name}}</option>
                    @endforeach
                </x-utils.form.select>
                @if(!is_null($rates))
                    <hr class="hr-dashed hr-menu">
                    <div class="row">
                        <div class="col-12 text-end">
                            <div class="px-2 py-1">
                                <small class="font-10">Exchanged Amount</small>
                                <h4 class="mt-0"><small
                                        class="text-muted font-10 fw-light">{{$rates['to_currency']}}
                                    </small> {{number_format($rates['exchange_amount'],2)}}
                                </h4>
                                <small class="font-10 text-muted">At the rate
                                    of <b>{{ $rates['to_currency'] }} {{$rates['exchange_rate']}}</b>
                                    <i class="fas fa-exchange-alt text-danger"></i>
                                    <b>{{$rates['from_currency'] }}
                                        1</b> </small>
                            </div>

                        </div>
                    </div>

                @endif
                <hr class="hr-dashed hr-menu">

                <button class="btn btn-success w-100" type="submit" @if($errors->any()) disabled
                        @endif wire:target="continueSendBank"
                        wire:loading.attr="disabled"><span
                        wire:target="continueSendBank" wire:loading class="btn-spinner"></span> Continue
                </button>
            </form>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#send_currency').on('select2:select', function (e) {
            @this.call('setSendCurrency', e.target.value);
            });
        });
    </script>
@endpush
