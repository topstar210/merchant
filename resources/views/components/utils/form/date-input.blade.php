<div class="form-group search-group"
     x-data="{value: @entangle('date')}"
     x-init="new Pikaday({ field: $refs.input, format: 'YYYY-MM-DD' })"
     x-on:change="value = $event.target.value"
>

    <input
        x-ref="input"
        x-bind:value="value"
        type="text"
        autocomplete="off"
        id="{{$key}}"
        class="form-control form-control-md"
        placeholder="{{$label ?? \Str::of($key)->snake()->replace('_', ' ')->title()}}"
        name="{{$key}}"
    >
</div>

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/pikaday/pikaday.css')}}">
@endpush
@push('scripts')
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/pikaday/pikaday.js')}}"></script>
@endpush
