<x-slot name="title">
    Transaction Processing
</x-slot>

<div class="page-content">
    <x-utils.actionbar :title="'Reference: '.$transaction->reference" wire:ignore/>
    <div class="container-fluid app-main">
        <div class="row d-flex justify-content-center">
            @if($transaction->status == 'Pending')
                <div class="col-lg-4 mx-auto" wire:poll.5s="checkTransaction">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6><i class="ti-info-alt text-danger"></i> Do not refresh this page</h6>
                        </div>
                        <div class="card-body text-center pb-4">
                            <div class="loading-spinner mt-4 mb-5"></div>
                            <h4>Processing Transaction</h4>
                            <p class="text-muted font-11">Please wait, we are confirming your transaction. Kindly stay on this page until
                                this process is completed</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
