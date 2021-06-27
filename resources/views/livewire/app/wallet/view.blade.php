<x-slot name="title">
    Wallet
</x-slot>

<div class="page-content">
    <x-utils.actionbar :title="$wallet->currency->code.' '.number_format($wallet->balance, 2)" :showBack="''"
                       wire:ignore>

        <x-slot name="action">
            <a class=" btn btn-md btn-soft-success" href="{{url('/app/agents/add')}}" role="button"><i
                    class="fas fa-plus me-2"></i>Fund Wallet</a>
            @if(count($transactions))
                <x-utils.ui.filter-button/>
            @endif
        </x-slot>
        {{--            <hr class="my-2">--}}
        {{--            <div class="row my-3 mx-2">--}}
        {{--                <div class="col-sm-12 col-lg-3 col-md-4">--}}
        {{--                    <x-utils.form.search-input :key="'query'" :label="'Enter Query'" :js="''"/>--}}
        {{--                </div>--}}
        {{--                <div class="col">--}}
        {{--                    <a href="javascript:void(0)" x-on:click="toggle()"--}}
        {{--                       class="float-end close-btn text-danger">--}}
        {{--                        <i class="mdi mdi-close-circle font-18"></i>--}}
        {{--                    </a>--}}
        {{--                </div>--}}
        {{--            </div>--}}

    </x-utils.actionbar>

    <div class="container-fluid list-section" wire:key="main">
        <div class="row">
            <div class="col-12">
                <div class="mt-3 mx-1">
                    @if(!count($transactions) && empty($date))
                        <x-utils.empty>
                            <h5><i class="ti-info-alt text-danger"></i> No Wallet Deposit Found</h5>
                        </x-utils.empty>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0 table-hover">
                                <thead>
                                <tr class="hidden-sm">
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($transactions as $trx)
                                    <tr style="cursor: pointer"
                                        onclick="window.location.href='{{url('app/agents/'.$trx->id)}}'"
                                        wire:key="list{{$loop->index}}"
                                    >

                                        <td class="hidden-sm">{{$trx->amount}}</td>
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
    <x-utils.ui.pagination :list="$transactions" :listName="'Deposit'"/>

</div>
