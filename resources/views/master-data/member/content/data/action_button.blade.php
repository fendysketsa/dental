<div class="btn-group" role="group">
    <a class="btn btn-success btn-xs btn-3d edit" data-route="{{ route('members.show', $id) }}" data-toggle="modal"
        data-target="#formModalPelanggan" data-backdrop="static" data-keyboard="false"><em
            class="fa fa-pencil-square-o"></em></a>

    <a class="btn @if($in_member_use == 0) btn-danger btn-remove-id @else btn-default btn-disabled @endif btn-xs btn-3d "
        @if($in_member_use==0) data-route="{{ route('members.destroy', $id) }}" @endif><em class="fa fa-times"></em></a>
</div>
