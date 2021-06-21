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
            <li>
                <a href="{{url('/app')}}">
                    <i data-feather="home"
                       class="align-self-center menu-icon"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{--            @role('merchant')--}}
            <li>
                <a href="{{url('/app/agents')}}">
                    <i data-feather="users"
                       class="align-self-center menu-icon"></i>
                    <span>Agents</span>
                    @if((user()->merchant->loadCount('users')->users_count - 1) > 0)
                        <span
                            class="badge bg-danger b-round-sm menu-arrow">{{user()->merchant->loadCount('users')->users_count - 1}}</span>
                    @endif
                </a>
            </li>
            {{--            @endrole--}}

            {{--            <li>--}}
            {{--                <a href="javascript: void(0);"><i data-feather="file-plus"--}}
            {{--                                                  class="align-self-center menu-icon"></i><span>Pages</span><span--}}
            {{--                        class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>--}}
            {{--                <ul class="nav-second-level" aria-expanded="false">--}}
            {{--                    <li class="nav-item"><a class="nav-link" href="pages-blogs.html"><i class="ti-control-record"></i>Blogs</a>--}}
            {{--                    </li>--}}
            {{--                    <li class="nav-item"><a class="nav-link" href="pages-faqs.html"><i class="ti-control-record"></i>FAQs</a>--}}
            {{--                    </li>--}}
            {{--                    <li class="nav-item"><a class="nav-link" href="pages-pricing.html"><i class="ti-control-record"></i>Pricing</a>--}}
            {{--                    </li>--}}
            {{--                    <li class="nav-item"><a class="nav-link" href="pages-profile.html"><i class="ti-control-record"></i>Profile</a>--}}
            {{--                    </li>--}}
            {{--                    <li class="nav-item"><a class="nav-link" href="pages-starter.html"><i class="ti-control-record"></i>Starter--}}
            {{--                            Page</a></li>--}}
            {{--                    <li class="nav-item"><a class="nav-link" href="pages-timeline.html"><i--}}
            {{--                                class="ti-control-record"></i>Timeline</a></li>--}}
            {{--                    <li class="nav-item"><a class="nav-link" href="pages-treeview.html"><i--}}
            {{--                                class="ti-control-record"></i>Treeview</a></li>--}}
            {{--                </ul>--}}
            {{--            </li>--}}
            <hr class="hr-dashed hr-menu mt-5">
            <li class="menu-label">Wallets</li>
        </ul>
        @forelse(user()->wallets as $wallet)
            <div class="wallet-left-item">
                <h5 class="mt-1 mb-1">
                    <span
                        class="font-18 flag-icon flag-icon-{{$wallet->currency->code == 'EUR' ? 'eu' : strtolower(substr($wallet->currency->code, 0,2))}}"></span><span
                        class="float-right text-info">{{$wallet->currency->code}}
                        <b class="text-white font-18">{{number_format($wallet->balance, 2)}}</b>
                    </span>
                </h5>
                <div class="float-end"><small class="text-muted font-10">Available
                        Balance: {{number_format($wallet->balance, 2)}}</small></div><br>
                <a class="btn btn-soft-success btn-sm font-10 mt-1">View Wallet</a>
            </div>
        @empty
            <div class="wallet-left-item text-center">
                <h5 class="mt-3 mb-3"><i class="ti-info-alt text-danger"></i> No Wallet</h5>
                <a class="btn btn-soft-primary btn-sm w-100">Add Wallet</a>
            </div>

        @endforelse
    </div>
</div>
