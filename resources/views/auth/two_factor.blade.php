<x-auth>
    <x-slot name="title">
        Login Authorization
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
                                    <p class="text-muted  mb-0">Login Authorization</p>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class=" p-4">
{{--                                    @if($errors->any())--}}
{{--                                        <div--}}
{{--                                            class="alert icon-custom-alert  alert-danger b-round-sm fade show"--}}
{{--                                            role="alert">--}}
{{--                                            <i class="ti-info-alt alert-icon"></i>--}}
{{--                                            <div class="alert-text">--}}
{{--                                                {{$errors->first() }}--}}
{{--                                            </div>--}}
{{--                                            <button type="button" class="btn-close" data-bs-dismiss="alert"--}}
{{--                                                    aria-label="Close"></button>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
                                    <h3>Hello, {{user()->first_name." ". user()->last_name}}</h3>
                                    <p><i class="ti-info-alt text-danger"></i> Enter <b>Authorization Token</b> sent to
                                        your email to
                                        continue</p>
                                    <hr>
                                    <form class="form-horizontal auth-login" method="POST">
                                        @csrf
                                        <x-utils.pin-entry/>
                                        <input class="d-none" name="pin" id="pin">
                                    </form>
                                    <livewire:token-resend-button/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $('#pincode').pincodeInput({
                inputs: 6,
                placeholders: "- - - - - -",
                hidedigits: false,
                change: function (input, value, inputnumber) {

                },
                complete: function (value, e, errorElement) {
                    $('#pin').val(value);
                    $('.auth-login').submit();
                }
            });
        </script>
    @endpush
</x-auth>
