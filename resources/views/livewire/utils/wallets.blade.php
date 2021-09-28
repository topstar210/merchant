<div>

    @if(!is_null($lien))
            <div class="mx-3 pt-3 pb-4">
                <hr class="hr-dashed hr-menu mt-2">
                <div class="mb-2">

                    <small class="text-white font-11">Lien Amount</small> <span class="float-right text-danger">{{$lien->currency->code}}
                        <b class="text-danger font-16">{{number_format($lien->lien_amount, 2)}}</b>
                    </span>

                </div>
                <div><small class="float-end font-11" style="color: #919191">Lien will expire
                        on {{formatDateOnly($lien->lien_end_date)}}</small></div>
            </div>
    @endif

    <div class="mx-3">
        <hr class="hr-dashed hr-menu mt-2">
        <h5>Wallets</h5>
    </div>
    @forelse($wallets as $wallet)
        <div class="wallet-left-item">
            <div class="d-flex flex-row">
                <div class="col-auto">
                         <span
                             class="font-18 flag-icon flag-icon-{{$wallet->currency->code == 'EUR' ? 'eu' : strtolower(substr($wallet->currency->code, 0,2))}}"></span>
                </div>
                <div class="col">
                    <h5 class="mt-1 mb-1">
                    <span class="float-right text-info">{{$wallet->currency->code}}
                        <b class="text-white font-18">{{number_format($wallet->balance, 2)}}</b>
                    </span>
                    </h5>
                </div>
            </div>
            <div class="d-flex flex-row mt-1">
                <div class="col text-end">
                    <small class="text-muted font-10 ">Commission: {{$wallet->currency->code}} <b
                            class="font-12 text-success">{{number_format($wallet->commission, 2)}}</b></small>
                </div>
            </div>

            <div class="mt-2 d-flex flex-row">
                <div class="col">
                    @if(!$wallet->lock)
                        <button class="btn btn-outline-success btn-sm font-10"
                                onclick="window.location.href='{{url('app/wallet/'.$wallet->id)}}'">View Wallet
                        </button>
                    @endif
                    @if($wallet->lock)
                        <span class="badge bg-danger"><i class="ti-lock"></i> Locked</span>
                    @endif
                </div>
                {{--                    <div class="col-auto text-end">--}}
                {{--                        --}}
                {{--                    </div>--}}
            </div>
        </div>
    @empty
        <div class="wallet-left-item text-center">
            <h5 class="mt-3 mb-3"><i class="ti-info-alt text-danger"></i> No Wallet</h5>
            <a class="btn btn-soft-primary btn-sm w-100">Add Wallet</a>
        </div>

    @endforelse
</div>
