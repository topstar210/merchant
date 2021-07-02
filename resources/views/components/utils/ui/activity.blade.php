<div class="">
    <p class="font-12 mb-0">{{$activity->activity}}</p>
    <div class="d-flex flex-row">
        <div class="col">
            <small class="text-muted font-10">{{$browser->platformName()}} - {{$browser->browserName()}}</small>
        </div>
        <div class="col-auto">
            <small
                class="text-muted font-10 text-end">{{formatDate($activity->created_at)}}</small>
        </div>
    </div>
</div>

