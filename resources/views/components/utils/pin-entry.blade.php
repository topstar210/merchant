<div>
    <div class="form-group mb-3">
        <div class="input-group">
            <div wire:ignore>
                <input type="text" id="pincode" name="pin" class="form-control" disabled required>
            </div>
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
    @endpush
</div>
