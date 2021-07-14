<x-auth>
    <x-slot name="title">
        Reset Password
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
                                    <form method="POST" class="form-horizontal auth-form" id="noLivewire"
                                          action="{{ route('password.update') }}">
                                        @csrf

                                        <input type="hidden" name="token" value="{{ $token }}">
                                        <input id="email" type="hidden" name="email"
                                               value="{{ $email ?? old('emails') }}">
                                        <x-utils.form.input :key="'password'" :label="__('New Password')" :type="'password'"
                                                            :js="''"/>
                                        <x-utils.form.input :key="'password_confirmation'"
                                                            :label="__('Confirm Password')" :type="'password'"
                                                            :js="''"/>
                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <button class="btn btn-success w-100 waves-effect waves-light"
                                                        type="submit">{{ __('Reset Password') }}
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
