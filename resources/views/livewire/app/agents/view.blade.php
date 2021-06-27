<x-slot name="title">
    View Agent
</x-slot>

<div class="page-content">
    <x-utils.actionbar :title="$agent->full_name" :showBack="''" wire:ignore.self>
        <x-slot name="action">
            @if(!$agent->reg_com)
                <button class=" btn btn-md btn-soft-danger" wire:target="deleteAgent" wire:loading.attr="disabled"
                        onclick="handleDelete()"><i wire:target="deleteAgent"
                                                    wire:loading.class="d-none" class="fas fa-trash me-2"></i><span
                        wire:loading class="btn-spinner btn-spinner-soft-danger"></span> Delete
                </button>
            @else
                <button wire:target="updateAgentStatus" wire:loading.attr="disabled"
                        onclick="handleAgentStatus('{{$agent->status}}')"
                        class="btn btn-soft-{{$agent->status == 'Active' ? 'danger' : 'success'}} btn-md me-2">
                                        <span wire:target="updateAgentStatus"
                                              wire:loading
                                              class="btn-spinner btn-spinner-soft-{{$agent->status == 'Active' ? 'danger' : 'success'}}"></span> {{$agent->status == 'Active' ? 'Deactivate' : 'Activate'}}
                </button>
            @endif
        </x-slot>
    </x-utils.actionbar>

    <div class="container-fluid app-main">
        <div class="row ">
            <div class="col-lg-4">
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Agent Details @if(!$agent->reg_com)
                                <span
                                    class="badge bg-warning float-end">Invitation Sent</span>
                            @elseif($agent->status == 'Active')
                                <span
                                    class="badge bg-success  float-end">Active</span>
                            @else
                                <span
                                    class="badge bg-danger  float-end">Inactive</span>
                            @endif</h5>
                    </div>
                    <div class="card-body">
                        <small class="font-10">Account</small>
                        <h6 class="mt-0">{{$agent->account_number}}</h6>
                        <hr>
                        <small class="font-10">Email</small>
                        <h6 class="mt-0">{{$agent->email}}</h6>
                        <hr>
                        <small class="font-10">Country/Phone</small>
                        <h6 class="mt-0"><span
                                class="flag-icon flag-icon-{{strtolower($agent->defaultCountry)}}"></span> {{$agent->formattedPhone}}
                        </h6>
                        <hr>

                        <div class="d-flex flex-row">
                            <div class="col">
                                <small class="font-10">State/Region</small>
                                <h6 class="mt-0">{{$agent->userDetail->state}}
                                </h6>
                            </div>
                            <div class="col text-end">
                                <small class="font-10">City</small>
                                <h6 class="mt-0">{{$agent->userDetail->city}}
                                </h6>
                            </div>
                        </div>
                        <hr>
                        <small class="font-10">Address</small>
                        <h6 class="mt-0">{{$agent->userDetail->address_1}}
                        </h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Wallets</h5>
                    </div>
                    <div class="card-body">
                        @forelse($agent->wallets as $wallet)
                            <div class="wallet-left-item wallet-main" wire:key="{{$wallet->id}}">
                                <h5 class="mt-1 mb-1">
                                    <span
                                        class="font-18 flag-icon flag-icon-{{$wallet->currency->code == 'EUR' ? 'eu' : strtolower(substr($wallet->currency->code, 0,2))}}"></span><span
                                        class="float-right text-info">{{$wallet->currency->code}}
                                        <b class="text-dark font-18">{{number_format($wallet->balance, 2)}}</b>
                                    </span>
                                </h5>
                                <div class="float-end"><small class="text-muted font-10">Available
                                        Balance: {{number_format($wallet->balance, 2)}}</small></div>
                                <br>
                                @if($agent->reg_com)
                                    <div class="mt-2">
                                        <button class="btn btn-outline-primary btn-sm font-10 me-2"
                                                onclick="window.location.href='{{url('app/wallet/'.$wallet->id)}}'">
                                            View Wallet
                                        </button>
                                        <button wire:target="updateWalletStatus"
                                                class="btn {{$wallet->lock ? 'btn-success' : 'btn-danger'}} btn-sm font-10 me-2"
                                                wire:loading.attr="disabled"
                                                onclick="handleWalletLock('{{$wallet->id}}', '{{$wallet->lock}}')">{{$wallet->lock ? "Unlock Wallet" : "Lock Wallet"}}
                                        </button>
                                        @if($wallet->lock)
                                            <span class="badge badge-soft-danger float-end"><i class="ti-lock"></i> Locked</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="wallet-left-item wallet-main text-center">
                                <h5 class="mt-3 mb-3"><i class="ti-info-alt text-danger"></i> No Wallet</h5>
                            </div>

                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Recent Activities</h5>
                    </div>
                    <div class="card-body activity-card overflow-scroll mb-3">
                        @forelse($agent->activities as $act)
                            <x-utils.ui.activity :activity="$act" :browser="Browser::parse($act->browser_agent)"/>
                            @if(!$loop->last)
                                <hr class="mt-3 mb-3">
                            @endif
                        @empty
                            <x-utils.empty>
                                <h5><i class="ti-info-alt text-danger"></i> No Activity Yet</h5>
                            </x-utils.empty>


                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--    @if (session()->has('error'))--}}

    {{--        <div class="toast-container position-absolute top-0 end-0 p-3" style="z-index: 1050">--}}
    {{--            <div role="alert" aria-live="assertive" aria-atomic="true" class="toast fade show bg-white">--}}
    {{--                <div class="toast-header">--}}

    {{--                    <span class="me-auto badge {{session('error') ? 'badge-soft-danger' : 'badge-soft-success'}}"--}}
    {{--                          style="    padding: .55em .9em;"><span--}}
    {{--                            class=" font-14">{{session('error') ? "Error" : "Success"}}</span></span>--}}
    {{--                    <small class="text-muted">just now</small>--}}
    {{--                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>--}}
    {{--                </div>--}}
    {{--                <div class="toast-body">--}}
    {{--                    {{session('error_message')}}--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    @endif--}}

</div>

@push('styles')
    <link href="{{asset('plugins/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">
@endpush
@push('scripts')
    <script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    <script>

        function handleDelete() {
            Swal.fire({
                title: 'Delete Agent',
                text: 'Are you sure you want to delete Agent?',
                showCancelButton: true,
                confirmButtonText: `Delete`,
            }).then((result) => {
                if (result.value) {
                @this.call('deleteAgent');
                }
            })
        }

        function handleWalletLock(wallet, status) {
            Swal.fire({
                title: status === '1' ? 'Unlock Wallet' : 'Lock Wallet',
                text: status === '1' ? 'Are you sure you want to unlock wallet?' : 'Are you sure you want to lock wallet?',
                showCancelButton: true,
                confirmButtonText: status === '1' ? `Unlock` : `Lock`,
            }).then((result) => {
                if (result.value) {
                @this.call('updateWalletStatus', parseInt(wallet));
                }
            })
        }

        function handleAgentStatus(status) {
            Swal.fire({
                title: status === 'Active' ? 'Deactivate Agent' : 'Activate Agent',
                text: status === 'Active' ? 'Are you sure you want to deactivate Agent?' : 'Are you sure you want to activate Agent?',
                showCancelButton: true,
                confirmButtonText: status === 'Active' ? `Deactivate` : `Activate`,
            }).then((result) => {
                if (result.value) {
                @this.call('updateAgentStatus');
                }
            })
        }
    </script>
@endpush