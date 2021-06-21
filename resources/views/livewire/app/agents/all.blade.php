<x-slot name="title">
    Agents
</x-slot>
<x-slot name="action">
    @if(!empty($this->agents))
        <a class=" btn btn-md btn-soft-primary" href="{{url('/app/agents/add')}}" role="button"><i
                class="fas fa-plus me-2"></i>Add Agent</a>
    @endif
</x-slot>


<div class="container-fluid">
    {{--        <h1>Hello</h1>--}}
    @if(empty($this->agents))
        <x-utils.empty>
            <h5><i class="ti-info-alt text-danger"></i> No Agent Added Yet</h5>
            <a class=" btn btn-md btn-soft-primary" href="{{url('/app/agents/add')}}" role="button"><i
                    class="fas fa-plus me-2"></i>Add Agent</a>
        </x-utils.empty>


    @else


    @endif


</div>
