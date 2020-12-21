<div class="btn-group" role="group">
    <a class="btn btn-success btn-xs btn-3d edit" data-route="{{ route('packages.update', $id) }}"><em
            class="fa fa-pencil-square-o"></em></a>
    <a class="btn @if($count_paket == 0) btn-danger btn-remove-id @else btn-disabled btn-default @endif btn-xs btn-3d"
        @if($count_paket==0) data-route="{{ route('packages.destroy', $id) }}" @endif><em class="fa fa-times"></em></a>
</div>
