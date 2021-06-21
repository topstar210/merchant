<div class="form-group mb-3">
    <label class="form-label" for="{{$key}}">{{$label ?? \Str::of($key)->snake()->replace('_', ' ')->title()}}</label>
    <div class="input-group phone">
        <select class="phone_country form-control mb-3 @error($key."_country") is-invalid @enderror custom-select"
                id="{{$key}}_country"
                name="{{$key}}_country" @if(isset($js)) wire:model="{{$key}}_country" @endif style="width: 100px;" required>
            {{$slot}}
        </select>
        <input id="{{$key}}" type="number" @if(isset($js))@if($js == 'lazy') wire:model.lazy="{{$key}}"
               @else wire:model="{{$key}}" @endif @endif
               class="form-control @error($key) is-invalid @enderror"
               name="{{$key}}" value="{{$value ?? null}}"
               required>
    </div>
    @error($key)
    <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
    @enderror
</div>
