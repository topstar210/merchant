<div class="accordion-item">
    <h3 class="accordion-header m-0" id="headingSMW" x-data="{ lock: @entangle('locked') }">
        <button class="accordion-button fw-normal collapsed" type="button" x-bind:disabled="lock" wire:ignore
                data-bs-toggle="collapse" data-bs-target="#collapseSMW"
                aria-expanded="false" aria-controls="collapseSMW">
            <i class="fas fa-wallet text-warning me-2 font-16"></i> Send to My Wallet
        </button>
    </h3>

    <div id="collapseSMW" class="accordion-collapse collapse" wire:ignore.self
         aria-labelledby="headingSMW" data-bs-parent="#sendAccordion" style="">
        <div class="accordion-body">
            <h5 class="mt-0"><small class="text-muted">Send money to your other wallet</small></h5>
            <hr>
            <form wire:submit.prevent="">
                <x-utils.form.amount-input :key="'amount'" :js="''" :label="'Amount to Send'"/>
                <x-utils.form.select :key="'recipient_wallet'" :js="''" :class="'currency'">
                    @foreach($this->other_wallets as $others)
                        <option
                            value="{{$others->id}}"
                            label="{{$others->currency->code}}">{{$others->currency->name}}</option>
                    @endforeach
                </x-utils.form.select>


            </form>
        </div>
    </div>
</div>
