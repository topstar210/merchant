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
                        @if(!$supported)
                            <div class="alert icon-custom-alert alert-danger b-round-sm fade show mt-2"
                                 role="alert">
                                <i class="ti-info-alt alert-icon"></i>
                                <div class="alert-text font-12">
                                    This currency is currently not supported.
                                </div>

                            </div>
                        @else
                            <form wire:submit.prevent="continueSendBank">
                                <div class="row">
                                    <div class="col">
                                        <x-utils.form.input :key="'account'" :js="'lazy'"
                                                            :label="in_array($recipient_currency->code, ['GHS','KES', 'RWF', 'TZS', 'UGX', 'XAF', 'XOF', 'ZMW']) ? 'Account Number / Mobile Number' : 'Account Number'"/>
                                    </div>
                                    <div class="col-auto" wire:target="account, setSelectedBank" wire:loading>
                                    <span wire:target="account, setSelectedBank" wire:loading
                                          class="btn-spinner btn-spinner-soft-danger" style="margin-top:2.2rem"></span>
                                    </div>
                                </div>
                                @if(in_array($recipient_currency->code, ['USD', 'EUR', 'GBP']))
                                    <div class="row">
                                        <div class="col">
                                            <x-utils.form.input :key="'beneficiary_name'" :js="'lazy'"
                                                                :label="'Account Name'"/>
                                        </div>
                                    </div>
                                    @if(in_array($recipient_currency->code, ['EUR']))
                                        <div class="row">
                                            <div class="col">
                                                <x-utils.form.select :key="'recipient_country'" :js="''"
                                                                     :label="'Recipient Country'">
                                                    @foreach($eu_countries as $key=> $value)
                                                        <option value="{{$key}}">{{$value}}</option>
                                                    @endforeach

                                                </x-utils.form.select>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col">
                                            <x-utils.form.input :key="'recipient_bank'" :js="'lazy'"
                                                                :label="'Bank Name'"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <x-utils.form.input :key="'routing_number'" :js="'lazy'"
                                                                :label="'Routing Number'"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <x-utils.form.input :key="'swift_code'" :js="'lazy'"
                                                                :label="'Swift Code'"/>
                                        </div>
                                    </div>
                                    @if(in_array($recipient_currency->code, ['EUR', 'GBP']))

                                        <div class="row">
                                            <div class="col">
                                                <x-utils.form.input :key="'postal_code'" :js="'lazy'"
                                                                    :label="'Postal Code'"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <x-utils.form.input :key="'street_number'" :js="'lazy'"
                                                                    :label="'Street Number'"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <x-utils.form.input :key="'street_name'" :js="'lazy'"
                                                                    :label="'Street Name'"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <x-utils.form.input :key="'city'" :js="'lazy'"
                                                                    :label="'City'"/>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col">
                                                <x-utils.form.input :key="'beneficiary_address'" :js="'lazy'"
                                                                    :label="'Recipient Address'"/>
                                            </div>
                                        </div>
                                    @endif
                                    {{--                                    <hr class="hr-dashed hr-menu">--}}
                                    <button class="btn btn-success w-100" type="submit" @if($errors->any()) disabled
                                            @endif wire:target="continueSendBank"
                                            wire:loading.attr="disabled"><span
                                            wire:target="continueSendBank" wire:loading class="btn-spinner"></span>
                                        Continue
                                    </button>
                                @else
                                    <div class="row" wire:init="retrieveBanks">
                                        <div class="col">
                                            <x-utils.form.select :key="'recipient_bank'" :js="''"
                                                                 :label="'Choose Bank'"/>
                                        </div>
                                        <div class="col-auto" wire:target="retrieveBanks" wire:loading>
                                    <span wire:target="retrieveBanks" wire:loading
                                          class="btn-spinner btn-spinner-soft-danger" style="margin-top:2.2rem"></span>
                                        </div>
                                    </div>


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
                                            @if(in_array($recipient_currency->code, ['ZAR']))
                                                <div class="row">
                                                    <div class="col">
                                                        <x-utils.form.input :key="'beneficiary_address'" :js="'lazy'"
                                                                            :label="'Recipient Address'"/>
                                                    </div>
                                                </div>
                                            @endif

                                        @endif

                                    @endif

                                    @if(!is_null($selectedAccount) && !$errors->has('account'))
                                        @if(is_null($tempAccount) )
                                            <div class="card-alt card-body mb-2">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <small class="font-10">Recipient Account</small>
                                                        <h6 class="mt-0 mb-0">{{$selectedAccount['account_name']}}</h6>
                                                        <small
                                                            class="text-muted">{{$selectedAccount['account']}}</small>
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
                var newOption = new Option((val.Name ?? val.bankName).toUpperCase(), val.Id ?? val.id, false, false);
                $('#recipient_bank').append(newOption).trigger('change');
            })
        })

        $('#recipient_bank').on('select2:select', function (event) {
        @this.call('setSelectedBank', event.target.value);
        })

        $('#recipient_country').on('select2:select', function (e) {
        @this.set('recipient_country', e.target.value);
        });
    </script>
@endpush
