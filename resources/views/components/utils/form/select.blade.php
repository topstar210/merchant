<div class="form-group mb-3">
    <label class="form-label" for="{{$key}}">{{$label ?? \Str::of($key)->snake()->replace('_', ' ')->title()}}</label>
    <div class="input-group">
        {{--        <div class="w-100" wire:ignore>--}}
        {{--            <select class="{{$class ?? 'select2'}} form-control mb-3 @error($key) is-invalid @enderror custom-select"--}}
        {{--                    id="{{$key}}"--}}
        {{--                    name="{{$key}}" @if(isset($js)) wire:model="{{$key}}" @endif style="width: 100%;" required>--}}
        {{--                <option value="">Select</option>--}}
        {{--                {{$slot}}--}}
        {{--            </select>--}}

        <div
            x-data="{}"
            x-init="()=>{
                var type = '{{$class ?? 'select2'}}';
 if (type === 'select2') {
        select2 = $($refs.select).select2();
    } else if (type === 'country') {
        select2 = $($refs.select).select2({
        templateResult: countryState,
        templateSelection: countrySelectState
        });
        } else if (type === 'currency') {
        select2 = $($refs.select).select2({
        templateResult: currencyState,
        templateSelection: currencySelectState
        });
        }
}" wire:ignore
            class="w-100">
            <select x-ref="select" id="{{$key}}" name="{{$key}}"
                    @if(isset($js)) wire:model="{{$key}}" @endif
                    class="form-control mb-3 @error($key) is-invalid @enderror custom-select" style="width: 100%;"
                    @if(!isset($not_required)) required @endif>
                <option value="">Select</option>
                {{$slot}}
            </select>

        </div>
        {{--        </div>--}}
        @error($key)
        <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

    </div>

</div>

