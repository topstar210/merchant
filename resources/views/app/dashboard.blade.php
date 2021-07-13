<x-app>
    <x-slot name="title">
        Dashboard
    </x-slot>
    <div class="page-content">
        <x-utils.actionbar :title="'Dashboard'"/>

        <div class="position-relative">
            <div class="dashboard-converter">

            </div>
            <div class="container-fluid app-main" style="padding-top: 70px">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-11 mx-auto converter">
                        <livewire:app.dashboard.converter/>
                    </div>
                </div>
                <div class="row d-flex justify-content-center mt-4 mx-auto">
                    <div class="col-lg-3 ">
                        <livewire:app.dashboard.rates/>
                    </div>

                    <div class="col-lg-4">
                        <livewire:app.dashboard.summary/>
                    </div>
                    <div class="col-lg-4">
                        <livewire:app.dashboard.notification/>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app>
