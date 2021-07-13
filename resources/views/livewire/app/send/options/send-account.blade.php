<div class="accordion-item">
    <h3 class="accordion-header m-0" id="headingSAA" x-data="{ lock: @entangle('locked') }">
        <button class="accordion-button fw-normal collapsed" type="button" x-bind:disabled="lock" wire:ignore
                data-bs-toggle="collapse" data-bs-target="#collapseSAA"
                aria-expanded="false" aria-controls="collapseSAA">
            <i class="far fa-user-circle text-warning me-2 font-16"></i> Send to Another
            IRT Account
        </button>
    </h3>
    <div id="collapseSAA" class="accordion-collapse collapse" wire:ignore.self
         aria-labelledby="headingSAA" data-bs-parent="#sendAccordion" style="">
        <div class="accordion-body">
            <h5 class="mt-0"><small class="text-muted">Send money to another IRT Account holder</small></h5>
            <hr>
            <form wire:submit.prevent="continueSendAccount">
                <div class="row">
                    <div class="col">
                        <x-utils.form.input :key="'recipient_irt_account'" :js="''" :label="'IRT Account Number'"/>
                    </div>
                    <div class="col-auto" wire:target="recipient_irt_account" wire:loading>
                                    <span wire:target="recipient_irt_account" wire:loading
                                          class="btn-spinner btn-spinner-soft-danger" style="margin-top:2.2rem"></span>
                    </div>
                </div>

                @if(!is_null($tempAccount) && !$errors->has('recipient_irt_account'))
                    <div class="card-alt card-body">
                        <div class="row">
                            <div class="col">
                                <small class="font-10">Recipient Account</small>
                                <h6 class="mt-0 mb-0">{{$tempAccount->full_name}}</h6>
                                <small class="text-muted">{{$tempAccount->account_number}}</small>
                            </div>
                            <div class="col-auto float-end">
                                <div class="">
                                    <small class="font-10">Confirm?</small>
                                </div>
                                <button class="btn btn-sm btn-soft-success" type="button"
                                        wire:click="confirmAccount(true)"><span class="fas fa-check"></span>
                                </button>
                                <button class="btn btn-sm btn-soft-danger" type="button"
                                        wire:click="confirmAccount(false)"><span class="fas fa-times"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                @if(!is_null($selectedAccount) && !$errors->has('recipient_irt_account'))
                    <div class="card-alt card-body mb-2">
                        <div class="row">
                            <div class="col-12">
                                <small class="font-10">Recipient Account</small>
                                <h6 class="mt-0 mb-0">{{$selectedAccount->full_name}}</h6>
                                <small class="text-muted">{{$selectedAccount->account_number}}</small>
                            </div>
                        </div>
                    </div>

                    <x-utils.form.amount-input :key="'amount'" :js="''" :label="'Amount to Send'"/>
                    <x-utils.form.select :key="'recipient_account_wallet'" :js="''" :class="'currency'">
                        @foreach($selectedAccount->wallets ?? [] as $wallet)
                            <option
                                value="{{$wallet->id}}"
                                label="{{$wallet->currency->code}}">{{$wallet->currency->name}}</option>
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


                        <hr class="hr-dashed hr-menu">

                        <button class="btn btn-success w-100" type="submit" @if($errors->any()) disabled
                                @endif wire:target="continueSendAccount"
                                wire:loading.attr="disabled"><span
                                wire:target="continueSendAccount" wire:loading class="btn-spinner"></span> Continue
                        </button>
                    @endif
                @else
                    <select class="d-none" id="recipient_account_wallet">

                    </select>
                @endif


            </form>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $('#recipient_account_wallet').on('select2:select', function (e) {
            console.log('hello');
        @this.call('setRecipientAccountWallet', e.target.value);
        });
    </script>
@endpush
