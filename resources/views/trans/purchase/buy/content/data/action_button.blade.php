<div class="btn-group" role="group">
    <a @if($status!=2) data-toggle="modal" data-target="#formModalTransChecklistPembelian" data-backdrop="static"
        data-keyboard="false" class="btn btn-info btn-xs btn-3d checklist"
        data-route="{{ route('trans.purchases.checklist.update_', $id) }}" @else class="btn btn-default btn-xs btn-3d"
        @endif><em class="fa @if($status!=2) fa-truck @else fa-thumbs-o-up @endif"></em></a>

    <a @if($tanggal>= DATE('Y-m-d') && $status!=2) data-toggle="modal" data-target="#formModalTransPembelian"
        data-backdrop="static" data-keyboard="false" class="btn btn-success btn-xs btn-3d edit"
        data-route="{{ route('trans.purchases.update', $id) }}" @else class="btn btn-default btn-xs btn-3d" @endif><em
            class="fa fa-pencil-square-o"></em></a>

    <a @if($tanggal>= DATE('Y-m-d') && $status!=2) class="btn btn-danger btn-xs btn-3d btn-remove-id"
        data-route="{{ route('trans.purchases.destroy', $id) }}" @else class="btn btn-default btn-xs btn-3d" @endif><em
            class="fa fa-times"></em></a>
</div>
