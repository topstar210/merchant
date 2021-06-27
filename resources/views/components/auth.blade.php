<x-master>
    <x-slot name="title">
        {{$title}}
    </x-slot>
    <x-slot name="body">
        account-body accountbg
    </x-slot>

    {{ $slot }}

    @if (session()->has('error'))
        <div class="toast-container position-absolute top-0 end-0 p-3" style="z-index: 1050">
            <div role="alert" aria-live="assertive" aria-atomic="true" class="toast bg-white">
                <div class="toast-header">
                    <i class="{{session('error') ? 'fas fa-times-circle text-danger' : 'fas fa-check-circle text-success'}}"
                       style="margin-right: 8px"></i>
                    <strong class="me-auto">{{session('error') ? "Error" : "Success"}}</strong>
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
