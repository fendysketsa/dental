<div class="btn-group" role="group">
    <a class="btn btn-success btn-xs btn-3d edit" data-toggle="modal" data-target="#formModalLayanan"
        data-backdrop="static" data-keyboard="false" data-route="{{ route('services.update', $id) }}"><em
            class="fa fa-pencil-square-o"></em></a>
    <a class="btn @if($count_kual == 0 && $count_paket == 0) btn-danger btn-remove-id @else btn-disabled btn-default @endif btn-xs btn-3d"
        @if($count_kual==0 && $count_paket==0) data-route="{{ route('services.destroy', $id) }}" @endif><em
            class="fa fa-times"></em></a>
</div>
