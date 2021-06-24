<div class="actionbar" wire:key="actionBar" x-data="{ open : false, toggle() { this.open = ! this.open }}">
    <div class="row  mx-3">
        @if(isset($showBack))
                <div class="col-auto align-self-center">
                    <a class="text-secondary" href="javascript:void(0)" onclick="window.history.back()"><i
                            data-feather="arrow-left"></i></a>
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
    <div x-show="open" x-cloak @click.away="toggle()" x-transition>
        {{$slot}}
    </div>
</div>
