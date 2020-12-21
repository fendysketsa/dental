<div class="btn-group" role="group">
    <a @if($tanggal>= DATE('Y-m-d') && $jam_shift > date("H:i")) class="btn btn-success btn-xs btn-3d edit"
        data-route="{{ route('set.modals.update', $id) }}" @else class="btn btn-default btn-xs btn-3d" @endif><em
            class="fa fa-pencil-square-o"></em></a>
    <a @if($tanggal>= DATE('Y-m-d') && $jam_shift > date("H:i")) class="btn btn-danger btn-xs btn-3d btn-remove-id"
        data-route="{{ route('set.modals.destroy', $id) }}" @else class="btn btn-default btn-xs btn-3d" @endif><em
            class="fa fa-times"></em></a>
</div>
