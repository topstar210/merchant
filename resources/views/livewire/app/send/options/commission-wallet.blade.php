<div class="accordion-item">
    <h3 class="accordion-header m-0" id="headingCW" x-data="{ lock: @entangle('locked') }">
        <button class="accordion-button fw-normal collapsed" type="button" x-bind:disabled="lock" wire:ignore
                data-bs-toggle="collapse" data-bs-target="#collapseCW"
                aria-expanded="false" aria-controls="collapseCW">
            <i class="fas fa-clone text-warning me-2 font-16"></i> Commission to Wallet
        </button>
    </h3>
    <div id="collapseCW" class="accordion-collapse collapse" wire:ignore.self
         aria-labelledby="headingCW" data-bs-parent="#sendAccordion" style="">
        <div class="accordion-body">
            <h5 class="mt-0"><small class="text-muted">Send from your commission to wallet</small></h5>
            <hr>
            <div class="row">
                <div class="col text-end">
                    <small class="font-10">Commission Balance</small>
                    <h4 class="mt-0"><small
                            class="text-muted font-10 fw-light">{{$wallet->currency->code}}
                        </small> {{number_format($wallet->commission, 2)}}</h4>
                </div>
            </div>
            <hr>
            <form wire:submit.prevent="continueCommissionWallet">
                <x-utils.form.amount-input :key="'amount'" :js="''" :label="'Amount to Send'"/>

                <hr class="hr-dashed hr-menu">

                <button class="btn btn-success w-100" type="submit" @if($errors->any()) disabled
                        @endif wire:target="continueCommissionWallet"
                        wire:loading.attr="disabled"><span
                        wire:target="continueCommissionWallet" wire:loading class="btn-spinner"></span> Continue
                </button>
            </form>

        </div>
    </div>
</div>
