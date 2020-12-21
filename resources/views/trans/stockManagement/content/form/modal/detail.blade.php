<div class="load-detail-modal">
    <div class="modal-content">
        <div class="modal-header bg-info ">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-center"></h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-6">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>PRODUK</td>
                                <td>:</td>
                                <td><b>@if(!empty($dataE)) {{ $dataE['nama'] }} @endif</b></td>
                            </tr>
                            <tr>
                                <td>KATEGORI</td>
                                <td>:</td>
                                <td>@if(!empty($dataE)) {{ $dataE['kategori'] }} @endif</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-6">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>UPDATE TERAKHIR</td>
                                <td>:</td>
                                <td>@if(!empty($dataE)) {{ date("D, d M Y / H:i", strtotime($dataE['updated_at'])) }}
                                    @endif</td>
                            </tr>
                            <tr>
                                <td>SISA STOK</td>
                                <td>:</td>
                                <td><b>@if(!empty($dataE)) {{ $dataE['stok'] }} @endif</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="box box-info display-future">
                <div class="box-header with-border bg-info">
                    <h3 class="box-title"><em class='fa fa-list-alt'></em> Log Stok</h3>
                </div>
                <div class="box-body">
                    <div class="load-history"><em class="fa fa-refresh fa-spin"></em> Loading...</div>
                </div>
            </div>
        </div>
    </div>
</div>
