<div class="btn-group" role="group">
    <a class="btn @if($count_trans>0) btn-info detail @else btn-default btn-disabled @endif btn-xs btn-3d "
        @if($count_trans>0) data-id-member="{{ $id }}" data-route="{{ route('members-info.show', $id) }}"
        data-toggle="modal" data-target="#detMember" data-backdrop="static" data-keyboard="false" @endif><em
            class="fa fa-search"></em></a>

    {{-- <a class="btn btn-success btn-xs btn-3d print" data-id-cetak="{{ $id_trans }}"><em
        class="fa fa-print"></em></a> --}}
</div>
