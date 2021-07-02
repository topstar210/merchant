<div class="form-group search-group">
    <input id="{{$key}}" type="{{$type ?? 'text'}}" @if(isset($js))@if($js == 'lazy') wire:model.lazy="{{$key}}"
           @elseif($js == 'defer') wire:model.defer="{{$key}}" @else wire:model="{{$key}}" @endif @endif
           class="form-control form-control-md @error($key) is-invalid @enderror"
           placeholder="{{$label ?? \Str::of($key)->snake()->replace('_', ' ')->title()}}"
           name="{{$key}}" value="{{$value ?? null}}" autocomplete="off"
    >
</div>
