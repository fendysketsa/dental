<div class="btn-group" role="group">
    <a class="btn btn-success btn-xs btn-3d edit" data-route="{{ route('brands.update', $id) }}"><em
            class="fa fa-pencil-square-o"></em></a>
    <a class="btn btn-xs @if($in_lay_det_use == 0) btn-danger btn-id-{{ $id }} btn-remove-id @else btn-disabled btn-default btn-3d @endif"
        @if($in_lay_det_use==0) data-route="{{ route('brands.destroy', $id) }}" @endif><em class="fa fa-times"></em></a>
</div>
