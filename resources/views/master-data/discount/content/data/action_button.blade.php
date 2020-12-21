<div class="btn-group" role="group">
    <a @if($berlaku_sampai> date('Y-m-d')) class="btn btn-success btn-xs btn-3d edit"
        data-route="{{ route('discounts.update', $id) }}" @else class="btn btn-default btn-xs" @endif><em
            class="fa fa-pencil-square-o"></em></a>
    <a @if($berlaku_sampai> date('Y-m-d')) class="btn btn-danger btn-id-{{ $id }} btn-xs btn-3d btn-remove-id"
        data-route="{{ route('discounts.destroy', $id) }}" @else class="btn btn-default btn-xs" @endif><em
            class="fa fa-times"></em></a>
</div>
