<div class="actionbar">
    <div class="row">
        @if(isset($showBack))
        <div class="col-auto align-self-center">
                <a class="text-secondary" href="#" onclick="window.history.back()"><i data-feather="arrow-left"></i></a>
        </div>
        @endif
        <div class="col align-self-center">
            <h4>{{$title}}</h4>
        </div>
        <div class="col-auto align-self-center">
            @if(isset($action))
                {{ $action }}
            @endif
        </div>
    </div>

</div>
