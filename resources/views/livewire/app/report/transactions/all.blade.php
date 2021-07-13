<x-slot name="title">
    Transactions
</x-slot>

<div class="page-content">
    <x-utils.actionbar :title="'Transactions'" wire:ignore.self>

        <x-slot name="action">
             <span  wire:target="$set" wire:loading
                   class="btn-spinner btn-spinner-soft-danger me-2"></span>
                <span class="d-md-none dropdown">
                        <button class="btn btn-soft-primary btn-sm me-1  dropdown-toggle" id="mobileWalletMenu"
                                data-bs-toggle="dropdown" aria-expanded="false">
                        <i
                            class="fas fa-ellipsis-v"></i>
                    </button>
                  <ul class="dropdown-menu" aria-labelledby="mobileWalletMenu">
                      <li><a class="dropdown-item" wire:click="export"><i
                                  class="far fa-file-excel me-2"></i>Export</a></li>
                      {{--                    <li><a class="dropdown-item" href="{{url('app/send/'.$wallet->id)}}"><i--}}
                      {{--                                class="fab fa-telegram-plane me-2"></i>Send</a></li>--}}
                  </ul>

                </span>
            <span class="hidden-sm">
                                <button class="btn btn-soft-success" wire:click="export"><i
                                        class="far fa-file-excel"></i> Export</button>


                </span>


            <x-utils.ui.filter-button/>

        </x-slot>
        <hr class="my-2">
        <div class="row my-3 mx-2">
            <div class="col-sm-12  {{user()->isMerchant() ? 'col-md-3' : 'col-md-3' }}">
                <x-utils.form.date-input :key="'date'" :label="'Select Date'" :js="''"/>
            </div>
            <div class="col-sm-12  {{user()->isMerchant() ? 'col-md-2' : 'col-md-3' }}">
                <x-utils.form.search-select :key="'status'" :js="''">
                    @foreach(statusList() as $stat)
                        <option value="{{$stat}}">{{$stat}}</option>
                    @endforeach
                </x-utils.form.search-select>
            </div>

            <div class="col-sm-12 {{user()->isMerchant() ? 'col-md-2' : 'col-md-3' }}">
                <x-utils.form.search-select :key="'service'" :js="''">
                    @foreach(serviceProductList() as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </x-utils.form.search-select>
            </div>
            <div class="col-sm-12  {{user()->isMerchant() ? 'col-md-2' : 'col-md-3' }}">
                <x-utils.form.search-select :key="'wallet'" :js="''" :class="'currency'">
                    @foreach($wallets as $wallet)
                        <option value="{{$wallet->id}}"
                                label="{{$wallet->currency->code}}">{{$wallet->currency->code}}</option>
                    @endforeach
                </x-utils.form.search-select>
            </div>
            @if(user()->isMerchant())
                <div class="col-sm-12  col-md-3">
                    <x-utils.form.search-select :key="'initiator'" :js="''" :label="'Initiator'">
                        @foreach(user()->merchant->users as $agent)
                            <option value="{{$agent->id}}"
                            >{{$agent->full_name}}</option>
                        @endforeach
                    </x-utils.form.search-select>
                </div>
            @endif
        </div>

    </x-utils.actionbar>
    <div class="container-fluid list-section" wire:key="main">
        <div class="row">
            <div class="col-12">
                <div class="mt-3 mx-1">
                    @if(!count($transactions))
                        <x-utils.empty>
                            <h5><i class="ti-info-alt text-danger"></i> No Transaction Found @if(empty($date))
                                    Today @endif </h5>
                        </x-utils.empty>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0 table-hover">
                                <thead>
                                <tr class="hidden-sm">
                                    <th>Reference</th>
                                    <th>Trans Type</th>
                                    <th>Wallet</th>
                                    <th>Amount</th>
                                    <th>Charge</th>
                                    <th>Commission</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    @if(user()->isMerchant())
                                        <th>Initiator</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($transactions as $trx)
                                    <tr style="cursor: pointer"
                                        onclick="window.location.href='{{url('app/report/transactions/view/'.$trx->reference)}}'"
                                        wire:key="list{{$loop->index}}"
                                    >
                                        <td>{{$trx->reference}}
                                            <span class="float-end d-block d-md-none">
                                                <span class="me-2"><small
                                                        class="text-muted font-10 fw-light">{{$trx->base_currency}}</small> <b>{{number_format($trx->amount,2)}}</b></span>
                                            <x-utils.ui.badge :title="$trx->status" :type="strtolower($trx->status)"
                                                              :mobile="''"/>

                                         <i class="ti-angle-right mx-1 mt-1"></i></span>
                                            <div class="d-block d-md-none">
                                                <div class=" d-flex flex-row">
                                                    <div class="col">
                                                        <small
                                                            class="text-muted font-10 fw-light">{{switchProducts($trx->product)}} <span class="text-dark">|</span> {{$trx->user == user()->first_name ? 'You' : $trx->user}}</small>
                                                    </div>
                                                    <div class="col-auto text-end">
                                                        <small
                                                            class="text-muted font-10 fw-light">{{formatDate($trx->created_at)}}</small>
                                                    </div>
                                                </div>

                                            </div>
                                        </td>

                                        <td class="hidden-sm">{{switchProducts($trx->product)}}</td>
                                        <td class="hidden-sm">{{$trx->base_currency}}</td>
                                        <td class="hidden-sm"><b>{{number_format($trx->amount, 2)}}</b></td>
                                        <td class="hidden-sm">{{number_format($trx->charges,2)}}</td>
                                        <td class="hidden-sm"><b>{{number_format($trx->commission,3)}}</b></td>
                                        <td class="hidden-sm">{{number_format($trx->total_amount,2)}}</td>
                                        <td class="hidden-sm">
                                            <x-utils.ui.badge :title="$trx->status" :type="strtolower($trx->status)"/>
                                        </td>
                                        <td class="hidden-sm">{{formatDate($trx->created_at)}}</td>
                                        @if(user()->isMerchant())
                                            <td class="hidden-sm">
                                                <b>{{$trx->user == user()->first_name ? 'You' : $trx->user}}</b></td>
                                        @endif
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
    <x-utils.ui.pagination :list="$transactions" :listName="'Transactions'"/>

</div>

@browser('isDesktop')
@push('scripts')
    <script>
        $(document).ready(function () {
            $('body').addClass('enlarge-menu');
        });
    </script>
@endpush
@endbrowser

@push('scripts')
    <script>
        window.addEventListener('set_wallets', event => {
            console.log(event.detail);
            $('#wallet').empty().trigger('change');
            var newOption = new Option('Select Wallet', "", false, false);
            $('#wallet').append(newOption).trigger('change');
            $.map(event.detail, function (val, key) {
                var newOption = new Option(val.currency.code, val.id, false, false);
                $('#wallet').append(newOption).trigger('change');
            })
        })
    </script>
@endpush
