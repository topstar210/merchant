<div class="left-sidenav">
    <!-- LOGO -->
    <div class="brand">
        <a href="{{url('/app')}}" class="logo">
            <span>
                <img src="{{asset('images/default-logo.png')}}" alt="logo-large" class="logo-lg logo-light">

            </span> IMO Rapid Transfer
        </a>
    </div>
    <div class="menu-content h-100" data-simplebar>
        <ul class="metismenu left-sidenav-menu">
            <h5>{{user()->merchant->merchant_name}}</h5>

            <hr class="hr-dashed hr-menu">
            <li class="menu-label my-2">Menu</li>
            @can('viewDashboard', \App\Models\Permission::class)
                <li>
                    <a href="{{url('/app')}}">
                        <i data-feather="home"
                           class="align-self-center menu-icon"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            @endcan
            @can('isMerchant', \App\Models\Permission::class)
                <li>
                    <a href="{{url('/app/agents')}}">
                        <i data-feather="users"
                           class="align-self-center menu-icon"></i>
                        <span>Agents</span>
                        {{--                    @if((user()->merchant->loadCount('users')->users_count - 1) > 0)--}}
                        <span
                            class="badge bg-danger b-round-sm menu-arrow">{{user()->merchant->loadCount('users')->users_count - 1}}</span>
                        {{--                    @endif--}}
                    </a>
                </li>
            @endcan


            {{--            <li>--}}
            {{--                <a href="javascript: void(0);"><i data-feather="file-plus"--}}
            {{--                                                  class="align-self-center menu-icon"></i><span>Pages</span><span--}}
            {{--                        class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>--}}
            {{--                <ul class="nav-second-level" aria-expanded="false">--}}
            {{--                    <li class="nav-item"><a class="nav-link" href="pages-blogs.html"><i class="ti-control-record"></i>Blogs</a>--}}
            {{--                    </li>--}}
            {{--                </ul>--}}
            {{--            </li>--}}
            <hr class="hr-dashed hr-menu mt-5">
            <li class="menu-label">Wallets</li>
        </ul>
        @forelse(user()->wallets as $wallet)
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
</div>
