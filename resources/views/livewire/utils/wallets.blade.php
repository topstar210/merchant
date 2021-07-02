<div>
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
                    <small class="text-muted font-10 ">Available
                        Balance: {{number_format($wallet->balance, 2)}}</small>
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
