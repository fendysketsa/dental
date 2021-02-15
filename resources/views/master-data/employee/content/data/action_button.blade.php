<div class="btn-group" role="group">
    <a class="btn btn-success edit btn-xs btn-3d" data-route="{{ route('employees.update', $id) }}"><em
            class="fa fa-pencil-square-o"></em></a>
    <a class="btn @if($in_det_use == 0 && $in_kal_use == 0) @if(!empty(session('cabang_session')) && $role != 5) btn-danger btn-remove-id btn-id-{{ $id }}  @else btn-default btn-disabled @endif @else btn-default btn-disabled @endif btn-xs btn-3d"
        @if($in_det_use==0 && $in_kal_use==0) @if(empty(session('cabang_session')))
        data-route="{{ route('employees.destroy', $id) }}" @endif @endif><em class="fa fa-times"></em></a>
</div>
