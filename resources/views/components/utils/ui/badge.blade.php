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
