<div class="btn-group" role="group">
    <a class="btn btn-warning detail btn-xs btn-3d" data-toggle="modal" data-target="#formModalInfoPembayaran"
        data-backdrop="static" data-keyboard="false" data-route="{{ route('payments.show', $id) }}"><em
            class="fa fa-search"></em></a>
    <a class="btn btn-info print btn-xs btn-3d" data-id-cetak="{{ $id}}"
        data-route="{{ route('cashiers.printOut') }}"><em class="fa fa-print"></em></a>
</div>
