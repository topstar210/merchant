<div>
    <div class="form-group mb-3">
        <div class="input-group">
            <input type="text" id="pincode" name="pin" class="form-control" required>
            @error('pin')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>




    @push('styles')
        <link href="{{asset('plugins/pincode/pincode.css')}}" rel="stylesheet">
    @endpush
    @push('scripts')
        <script type="text/javascript" src="{{asset('plugins/pincode/pincode.js')}}"></script>
        <script>
            $('#pincode').pincodeInput({
                inputs: {{$pinLen}},
                placeholders: "- - - - - -",
                hidedigits: false,
                change: function (input, value, inputnumber) {

                },
                complete: function (value, e, errorElement) {
                    $('.auth-login').submit();
                }
            });
        </script>
    @endpush
</div>
