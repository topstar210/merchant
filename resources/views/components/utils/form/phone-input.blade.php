<div class="form-group mb-3">
    <label class="form-label" for="{{$key}}">{{$label ?? \Str::of($key)->snake()->replace('_', ' ')->title()}}</label>
    <div class="input-group phone">
        <div
            x-data="{}"
            x-init="()=>{
        select2 = $($refs.select).select2({
            templateResult: phoneState,
            templateSelection: phoneSelectState
        });

}" wire:ignore
        >
            <select x-ref="select" class="form-control mb-3 @error($key."_code") is-invalid @enderror custom-select"
                    id="{{$key}}_code"
                    name="{{$key}}_code" @if(isset($disabled)) disabled
                    @endif  @if(isset($js)) wire:model="{{$key}}_code" @endif style="width: 100px;" required>
                {{$slot}}
            </select>
        </div>
        <input id="{{$key}}" type="number" @if(isset($js))@if($js == 'lazy') wire:model.lazy="{{$key}}"
               @elseif($js == 'defer') wire:model.defer="{{$key}}" @else wire:model="{{$key}}" @endif @endif
               class="form-control @error($key) is-invalid @enderror"
               name="{{$key}}" value="{{$value ?? null}}"
               required>
        @error($key)
        <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

</div>
