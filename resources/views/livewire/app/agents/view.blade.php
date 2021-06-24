<x-slot name="title">
    View Agent
</x-slot>

<div class="page-content">
    <x-utils.actionbar :title="$agent->full_name" :showBack="''">
        {{--        <x-slot name="action">--}}
        {{--            @if(count($agents))--}}
        {{--                <a class=" btn btn-md btn-soft-primary" href="{{url('/app/agents/add')}}" role="button"><i--}}
        {{--                        class="fas fa-plus me-2"></i>Add Agent</a>--}}
        {{--            @endif--}}
        {{--        </x-slot>--}}
    </x-utils.actionbar>


</div>
