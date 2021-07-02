<x-slot name="title">
    Wallet Deposit
</x-slot>

<div class="page-content">
    <x-utils.actionbar :title="'Wallet Deposit'" :showBack="'true'" wire:ignore/>
    <div class="container-fluid app-main">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-4 mx-auto">
                <div class="card mt-4">
                    <div class="card-header">
                        <h6><i class="ti-info-alt text-info"></i> Deposit Funds Into Your <span
                                class="badge badge-soft-primary"><span
                                    class="font-13 flag-icon flag-icon-{{$wallet->currency->code == 'EUR' ? 'eu' : strtolower(substr($wallet->currency->code, 0,2))}}"></span> {{$wallet->currency->code}} Wallet</span>
                        </h6>
                    </div>
                    <div class="card-body">

                        @if(count($routes))
                            <form wire:submit.prevent="initiateDeposit">
                                <x-utils.form.amount-input :key="'amount'" :type="'number'" :js="''"
                                                           :currency="$wallet->currency->code"/>


                                <x-utils.form.select :key="'route'" :js="''" :label="'Payment Option'">
                                    @foreach($routes as $route)
                                        <option
                                            value="{{$route->id}}" {{strtolower($route->id) == strtolower($route) ? 'selected' : '' }}>{{switchRouteName($route->payment_method->name)[0]}}</option>
                                    @endforeach
                                </x-utils.form.select>

                                <hr class="hr-dashed hr-menu">
                                <div class="row">
                                    <div class="col-5">

                                        <small class="font-10">Charges</small>
                                        <h4 class="mt-0"><small
                                                class="text-muted font-10 fw-light">{{$wallet->currency->code}}
                                            </small> {{number_format($charge,2)}}
                                        </h4>
                                    </div>

                                    <div class="col-7 text-end">
                                        <div class="bg-soft-success px-2 py-1" style="border-radius: 7px; box-shadow: 1px 5px 3px -2px rgb(0 0 0 / 10%)">
                                            <small class="font-10">Total</small>
                                            <h4 class="mt-0"><small
                                                    class="text-muted font-10 fw-light">{{$wallet->currency->code}}
                                                </small> {{number_format($total,2)}}
                                            </h4>
                                        </div>

                                    </div>
                                </div>


                                <hr class="hr-dashed hr-menu">

                                <button class="btn btn-success w-100" type="submit" @if($errors->any()) disabled
                                        @endif wire:target="initiateDeposit"
                                        wire:loading.attr="disabled"><span
                                        wire:target="initiateDeposit" wire:loading class="btn-spinner"></span> Continue
                                </button>
                            </form>
                        @else
                            <x-utils.empty :noFooter="''">
                                <div class="card bg-soft-warning" style="margin-bottom: 30%">
                                    <div class="card-body">
                                        <h6><i class="ti-info-alt text-danger"></i> No payment
                                            option available for this currency. Try again soon</h6>
                                    </div>
                                </div>
                            </x-utils.empty>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#route').on('change', function (e) {
            @this.call('setRoute', e.target.value);
            });
        });
    </script>
@endpush
