<div class="btn-group" role="group">
    <a class="btn btn-success btn-xs btn-3d edit" data-route="{{ route('rekams.update', $id) }}"><em
            class="fa fa-pencil-square-o"></em></a>
    <a class="btn btn-danger btn-id-{{ $id }} btn-xs btn-3d btn-remove-id"
        data-route="{{ route('rekams.destroy', $id) }}"><em class="fa fa-times"></em></a>
</div>
