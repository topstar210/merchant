<div class="card">
    <div class="card-header">
        <h6>Notifications
        </h6>
    </div>
    <div class="card-body">
        <div style="height: 300px;  overflow-y: scroll; overflow-x: hidden; scrollbar-width: none;">
            @if(empty($notifications))
                <x-utils.empty :noFooter="''">
                    <h5><i class="ti-info-alt text-danger"></i> No Notification Yet</h5>
                </x-utils.empty>
            @else



            @endif
        </div>
    </div>
</div>
