<div class="btn-group" role="group">
    <a class="btn btn-success btn-xs btn-3d edit" data-toggle="modal" data-target="#formModalProduk"
        data-backdrop="static" data-keyboard="false" data-route="{{ route('products.update', $id) }}"><em
            class="fa fa-pencil-square-o"></em></a>
    <a class="btn @if($count_disc == 0 && $count_trans == 0 && $count_vouch == 0 && $count_pemb == 0) btn-danger btn-remove-id @else btn-disabled btn-default @endif btn-xs btn-3d"
        @if($count_disc==0 && $count_trans==0 && $count_vouch==0 && $count_pemb==0)
        data-route="{{ route('products.destroy', $id) }}" @endif><em class="fa fa-times"></em></a>
</div>
