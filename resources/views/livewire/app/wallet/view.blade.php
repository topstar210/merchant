<x-slot name="title">
    Wallet
</x-slot>

<div class="page-content">
    <x-utils.actionbar :title="$wallet->currency->code.' '.number_format($wallet->balance, 2)" :showBack="''"
                       wire:ignore.self>

        <x-slot name="action">
 <span  wire:target="$set" wire:loading
        class="btn-spinner btn-spinner-soft-danger me-2"></span>
            @if($wallet->lock)
                <button class="btn btn-soft-danger @mobile btn-sm @endmobile" disabled style="opacity: 1 !important;"><i
                        class="ti-lock"></i> Locked
                </button>
            @else
                <span class="d-md-none dropdown">
                        <button class="btn btn-soft-primary btn-sm me-1  dropdown-toggle" id="mobileWalletMenu"
                                data-bs-toggle="dropdown" aria-expanded="false">
                        <i
                            class="fas fa-ellipsis-v"></i>
                    </button>
                  <ul class="dropdown-menu" aria-labelledby="mobileWalletMenu">
                      <li class=" text-center mt-2"><small class="text-muted font-10">Commission Earned<br>{{$wallet->currency->code}} <b class="font-13 text-success">{{number_format($wallet->commission, 2)}}</b></small><hr></li>
                    <li><a class="dropdown-item" href="{{url('app/wallet/'.$wallet->id.'/deposit')}}"><i
                                class="fas fa-plus me-2"></i>Deposit</a></li>
                    <li><a class="dropdown-item" href="{{url('app/send/'.$wallet->id)}}"><i
                                class="fab fa-telegram-plane me-2"></i>Send</a></li>
                  </ul>

                </span>
                <span class="hidden-sm">
                      <button class="btn btn-soft-secondary @mobile btn-sm @endmobile" disabled
                              style="opacity: 1 !important;"> Commission: {{$wallet->currency->code}} <b
                              class="text-success">{{number_format($wallet->commission, 2)}}</b>
            </button>
                    <button class="btn btn-soft-success"
                            onclick="window.location.href='{{url('app/wallet/'.$wallet->id.'/deposit')}}'"
                            role="button"><i
                            class="fas fa-plus me-1"></i>Deposit
                    </button>
                    @can('owner', $wallet)
                        @can('shouldSend', App\Models\Permission::class)
                            <button class="btn btn-soft-primary"
                                    onclick="window.location.href='{{url('app/send/'.$wallet->id)}}'"
                                    role="button"><i
                                    class="fab fa-telegram-plane me-1"></i>Send
                        </button>
                        @endcan
                    @endcan
                </span>
            @endif
            @if(count($transactions))
                <x-utils.ui.filter-button/>
            @endif
        </x-slot>
        <hr class="my-2">
        <div class="row my-3 mx-2">
            <div class="col-sm-12 col-lg-3 col-md-4">
                <x-utils.form.date-input :key="'date'" :label="'Select Date'" :js="''"/>
            </div>
            <div class="col-sm-12 col-lg-3 col-md-4">
                <x-utils.form.search-select :key="'status'" :js="''">
                    @foreach(statusList() as $stat)
                        <option value="{{$stat}}">{{$stat}}</option>
                    @endforeach
                </x-utils.form.search-select>
            </div>
        </div>

    </x-utils.actionbar>

    <div class="container-fluid list-section" wire:key="main">
        <div class="row">
            <div class="col-12">
                <div class="mt-3 mx-1">
                    @if(!count($transactions))
                        <x-utils.empty>
                            <h5><i class="ti-info-alt text-danger"></i> No Wallet Deposit Found</h5>
                        </x-utils.empty>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0 table-hover">
                                <thead>
                                <tr class="hidden-sm">
                                    <th>Reference</th>
                                    <th>Amount <small class="text-muted font-10 fw-light">({{$wallet->currency->code}}
                                            )</small></th>
                                    <th>Charge <small class="text-muted font-10 fw-light">({{$wallet->currency->code}}
                                            )</small></th>
                                    <th>Bal. Before <small
                                            class="text-muted font-10 fw-light">({{$wallet->currency->code}})</small>
                                    </th>
                                    <th>Deposited <small
                                            class="text-muted font-10 fw-light">({{$wallet->currency->code}})</small>
                                    </th>
                                    <th>Bal. After <small
                                            class="text-muted font-10 fw-light">({{$wallet->currency->code}})</small>
                                    </th>
                                    <th>Status</th>
                                    <th>Date</th>
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
                                                        class="text-muted font-10 fw-light">{{$wallet->currency->code}}</small> <b>{{number_format($trx->amount,2)}}</b></span>
                                            <x-utils.ui.badge :title="$trx->status" :type="strtolower($trx->status)"
                                                              :mobile="''"/>

                                         <i class="ti-angle-right mx-1 mt-1"></i></span>
                                            <div class="d-block d-md-none">
                                                <div class=" d-flex flex-row">

                                                    <div class="col-auto text-end">
                                                        <small
                                                            class="text-muted font-10 fw-light">{{formatDate($trx->created_at)}} <span class="text-dark">|</span> {{$trx->user == user()->first_name ? 'You' : $trx->user}} <span class="text-dark">|</span> {{switchProducts($trx->product)}}</small>
                                                    </div>
                                                    <div class="col">
                                                    </div>
                                                </div>

                                            </div>
                                        </td>
                                        <td class="hidden-sm">{{number_format($trx->total_amount, 2)}}</td>
                                        <td class="hidden-sm">{{number_format($trx->charges,2)}}</td>
                                        <td class="hidden-sm">{{number_format($trx->balance_before,2)}}</td>
                                        <td class="hidden-sm"><b>{{number_format($trx->amount,2)}}</b></td>
                                        <td class="hidden-sm">{{number_format($trx->balance_after,2)}}</td>
                                        <td class="hidden-sm">
                                            <x-utils.ui.badge :title="$trx->status" :type="strtolower($trx->status)"/>
                                        </td>
                                        <td class="hidden-sm">{{formatDate($trx->created_at)}}</td>
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
