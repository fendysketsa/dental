<div class="btn-group" role="group">
    <a class="btn btn-success btn-xs btn-3d edit" data-toggle="modal" data-target="#formModalStockManagement"
        data-backdrop="static" data-keyboard="false" data-route="{{ route('stocks.show', $id) }}"><em
            class="fa fa-pencil-square-o"></em></a>

    <a class="btn btn-info btn-xs btn-3d detail" data-toggle="modal" data-target="#detModalStockManagement"
        data-backdrop="static" data-keyboard="false" data-id-produk="{{ $id }}"
        data-route="{{ route('stocks.detail', $id) }}"><em class="fa fa-search"></em></a>
</div>
