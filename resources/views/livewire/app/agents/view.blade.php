<x-slot name="title">
    View Agent
</x-slot>

<div class="page-content">
    <x-utils.actionbar :title="$agent->full_name" :showBack="''">
        <x-slot name="action">
            @if($agent->reg_com)
                <button class=" btn btn-md btn-soft-danger" wire:target="deleteAgent" wire:loading.attr="disabled"
                        onclick="handleDelete()"><i
                        wire:loading.class="d-none" class="fas fa-trash me-2"></i><span
                        wire:loading class="btn-spinner btn-spinner-soft-danger"></span> Delete
                </button>
            @endif
        </x-slot>
    </x-utils.actionbar>


</div>

@push('scripts')
    <script>

        function handleDelete() {

            Swal.fire({
                title: 'Delete Agent',
                text: 'Are you sure you want to delete Agent?',
                showCancelButton: true,
                confirmButtonText: `Delete`,
            }).then((result) => {
                if (result.value) {
                @this.call('deleteAgent');
                }
            })
        }
    </script>
@endpush
