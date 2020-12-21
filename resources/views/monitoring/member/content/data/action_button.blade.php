<div class="btn-group" role="group">
    <a class="btn @if($in_member_use>0) btn-success detail @else btn-default btn-disabled @endif btn-xs btn-3d"
        @if($in_member_use>0) id="more" data-id="{{ $id }}" data-toggle="modal"
        data-target="#formModalDetailTransMember" data-backdrop="static"
        data-keyboard="false" data-route="{{ route('mntrg.members.show', $id) }}" @endif><em
            class="fa fa-search"></em></a>
</div>
