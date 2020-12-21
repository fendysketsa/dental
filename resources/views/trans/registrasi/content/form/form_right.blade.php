<form action="{{ $action }}" id="formRegistrasi"></form>
@csrf

<div class="row">
    <div class="col-md-12">
        <div class="box box-default display-future">
            <div class="box-header with-border bg-default">
                <h3 class="box-title"><em class='fa fa-th-large'></em> Layanan</h3>
            </div>
            <div class="box-body clean-sheet on-dutty-off">
                <div class="load-form-table-layanan">
                    <table class="table hover" width="100%" cellspacing="0">
                        <thead class="bg-navy disabled color-palette">
                            <th style="width:5%;" class="text-center">No</th>
                            <th style="width:60%;">Layanan</th>
                            <th style="width:25%;">Terapis</th>
                            <th style="width:10%;" class="text-center">Action</th>
                        </thead>
                        <tbody class="load-row-layanan">
                            <tr>
                                <td colspan="4">
                                    <em class='fa fa-spin fa-spinner'></em> Loading...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="col-md-4">
                        <div class="row">
                            <button class="col-md-3 btn btn-xs btn-info add-row-layanan" type="button"><em
                                    class="fa fa-plus"></em>
                                Baris</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-default clean-sheet on-dutty-off display-future">
            <div class="box-header with-border bg-default">
                <h3 class="box-title"><em class='fa fa-th-large'></em> Paket</h3>
                <div class="pull-right">
                    <button data-toggle="collapse" class="btn btn-box-tool collap-paket" data-target="#cont-paket"><em
                            class="fa fa-plus"></em></button>
                </div>
            </div>
            <div class="box-body collapse" id="cont-paket">
                <div class="load-form-table-paket">
                    <table class="table hover" width="100%" cellspacing="0">
                        <thead class="bg-navy disabled color-palette">
                            <th style="width:5%;" class="text-center">No</th>
                            <th style="width:85%;">Paket</th>
                            <th style="width:10%;" class="text-center">Action</th>
                        </thead>
                        <tbody class="load-row-paket">
                            <tr>
                                <td colspan="3">
                                    <em class='fa fa-spin fa-spinner'></em> Loading...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="col-md-4">
                        <div class="row">
                            <button class="col-md-3 btn btn-xs btn-info add-row-paket" type="button"><em
                                    class="fa fa-plus"></em>
                                Baris</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 clean-sheet on-dutty-off" style="margin-bottom:20px;">
        <div class="row">
            <div class="pull-right col-md-12 col-xs-12">
                <div class="col-md-9 col-xs-9 bg-gray-light">
                    <h4 class="pull-right">Est. Total Harga : </h4>
                </div>
                <div class="col-md-3 col-xs-3 bg-gray">
                    <h4 class="pull-left price-full">0</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6"></div>
    <div class="col-md-6 clean-sheet on-dutty-off">
        <div class="row">
            <div class="col-md-6">
                {{-- <div class="form-group">
                    <label>DP ? <em class="text-danger">*</em></label>
                    <div class="input-group input-group-sm col-xs-12 date">
                        <div class="input-group-addon">
                            <i class="fa fa-user-plus"></i>
                        </div>
                        <input name="dp" type="number" class="form-control" min="10000" step="500" value="10000"
                            form="formRegistrasi">
                    </div>
                </div> --}}
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Berapa orang ? <em class="text-danger">*</em></label>
                    <div class="input-group input-group-sm col-xs-12 date">
                        <div class="input-group-addon">
                            <i class="fa fa-user-plus"></i>
                        </div>
                        <input name="jumlah_orang" type="number" class="form-control" min="1" value="1"
                            form="formRegistrasi">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
