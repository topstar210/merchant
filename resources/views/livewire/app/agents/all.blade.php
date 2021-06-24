<x-slot name="title">
    Agents
</x-slot>

<div class="page-content">
    <x-utils.actionbar :title="'Agents'"  wire:ignore>
        @if(count($agents) || !empty($query))
            <x-slot name="action">
                <a class=" btn btn-md btn-soft-primary" href="{{url('/app/agents/add')}}" role="button"><i
                        class="fas fa-plus me-2"></i>Add Agent</a>
                <x-utils.ui.filter-button/>
            </x-slot>
            <hr class="my-2">
            <div class="row my-3 mx-2">
                <div class="col-sm-12 col-lg-3 col-md-4">
                    <x-utils.form.search-input :key="'query'" :label="'Enter Query'" :js="''"/>
                </div>
                <div class="col">
                    <a href="javascript:void(0)" x-on:click="toggle()"
                       class="float-end close-btn text-danger">
                        <i class="mdi mdi-close-circle font-18"></i>
                    </a>
                </div>
            </div>
        @endif
    </x-utils.actionbar>

    <div class="container-fluid list-section" wire:key="main">
        <div class="row">
            <div class="col-12">
                <div class="mt-3 mx-1">
                    @if(!count($agents) && empty($query))
                        <x-utils.empty>
                            <h5><i class="ti-info-alt text-danger"></i> No Agent Added Yet</h5>
                            <a class=" btn btn-md btn-primary" href="{{url('/app/agents/add')}}"
                               role="button"><i class="fas fa-plus me-2"></i>Add Agent</a>
                        </x-utils.empty>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0 table-hover">
                                <thead>
                                <tr class="hidden-sm">
                                    <th>Agent Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Account Number</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($agents as $agent)
                                    <tr style="cursor: pointer"
                                        onclick="window.location.href='{{url('app/agents/'.$agent->id)}}'" wire:key="list{{$loop->index}}"
                                    >
                                        <td>{{$agent->full_name}}
                                            <span class="float-end d-block d-md-none">
                                                @if(!$agent->reg_com)
                                                    <span
                                                        class="badge bg-warning menu-arrow">Invitation Sent</span>
                                                @elseif($agent->status == 'Active')
                                                    <span
                                                        class="badge bg-success menu-arrow">Active</span>
                                                @else
                                                    <span
                                                        class="badge bg-danger menu-arrow">Inactive</span>
                                                @endif
                                         <i class="ti-angle-right mx-1 mt-1"></i></span></td>
                                        <td class="hidden-sm">{{$agent->email}}</td>
                                        <td class="hidden-sm"><span
                                                class="flag-icon flag-icon-{{strtolower($agent->defaultCountry)}}"></span> {{$agent->formattedPhone}}
                                        </td>

                                        <td class="hidden-sm">{{strtoupper($agent->account_number)}}</td>
                                        <td class="hidden-sm">
                                            @if(!$agent->reg_com)
                                                <span
                                                    class="badge bg-warning menu-arrow">Invitation Sent</span>
                                            @elseif($agent->status == 'Active')
                                                <span
                                                    class="badge bg-success menu-arrow">Active</span>
                                            @else
                                                <span
                                                    class="badge bg-danger menu-arrow">Inactive</span>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table><!--end /table-->
                        </div>
                    @endif
                </div>
            </div>


        </div>
    </div>
    <x-utils.ui.pagination :list="$agents" :listName="'Agents'"/>
</div>
@if (session()->has('error'))
    @push('scripts')

        <script>

            var error = parseInt('{{session('error')}}');
            Swal.fire(
                {
                    icon: error ? "error" : "success",
                    title: error ? "Error" : "Success",
                    text: "{{session('error_message')}}",
                }
            );
        </script>
    @endpush
@endif

