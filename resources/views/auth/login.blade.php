<x-auth>
    <x-slot name="title">
        Login
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
                                    <p class="text-muted  mb-0">Agency Portal</p>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class=" p-4">
                                    @error('error')
                                    <div class="alert icon-custom-alert alert-danger b-round-sm fade show"
                                         role="alert">
                                        <i class="ti-info-alt alert-icon"></i>
                                        <div class="alert-text">
                                            {{$message }}
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                    </div>
                                    @enderror
                                    <form class="form-horizontal auth-form" id="noLivewire" method="POST">
                                        @csrf
                                        <x-utils.form.input :key="'email'" :label="'Email Address'" :type="'email'"
                                                            :value="old('email')"/>
                                        <x-utils.form.input :key="'password'" :label="'Password'" :type="'password'"/>

                                        <div class="form-group row my-4">

                                            <div class="col text-end">

                                                @if (Route::has('password.request'))
                                                    <a class="font-13"
                                                       href="{{ route('password.request') }}">
                                                        <i class="dripicons-lock"></i> {{ __('Forgot Your Password?') }}
                                                    </a>
                                                @endif
                                            </div><!--end col-->
                                        </div><!--end form-group-->

                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <button class="btn btn-success w-100 waves-effect waves-light"
                                                        type="submit">{{ __('Sign In') }}
                                                </button>
                                            </div><!--end col-->
                                        </div> <!--end form-group-->
                                    </form><!--end form-->
                                    <div class="hidden-sm">

                                        <div x-data="{ open: true }">
                                            <hr class="hr-dashed hr-menu">
                                            <div class="notification-msg" x-show="open" x-transition>

                                                <div class="d-flex flex-row">
                                                    <div class="col-auto">
                                                        <img src="{{asset('images/merchant_ad.svg')}}"
                                                             class="img-fluid" style="width: 90px">
                                                    </div>
                                                    <div class="col">
                                                        <div style="margin-left: 10px">
                                                            <a href="#" @click="open = ! open"
                                                               class="float-end close-btn text-danger">
                                                                <i class="mdi mdi-close-circle font-18"></i>
                                                            </a>
                                                            <h5 class="mt-0">Become A Merchant</h5>
                                                            <p class="mb-3 font-11">Do you want to become a merchant and
                                                                earn
                                                                revenue as
                                                                you trade?</p>
                                                            <a href="javascript: void(0);"
                                                               class="btn btn-primary btn-sm">Learn More</a>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end card-body-->

                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end col-->
        </div><!--end row-->
    </div>

</x-auth>





