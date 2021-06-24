<div class="form-group mb-3">
    <label class="form-label" for="{{$key}}">{{$label ?? \Str::of($key)->snake()->replace('_', ' ')->title()}}</label>
    <div class="input-group">
        <textarea id="{{$key}}" class="form-control  @error($key) is-invalid @enderror" rows="{{$rows ?? 2}}"
                  @if(isset($js))@if($js == 'lazy') wire:model.lazy="{{$key}}"
                  @elseif($js == 'defer') wire:model.defer="{{$key}}"
                  @else wire:model="{{$key}}" @endif @endif  name="{{$key}}" required
        >{{$value ?? null}}</textarea>

        @error($key)
        <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
