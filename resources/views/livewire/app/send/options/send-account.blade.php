<div class="accordion-item">
    <h3 class="accordion-header m-0" id="headingSAA">
        <button class="accordion-button fw-normal collapsed" type="button" @if($locked) disabled @endif
        data-bs-toggle="collapse" data-bs-target="#collapseSAA"
                aria-expanded="false" aria-controls="collapseSAA">
            <i class="far fa-user-circle text-warning me-2 font-16"></i> Send to Another
            IRT Account
        </button>
    </h3>
    <div id="collapseSAA" class="accordion-collapse collapse"
         aria-labelledby="headingSAA" data-bs-parent="#sendAccordion" style="">
        <div class="accordion-body">
            <h5 class="mt-0"><small class="text-muted">Send money to another IRT Account holder</small></h5>
            <hr>
            <form wire:submit.prevent="">
                <x-utils.form.amount-input :key="'amount'" :js="''" :label="'Amount to Send'"/>


            </form>
        </div>
    </div>
</div>
