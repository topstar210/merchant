@if(isset($mobile))
    <span><i class="fas fa-circle @switch($type)
        @case('success')
            text-success
@break

        @case('failed')
            text-danger
@break

        @case('refund')
            text-purple
@break

        @case('blocked')
            text-dark
@break

        @case('pending')
            text-warning
@break
        @default
            text-danger
@endswitch">
        </i></span>
@else
    <span class="badge {{$class ?? ''}}  menu-arrow @switch($type)
    @case('success')
        bg-success
        @break

    @case('failed')
        bg-danger
        @break

    @case('refund')
        bg-purple
        @break

    @case('blocked')
        bg-dark
        @break

    @case('pending')
        bg-warning
        @break
    @default
        bg-danger
@endswitch">{{\Str::title($title)}}</span>
@endif
