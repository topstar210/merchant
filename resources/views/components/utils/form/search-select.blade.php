<div class="form-group search-group" wire:ignore
     x-data="{}"
     x-init="()=>{
                var type = '{{$class ?? 'select2'}}'
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
}">
    <select x-ref="select" class="form-control mb-3 custom-select"
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
