<x-email>
    Dear {{$user->full_name}}<br/><br/>

    Welcome onboard IMO Rapid Transfer Agency platform, where you earn as you transact.<br/><br/>

    A merchant account (<b>{{$merchant->merchant_name}}</b>) has been created for you. Kindly click on the link below to
    complete your merchant account setup.<br/><br/>

    <a href="{{$url}}">{{$url}}</a><br/><br/>

    Do not share link above with anyone else.

</x-email>
