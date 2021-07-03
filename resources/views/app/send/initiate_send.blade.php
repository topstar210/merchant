<x-app>
    <x-slot name="title">
        Send Money
    </x-slot>
    <div class="page-content">
        <x-utils.actionbar :title="'Send from: '.$wallet->currency->code.' Wallet'" :showBack="''"/>

        <div class="container-fluid app-main">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-4 mx-auto">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6><i class="ti-info-alt text-info"></i> Choose Send Option
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="accordion" id="sendAccordion">
                                <livewire:app.send.options.send-wallet :wallet="$wallet"/>
                                <livewire:app.send.options.send-account :wallet="$wallet"/>
                                <livewire:app.send.options.send-bank :wallet="$wallet"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app>
