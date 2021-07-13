<x-slot name="title">
    My Account
</x-slot>

<div class="page-content">
    <x-utils.actionbar :title="'My Account'" wire:ignore.self>

        <x-slot name="action">
            <button class="btn btn-soft-danger @mobile btn-sm @endmobile"
                    onclick=" document.getElementById('logout-form').submit();"><i
                    class="align-self-center ti-lock"></i>
                Logout
            </button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </x-slot>
    </x-utils.actionbar>
    <div class="container-fluid app-main">
        <div class="row d-flex  mx-auto justify-content-center">
            <div class="col-lg-4">
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Account Details</h5>
                    </div>
                    <div class="card-body">
                        <small class="font-10">Account</small>
                        <h6 class="mt-0">{{$account->account_number}}</h6>
                        <hr>
                        <small class="font-10">Full Name</small>
                        <h6 class="mt-0">{{$account->full_name}}
                        </h6>
                        <hr>
                        <small class="font-10">Email</small>
                        <h6 class="mt-0">{{$account->email}}</h6>
                        <hr>
                        <small class="font-10">Country/Phone</small>
                        <h6 class="mt-0"><span
                                class="flag-icon flag-icon-{{strtolower($account->defaultCountry)}}"></span> {{$account->formattedPhone}}
                        </h6>

                        <hr>
                        <div class="d-flex row">
                            <div class="col-md-6">
                                <h6>Password</h6>
                            </div>
                            <div class="col-md-6">
                                <a class="btn btn-danger w-100 @mobile btn-sm @endmobile me-2"
                                   href="{{url('app/account/security/password')}}">Change Password</a>
                            </div>
                            <small class="fw-light font-10 mt-3">Change your password if you think you have been
                                compromised.</small>

                        </div>
                        <hr>
                        <div class="d-flex row">
                            <div class="col-md-6">
                                <h6>Transaction Pin</h6>
                            </div>
                            <div class="col-md-6">
                                <a class="btn btn-danger w-100 @mobile btn-sm @endmobile "
                                   href="{{url('app/account/security/pin')}}">Change Pin</a>
                            </div>
                            <small class="fw-light font-10 mt-3 mb-2">Change your transaction pin frequently to a more
                                secure one.</small>

                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Recent Activities</h5>
                    </div>
                    <div class="card-body activity-card mb-3" data-simplebar>
                        @forelse($account->activities as $act)
                            <x-utils.ui.activity :activity="$act" :browser="Browser::parse($act->browser_agent)"/>
                            @if(!$loop->last)
                                <hr class="mt-3 mb-3">
                            @endif
                        @empty
                            <x-utils.empty :noFooter="''">
                                <h5><i class="ti-info-alt text-danger"></i> No Activity Yet</h5>
                            </x-utils.empty>


                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
