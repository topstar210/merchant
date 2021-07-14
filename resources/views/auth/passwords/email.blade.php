<x-auth>
    <x-slot name="title">
        Forgot Password
    </x-slot>
    <div class="container">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="row">
                    <div class="col-lg-4 mx-auto auth-main">
                        <div class="card">
                            <div class="card-body p-0 auth-header-box">
                                <div class="text-center p-3">
                                    <a href="index.html" class="logo logo-admin">
                                        <img src="{{asset('images/default-logo.png')}}" height="50" alt="logo"
                                             class="auth-logo">
                                    </a>
                                    <h4 class="mt-3 mb-1 fw-semibold text-white font-18">IMO Rapid Transfer</h4>
                                    <p class="text-muted  mb-0">Reset Password</p>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class=" p-4">
                                    @if (session('status'))
                                        <div class="alert icon-custom-alert alert-success b-round-sm fade show"
                                             role="alert">
                                            <i class="ti-info-alt alert-icon"></i>
                                            <div class="alert-text font-12">
                                                {{ session('status') }}
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                        </div>
                                    @endif


                                    <form class="form-horizontal auth-form" action="{{ url('password/email') }}"
                                          id="noLivewire" method="POST">
                                        @csrf
                                        <x-utils.form.input :key="'email'" :label="'Email Address'" :type="'email'"
                                                            :value="old('email')"/>
                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <button class="btn btn-success w-100 waves-effect waves-light"
                                                        type="submit">{{ __('Send Password Reset Link') }}
                                                </button>
                                            </div><!--end col-->
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-auth>
