<x-slot name="title">
    {{$wallet->currency->code}} <b class="text-dark font-18">{{number_format($wallet->balance, 2)}}</b>
</x-slot>

<div class="page-content">
    <x-utils.actionbar :title="$wallet->currency->code.' '.number_format($wallet->balance, 2)" :showBack="''"  wire:ignore>

            <x-slot name="action">
                <a class=" btn btn-md btn-soft-success" href="{{url('/app/agents/add')}}" role="button"><i
                        class="fas fa-plus me-2"></i>Fund Wallet</a>
                <x-utils.ui.filter-button/>
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


</div>
