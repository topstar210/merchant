<x-email>
    Dear {{$user->full_name}}<br/><br/>

    You have been invited to be an agent for <b>{{$merchant->merchant_name}}</b>. Click on the link below to accept
    invitation and to complete your account setup on IMO Rapid Transfer.<br/><br/>
    <a href="{{$url}}">{{$url}}</a> <br/><br/>
    Kindly ignore if you believe this mail is not intended for you or you do not want to accept invitations.
</x-email>
