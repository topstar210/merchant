<div wire:key="footer">
    @if(count($list))
        <footer class="footer">
            <div class="row  mx-0">
                <div class="col hidden-sm">
                    <p class="mt-2 mb-0">Showing {{$list->firstItem() ?? 0}}
                        - {{$list->lastItem() ?? 0}}
                        of {{$list->total()}} {{$listName}}</p>
                </div>
                <div class="col-auto">
                    {{ $list->links() }}
                </div>
            </div>
        </footer>
    @endif
</div>
