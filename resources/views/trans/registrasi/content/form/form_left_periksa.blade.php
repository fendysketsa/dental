<form action="{{ $action }}" id="formPeriksa"></form>
@csrf

<div class="row">
    <div class="col-md-12 text-left mt-20">
        <div class="container_order">
            <ul class="progressbar_order">
                <li class="active_order st-1" data-step="1"></li>
                <li class="st-2" data-step="2"></li>
                <li class="st-3" data-step="3"></li>
            </ul>
        </div>
    </div>
</div>

<div id="f-load-rekam-medik-periksa"></div>
<div id="f-load-rekam-medik-periksa-gigi" class="hide"></div>
<div id="f-load-ubah" class="hide"></div>
