<div class="btn-group" role="group">
    <a @if($tanggal>= DATE('Y-m-d')) data-toggle="modal" data-target="#formModalTransPengeluaran"
        data-backdrop="static" data-keyboard="false" class="btn btn-success btn-xs btn-3d edit"
        data-route="{{ route('trans.spends.update', $id) }}" @else class="btn btn-default btn-xs btn-3d" @endif><em
            class="fa fa-pencil-square-o"></em></a>

    <a @if($tanggal>= DATE('Y-m-d')) class="btn btn-danger btn-xs btn-3d btn-remove-id"
        data-route="{{ route('trans.spends.destroy', $id) }}" @else class="btn btn-default btn-xs btn-3d" @endif><em
            class="fa fa-times"></em></a>
</div>
