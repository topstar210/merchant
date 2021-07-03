<div class="accordion-item">
    <h3 class="accordion-header m-0" id="headingSBM">
        <button class="accordion-button fw-normal collapsed" type="button" @if($locked) disabled @endif
                data-bs-toggle="collapse" data-bs-target="#collapseSBM"
                aria-expanded="false" aria-controls="collapseSBM">
            <i class="fas fa-archive text-warning me-2 font-16"></i> Send to Bank /
            Mobile Wallet
        </button>
    </h3>
    <div id="collapseSBM" class="accordion-collapse collapse"
         aria-labelledby="headingSBM" data-bs-parent="#sendAccordion" style="">
        <div class="accordion-body">
            <h5 class="mt-0"><small class="text-muted">Send money to Banks or a Mobile Money wallet</small></h5>
            <hr>
            <form wire:submit.prevent="">
                <x-utils.form.amount-input :key="'amount'"  :js="''" :label="'Amount to Send'"/>



            </form>
        </div>
    </div>
</div>
