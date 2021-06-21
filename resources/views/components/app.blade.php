<x-master>
    <x-slot name="title">
        {{$title}}
    </x-slot>
    <x-slot name="body">
        dark-sidenav
    </x-slot>
    <x-utils.left-side/>
    <div class="page-wrapper">
        <x-utils.topbar/>
        <div class="page-content">
            <x-utils.actionbar :title="$title ?? null" :action="$action ?? null" :showBack="$showBack ?? null"/>

            {{ $slot }}
        </div>
    </div>

    @push('styles')
        <link href="{{asset('css/metisMenu.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('plugins/select2/select2.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('plugins/flagicon/css/flag-icon.min.css')}}" rel="stylesheet" type="text/css"/>
    @endpush
    @push('scripts')
        <script src="{{asset('js/metismenu.min.js')}}"></script>
        <script src="{{asset('plugins/select2/select2.min.js')}}"></script>
        <script src="{{asset('js/moment.js')}}"></script>
        <script src="{{asset('js/app.custom.js')}}"></script>
    @endpush
</x-master>
