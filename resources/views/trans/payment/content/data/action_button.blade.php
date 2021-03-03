<div class="btn-group" role="group">
    <a class="btn btn-warning btn-xs btn-3d bayar" data-id-cetak-on="{{ $id}}"
        data-route-on="{{ route('cashiers.printOut') }}" data-toggle="modal" data-target="#formModalPembayaran"
        data-backdrop="static" data-keyboard="false" data-route="{{ route('cashiers.update', $id) }}"><em
            class="fa fa-credit-card"></em></a>
    <a class="btn @if(!empty($cara_bayar_kasir)) btn-info print-a @else btn-default btn-disabled @endif btn-xs btn-3d"
        data-id-cetak="{{ $id}}" {{-- @if(!empty($cara_bayar_kasir)) data-route="{{ route('cashiers.printOut') }}"
        @endif --}}><em class="fa fa-print"></em></a>
</div>
