<div class="actionbar" wire:key="actionBar"
     x-data="{ open : false, toggle() { this.open = ! this.open }}" {{$attributes}}>
    <div class="row mx-2 mx-md-3">
        <div class="col overflow-hidden text-truncate text-nowrap align-self-center">
            <h4>@if(isset($showBack))
                    <a  wire:ignore class="text-secondary" href="javascript:void(0)" onclick="window.history.back()"><i
                            data-feather="arrow-left"></i></a>
                    @endif {{ $title }}</span></h4>
        </div>
        <div class="col-auto text-md-end align-self-center">
            @if(isset($action))
                {{ $action }}
            @endif
        </div>
    </div>
    <div x-show="open" x-cloak x-transition>
        {{$slot}}
    </div>
</div>
