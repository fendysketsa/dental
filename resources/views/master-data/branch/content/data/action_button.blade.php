<div class="btn-group" role="group">
    <a class="btn btn-success edit btn-xs btn-3d" data-route="{{ route('branchs.update', $id) }}"><em
            class="fa fa-pencil-square-o"></em></a>
    <a class="btn @if($in_kal_use == 0 && $in_lay_use == 0 && $in_lay_det_use == 0) btn-danger btn-id-{{ $id }} btn-remove-id @else btn-default btn-disabled  @endif btn-xs btn-3d"
        @if($in_kal_use==0 && $in_lay_use==0 && $in_lay_det_use==0) data-route="{{ route('branchs.destroy', $id) }}"
        @endif><em class="fa fa-times"></em></a>
</div>
