<x-slot name="title">
    Send To Bank
</x-slot>
<div class="page-content">
    <x-utils.actionbar :title="'Send To Bank'" :showBack="''"/>

    <div class="container-fluid app-main">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-4 mx-auto">
                <div class="card mt-4">
                    <div class="card-header">
                        <h6><i class="ti-info-alt text-info"></i> Initiate Send to Bank
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

                        <div class="d-flex flex-row">
                            <div class="col">
                                <small class="font-10">Amount</small>
                                <h4 class="mt-0"><small
                                        class="text-muted font-11 fw-light">{{$wallet->currency->code}}
                                    </small> {{number_format($amount, 2)}}</h4>
                            </div>
                            <div class="col-auto align-self-center">
                                <i class="fas fa-exchange-alt text-danger"></i>
                            </div>
                            <div class="col text-end">
                                <small class="font-10">Recipient Receives</small>
                                <h4 class="mt-0">

                                    <small
                                        class="text-muted font-11 fw-light">{{$recipient_currency->code}}
                                    </small> {{number_format($rates['exchange_amount'],2)}}</h4>

                            </div>
                        </div>
                        <div class=" text-center">
                            <small class="font-10 text-muted">At the rate
                                of <b>{{ $recipient_currency->code }} {{$rates['exchange_rate']}}</b>
                                to
                                <b>{{$wallet->currency->code }}
                                    1</b> </small>
                        </div>
                        <hr>
                        @if(empty($payment_method))
                            <div class="alert icon-custom-alert alert-danger b-round-sm fade show mt-2"
                                 role="alert">
                                <i class="ti-info-alt alert-icon"></i>
                                <div class="alert-text font-11">
                                    This currency is currently not supported.
                                </div>

                            </div>

                        @else
                            <form wire:submit.prevent="continueSendBank">
                                <div class="row" wire:init="retrieveBanks">
                                    <div class="col">
                                        <x-utils.form.select :key="'recipient_bank'" :js="''" :label="'Choose Bank'"/>
                                    </div>
                                    <div class="col-auto" wire:target="retrieveBanks" wire:loading>
                                    <span wire:target="retrieveBanks" wire:loading
                                          class="btn-spinner btn-spinner-soft-danger" style="margin-top:2.2rem"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top">
                                        <x-utils.form.input :key="'account'" :js="'lazy'" :label="'Account Number'"/>
                                    </div>
                                    <div class="col-auto" wire:target="account" wire:loading>
                                    <span wire:target="account" wire:loading
                                          class="btn-spinner btn-spinner-soft-danger" style="margin-top:2.2rem"></span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top">
                                    Tooltip on top
                                </button>

                                @if(!is_null($tempAccount) && !$errors->has('account'))
                                    @if(!empty($tempAccount['account_name']))
                                        <div class="card-alt card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <small class="font-10">Recipient Account</small>
                                                    <h6 class="mt-0 mb-0">{{$tempAccount['account_name']}}</h6>
                                                    <small class="text-muted">{{$tempAccount['account']}}</small>
                                                </div>
                                                <div class="col-auto float-end">
                                                    <div class="">
                                                        <small class="font-10">Confirm?</small>
                                                    </div>
                                                    <button class="btn btn-sm btn-soft-success" type="button"
                                                            wire:click="confirmAccount(true)"><span
                                                            class="fas fa-check"></span>
                                                    </button>
                                                    <button class="btn btn-sm btn-soft-danger" type="button"
                                                            wire:click="confirmAccount(false)"><span
                                                            class="fas fa-times"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <x-utils.form.input :key="'beneficiary'" :js="'lazy'"
                                                            :label="'Recipient Name'"/>

                                    @endif
                                @endif

                                @if(!is_null($selectedAccount) && !$errors->has('account'))
                                    @if(is_null($tempAccount) )
                                        <div class="card-alt card-body mb-2">
                                            <div class="row">
                                                <div class="col-12">
                                                    <small class="font-10">Recipient Account</small>
                                                    <h6 class="mt-0 mb-0">{{$selectedAccount['account_name']}}</h6>
                                                    <small class="text-muted">{{$selectedAccount['account']}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <hr class="hr-dashed hr-menu">
                                    <button class="btn btn-success w-100" type="submit" @if($errors->any()) disabled
                                            @endif wire:target="continueSendBank"
                                            wire:loading.attr="disabled"><span
                                            wire:target="continueSendBank" wire:loading class="btn-spinner"></span>
                                        Continue
                                    </button>
                                @endif
                            </form>
                        @endif

                    </div>

                </div>

            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        window.addEventListener('set_banks', event => {
            $.map(event.detail, function (val, key) {
                var newOption = new Option(val.Name ?? val.bankName, val.Id ?? val.id, false, false);
                $('#recipient_bank').append(newOption).trigger('change');
            })
        })

        $('#recipient_bank').on('select2:select', function (event) {
        @this.call('setSelectedBank', event.target.value);
        })
    </script>
@endpush
