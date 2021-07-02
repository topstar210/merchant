<div class="row">
    <div class="col text-center" style="margin-top:20%">
        {{$slot}}
    </div>
    @if(!isset($noFooter))
        <footer class="footer" style="background-color: #fff; border: none">
        </footer>
    @endif
</div>

