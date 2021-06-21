<div class="topbar">
    <!-- Navbar -->
    <nav class="navbar-custom">
        <ul class="list-unstyled topbar-nav float-end mb-0">
            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect" data-bs-toggle="dropdown" href="#" role="button"
                   aria-haspopup="false" aria-expanded="false">
                    <i data-feather="bell" class="align-self-center topbar-icon"></i>
                    <span class="badge bg-danger rounded-pill noti-icon-badge">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-lg pt-0">

                    <h6 class="dropdown-item-text font-15 m-0 py-3 border-bottom d-flex justify-content-between align-items-center">
                        Notifications <span class="badge bg-primary rounded-pill">0</span>
                    </h6>
                    <div class="notification-menu" data-simplebar>
                        <!-- item-->
{{--                        <a href="#" class="dropdown-item py-3">--}}
{{--                            <small class="float-end text-muted ps-2">2 min ago</small>--}}
{{--                            <div class="media">--}}
{{--                                <div class="avatar-md bg-soft-primary">--}}
{{--                                    <i data-feather="shopping-cart" class="align-self-center icon-xs"></i>--}}
{{--                                </div>--}}
{{--                                <div class="media-body align-self-center ms-2 text-truncate">--}}
{{--                                    <h6 class="my-0 fw-normal text-dark">Your order is placed</h6>--}}
{{--                                    <small class="text-muted mb-0">Dummy text of the printing and industry.</small>--}}
{{--                                </div><!--end media-body-->--}}
{{--                            </div><!--end media-->--}}
{{--                        </a><!--end-item-->--}}

                    </div>
                    <!-- All-->
                    <a href="javascript:void(0);" class="dropdown-item text-center text-primary">
                        View all <i class="fi-arrow-right"></i>
                    </a>
                </div>
            </li>

            <li class="dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-bs-toggle="dropdown" href="#" role="button"
                   aria-haspopup="false" aria-expanded="false">
                    <img src="{{asset('images/user.png')}}" alt="profile-user" class="rounded-circle thumb-xs" />
                    <span class="ms-1 nav-user-name hidden-sm">{{\Str::ucfirst(user()->first_name) ." ".\Str::ucfirst(user()->last_name)}}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="#"><i data-feather="settings" class="align-self-center icon-xs icon-dual me-1"></i> Settings</a>
                    <div class="dropdown-divider mb-0"></div>
                    <a class="dropdown-item" href="#"
                       onclick=" document.getElementById('logout-form').submit();"><i data-feather="power" class="align-self-center icon-xs icon-dual me-1"></i> {{ __('Logout') }}
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </a>
                </div>
            </li>
        </ul><!--end topbar-nav-->

        <ul class="list-unstyled topbar-nav mb-0">
            <li>
                <button class="nav-link button-menu-mobile">
                    <i data-feather="menu" class="align-self-center topbar-icon"></i>
                </button>
            </li>


        </ul>
    </nav>
    <!-- end navbar-->
</div>
