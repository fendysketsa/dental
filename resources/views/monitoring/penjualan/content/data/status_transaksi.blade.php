<div class="btn-group" role="group">
    @if($status_transaksi == 1)
    <a class="btn btn-warning btn-xs btn-3d">Reservasi</a>
    @elseif($status_transaksi == 2)
    <a class="btn btn-danger btn-xs btn-3d">Hutang</a>
    @elseif($status_transaksi == 3)
    <a class="btn btn-info btn-xs btn-3d">Terbayar</a>
    @endif
</div>
