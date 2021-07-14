<x-email>
    Dear {{$user->full_name}}<br/><br/>

    You are receiving this email because we received a password reset request for your account.<br><br>
    Kindly click on the link below to reset your password.<br/><br/>

    <a href="{{$url}}">{{$url}}</a><br/><br/>
    This password reset link will expire in 60 minutes.<br/><br/>

    Kindly ignore if you believe this mail is not intended for you
</x-email>
