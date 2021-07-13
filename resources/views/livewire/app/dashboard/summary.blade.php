<div class="card">
    <div class="card-header">
        <h6>Today's Summary
            <span wire:target="getSummary" wire:loading
                  class="float-end btn-spinner btn-spinner-soft-danger"></span>
        </h6>
    </div>
    <div class="card-body m-0 p-0" style="height: 335px" wire:init="getSummary">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                @foreach($wallets as $index => $wallet)
                    <button class="nav-link {{$loop->first ? 'active' : ''}}" id="nav-{{$wallet->id}}-tab"
                            data-bs-toggle="tab" data-bs-target="#nav-{{$wallet->id}}" type="button" role="tab"
                            aria-controls="nav-{{$wallet->id}}"
                            aria-selected="true">{{$wallet->currency->code}} Wallet
                    </button>

                @endforeach
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            @if(!is_null($summary))
                @foreach($summary as $index => $sum)
                    <div class="tab-pane fade {{$loop->first ? 'show active' : ''}}" id="nav-{{$sum->id}}"
                         role="tabpanel" aria-labelledby="nav-{{$sum->id}}-tab">
                        <div class="p-3 pb-0">
                            <div class="alert alert-outline-warning">

                                <h5><small class="font-12">Deposits</small> <span class="float-end"><span
                                            class="fw-light font-12 text-muted">{{$sum->currency->code}}</span> {{number_format($sum->depositSum, 2)}}
                                    </span>
                                </h5>
                            </div>

                            <div class="alert alert-outline-danger">

                                <h5><small class="font-12">Withdrawals</small> <span class="float-end"><span
                                            class="fw-light font-12 text-muted">{{$sum->currency->code}}</span> {{number_format($sum->withdrawalSum, 2)}}
                                </span>
                                </h5>
                            </div>

                            <div class="alert alert-outline-success">

                                <h5><small class="font-12">Commission Earned</small><span class="float-end"><span
                                            class="fw-light font-12 text-muted">{{$sum->currency->code}}</span> {{number_format($sum->commissionSum, 2)}}
                                    </span>
                                </h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

    </div>
</div>
