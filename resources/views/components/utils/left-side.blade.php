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
            @can('shouldSend', \App\Models\Permission::class)
                <li>
                    <a href="{{url('/app/send')}}">
                        <i data-feather="send"
                           class="align-self-center menu-icon"></i>
                        <span>Send</span>
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

        <livewire:utils.wallets :wallets="user()->wallets"/>
    </div>
</div>
