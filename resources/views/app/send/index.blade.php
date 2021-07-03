<x-app>
    <x-slot name="title">
        Send Money
    </x-slot>
    <div class="page-content">
        <x-utils.actionbar :title="'Send'" wire:ignore/>

        <div class="container-fluid app-main">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-4 mx-auto">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6><i class="ti-info-alt text-info"></i> Select Wallet
                            </h6>
                        </div>
                        <div class="card-body pt-0 pb-0 px-0">
                            <ul class="list-group custom-list-group">
                                @foreach(user()->noLockWallets as $wallet)
                                        <li class="list-group-item d-flex justify-content-between"  style="cursor: pointer"
                                            onclick="window.location.href='{{url('app/send/'.$wallet->id)}}'">
                                            <div class="media">
                                                <div class="media-body">
                                                    <div class="d-flex flex-row">
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
                                                </div>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="ti-angle-right mx-1 mt-1"></i>
                                            </div>
                                        </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app>
