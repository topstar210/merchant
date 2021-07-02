<x-slot name="title">
    Agents
</x-slot>

<div class="page-content">
    <x-utils.actionbar :title="'Agents'" wire:ignore>
        @if(count($agents) || !empty($query))
            <x-slot name="action">
                @can('isMerchant', \App\Models\Permission::class)
                    <a class=" btn @mobile btn-sm @endmobile btn-soft-primary" href="{{url('/app/agents/add')}}" role="button"><i
                            class="fas fa-plus me-1"></i>Add Agent</a>
                @endcan
                <x-utils.ui.filter-button/>
            </x-slot>
            <hr class="my-2">
            <div class="row my-3 mx-2">
                <div class="col-12 col-lg-3 col-md-4">
                    <x-utils.form.search-input :key="'query'" :label="'Enter Query'" :js="''"/>
                </div>
            </div>
        @endif
    </x-utils.actionbar>

    <div class="container-fluid list-section" wire:key="main">
        <div class="row">
            <div class="col-12">
                <div class="mt-3 mx-1">
                    @if(!count($agents))
                        <x-utils.empty>
                            <h5><i class="ti-info-alt text-danger"></i> No Agent Found</h5>
                            @can('isMerchant', \App\Models\Permission::class)
                                @if(empty($query))
                                    <a class=" btn btn-md btn-primary" href="{{url('/app/agents/add')}}"
                                       role="button"><i class="fas fa-plus me-2"></i>Add Agent</a>
                                @endif
                            @endcan
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
                                        onclick="window.location.href='{{url('app/agents/'.$agent->id)}}'"
                                        wire:key="list{{$loop->index}}"
                                    >
                                        <td>{{$agent->full_name}}
                                            <span class="float-end d-block d-md-none">
                                                 @if(!$agent->reg_com)
                                                    <x-utils.ui.badge :title="'Invitation Sent'" :type="'pending'"/>
                                                @elseif($agent->status == 'Active')
                                                    <x-utils.ui.badge :title="'Active'" :type="'success'"/>
                                                @else
                                                    <x-utils.ui.badge :title="'Inactive'" :type="'failed'"/>
                                                @endif
                                         <i class="ti-angle-right mx-1 mt-1"></i></span></td>
                                        <td class="hidden-sm">{{$agent->email}}</td>
                                        <td class="hidden-sm"><span
                                                class="flag-icon flag-icon-{{strtolower($agent->defaultCountry)}}"></span> {{$agent->formattedPhone}}
                                        </td>

                                        <td class="hidden-sm">{{strtoupper($agent->account_number)}}</td>
                                        <td class="hidden-sm">
                                            @if(!$agent->reg_com)
                                                <x-utils.ui.badge :title="'Invitation Sent'" :type="'pending'"/>
                                            @elseif($agent->status == 'Active')
                                                <x-utils.ui.badge :title="'Active'" :type="'success'"/>
                                            @else
                                                <x-utils.ui.badge :title="'Inactive'" :type="'failed'"/>
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


