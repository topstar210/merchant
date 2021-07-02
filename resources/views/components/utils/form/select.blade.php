<div class="form-group mb-3">
    <label class="form-label" for="{{$key}}">{{$label ?? \Str::of($key)->snake()->replace('_', ' ')->title()}}</label>
    <div class="input-group">
        <div class="w-100" wire:ignore>
            <select class="{{$class ?? 'select2'}} form-control mb-3 @error($key) is-invalid @enderror custom-select"
                    id="{{$key}}"
                    name="{{$key}}" @if(isset($js)) wire:model="{{$key}}" @endif style="width: 100%;" required>
                <option value="">Select</option>
                {{$slot}}
            </select>
        </div>
        @error($key)
        <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

    </div>

</div>
