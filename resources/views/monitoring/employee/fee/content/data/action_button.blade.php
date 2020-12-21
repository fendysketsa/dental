<div class="btn-group" role="group">
    <a class="btn @if($total_komisi>0) btn-success detail @else btn-disabled btn-default @endif btn-xs btn-3d "
        @if($total_komisi>0) id="more" data-id="{{ $id }}" data-toggle="modal" data-target="#formModalKomisiTerapis"
        data-backdrop="static"
        data-keyboard="false" data-route="{{ route('therapists.fee.show', $id) }}" @endif><em
            class="fa fa-search"></em></a>
</div>
