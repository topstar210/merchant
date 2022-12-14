<x-auth>
    <x-slot name="title">
        Account Setup
    </x-slot>

    <div class="container">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="row">
                    <div class="col-lg-4 mx-auto auth-main">
                        <div class="card">
                            <div class="card-body p-0 auth-header-box">
                                <div class="text-center p-3">
                                    <a  class="logo logo-admin">
                                        <img src="{{asset('images/default-logo.png')}}" height="50" alt="logo"
                                             class="auth-logo">
                                    </a>
                                    <h4 class="mt-3 mb-1 fw-semibold text-white font-18">IMO Rapid Transfer</h4>
                                    <p class="text-muted  mb-0">Account Setup</p>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class=" p-4">
                                    @error('error')
                                    <div class="alert icon-custom-alert alert-danger b-round-sm fade show"
                                         role="alert">
                                        <i class="ti-info-alt alert-icon"></i>
                                        <div class="alert-text  font-12">
                                            {{$message }}
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                    </div>
                                    @enderror

                                    <h3>Hello, {{$user->first_name." ". $user->last_name}}</h3>
                                    <p><i class="ti-info-alt text-danger"></i> Complete security setup for your account </p>
                                    <hr>
                                    <form class="form-horizontal auth-form" id="noLivewire" method="POST">
                                        @csrf

                                        <livewire:complete-setup-form/>
                                    </form>

                                </div>
                            </div><!--end card-body-->

                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end col-->
        </div><!--end row-->
    </div>
</x-auth>
