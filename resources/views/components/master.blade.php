<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description"/>
    <meta content="" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    {{--    <!-- CSRF Token -->--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{$title .' | Imo Rapid Transfer Agent' }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/select2/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">
    @stack('styles')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css"/>

    @livewireStyles
</head>
<body class="{{$body}}">
{{$slot}}

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/waves.js') }}"></script>
<script src="{{ asset('js/feather.min.js') }}"></script>
<script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{ asset('js/simplebar.min.js') }}"></script>
<script defer src="{{asset('plugins/alpine/alpine.2.8.min.js')}}"></script>

<script src="{{asset('plugins/select2/select2.min.js')}}"></script>


@livewireScripts
@stack('scripts')
<script src="{{ asset('js/custom.js') }}"></script>


</body>
</html>
