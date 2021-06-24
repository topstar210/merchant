<div class="form-group mb-3">
    <label class="form-label" for="{{$key}}">{{$label ?? \Str::of($key)->snake()->replace('_', ' ')->title()}}</label>
    <div class="input-group @error($key) is-invalid @enderror">
        @foreach($options as $option)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="{{$key}}" id="{{$key}}-{{$option}}" wire:model="{{$key}}"
                       value="{{$option}}">
                <label class="form-check-label" for="{{$key}}-{{$option}}">{{\Str::of($option)->snake()->replace('_', ' ')->title() }}</label>
            </div>
        @endforeach
            @error($key)
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
    </div>

</div>
