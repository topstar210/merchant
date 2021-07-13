<x-slot name="title">
    Account Security
</x-slot>

<div class="page-content">
    <x-utils.actionbar :title="'Account Security'" :showBack="'true'" wire:ignore/>
    <div class="container-fluid app-main">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-4 mx-auto">
                <div class="card mt-4">
                    <div class="card-header">
                        <h6><i class="ti-info-alt text-info"></i> Change {{\Illuminate\Support\Str::title($action)}}
                        </h6>
                    </div>
                    <div class="card-body">
                        {{print_r($errors->all())}}
                        <form wire:submit.prevent="changeAction">
                            <x-utils.form.input :key="'current_password'" :label="'Current Password'" :type="'password'"
                                                :js="''"/>
                            <x-utils.form.input :key="$action" :label="'Choose '.\Illuminate\Support\Str::title($action)" :type="$action == 'password' ? 'password' : 'number'"
                                                :js="''"/>
                            <x-utils.form.input :key="$action.'_confirmation'" :label="'Confirm '.\Illuminate\Support\Str::title($action)"
                                                :type="$action == 'password' ? 'password' : 'number'" :js="''"/>

                            <hr class="hr-dashed hr-menu">

                            <button class="btn btn-success w-100" type="submit" @if($errors->any()) disabled
                                    @endif wire:target="changeAction"
                                    wire:loading.attr="disabled"><span
                                    wire:target="changeAction" wire:loading class="btn-spinner"></span>
                                Change {{\Illuminate\Support\Str::title($action)}}
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
