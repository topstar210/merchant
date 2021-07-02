<x-master>
    <x-slot name="title">
        {{$title}}
    </x-slot>
    <x-slot name="body">
        account-body accountbg
    </x-slot>

    {{ $slot }}

    @if (session()->has('error'))
        <div class="position-absolute top-0 end-0 p-3" style="z-index: 1050">
            <div id="bs_toast" role="alert" aria-live="assertive" aria-atomic="true" class="toast bg-white">
                <div class="toast-header">

                    <span class="me-auto badge {{session('error') ? 'badge-soft-danger' : 'badge-soft-success'}}"
                          style="    padding: .55em .9em;"><span
                            class=" font-14">{{session('error') ? "Error" : "Success"}}</span></span>
                    <small class="text-muted">just now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{session('error_message')}}
                </div>
            </div>
        </div>
    @endif
</x-master>
