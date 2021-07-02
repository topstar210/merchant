<div class="form-group mb-3">
    <label class="form-label" for="{{$key}}">{{$label ?? \Str::of($key)->snake()->replace('_', ' ')->title()}}</label>
    <div class="input-group">
        <input id="{{$key}}" type="{{$type ?? 'text'}}" @if(isset($js))@if($js == 'lazy') wire:model.lazy="{{$key}}"
               @elseif($js == 'defer') wire:model.defer="{{$key}}" @else wire:model="{{$key}}" @endif @endif
               class="form-control @error($key) is-invalid @enderror text-end font-20"
               name="{{$key}}" value="{{$value ?? null}}" autocomplete="off"
               required>

        @error($key)
        <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
