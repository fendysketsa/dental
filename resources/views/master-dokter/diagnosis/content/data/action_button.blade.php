<div class="btn-group" role="group">
    <a class="btn btn-success btn-xs btn-3d edit" data-route="{{ route('diagnosis.update', $id) }}"><em
            class="fa fa-pencil-square-o"></em></a>

    <a class="btn btn-danger btn-id-{{ $id }} btn-remove-id btn-xs btn-3d"
        data-route="{{ route('diagnosis.destroy', $id) }}"><em class="fa fa-times"></em></a>
</div>
