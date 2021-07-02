<div class="form-group search-group" wire:ignore>
    <select class="select2 form-control mb-3 custom-select"
            id="{{$key}}"
            name="{{$key}}"
            @if(isset($js)) wire:model="{{$key}}" @endif style="width: 100%;" required>
        <option value="">Select {{ucfirst($key)}}</option>
        {{$slot}}
    </select>
</div>
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#{{$key}}').on('change', function (e) {
            @this.set('{{$key}}', e.target.value);
            });
        });
    </script>
@endpush
