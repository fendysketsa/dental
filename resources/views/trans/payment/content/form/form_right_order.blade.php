<div class="row">
    <div class="col-md-12">
        <div class="box box-default display-future fl-layanan">
            <div class="box-header with-border bg-default">
                <h3 class="box-title"><em class='fa fa-th-large'></em> Layanan</h3>
            </div>
            <div class="box-body">
                <div class="load-form-table-layanan">
                    <table class="table hover" width="100%" cellspacing="0">
                        <thead class="bg-navy disabled color-palette">
                            <th style="width:5%;" class="text-center">No</th>
                            <th @if(!empty($_GET['loaded']) && $_GET['loaded']=='detail' ) style="width:70%;" @else
                                style="width:60%;" @endif>Layanan</th>
                            {{-- <th style="width:25%;">Terapis</th> --}}
                            @if(empty($_GET['loaded']))
                            <th style="width:10%;" class="text-center">Action</th>
                            @endif
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
                        @if(empty($_GET['loaded']))
                        <div class="row">
                            <button class="col-md-3 btn btn-xs btn-info add-row-layanan" type="button"><em
                                    class="fa fa-plus"></em>
                                Baris</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="box box-default display-future fl-paket">
            <div class="box-header with-border bg-default">
                <h3 class="box-title"><em class='fa fa-th-large'></em> Paket</h3>
                @if(empty($_GET['loaded']))
                <div class="pull-right">
                    <button data-toggle="collapse" class="btn btn-box-tool collap-paket" data-target="#cont-paket"><em
                            class="fa fa-plus"></em></button>
                </div>
                @endif
            </div>
            <div class="box-body collapse" id="cont-paket">
                <div class="load-form-table-paket">
                    <table class="table hover" width="100%" cellspacing="0">
                        <thead class="bg-navy disabled color-palette">
                            <th style="width:5%;" class="text-center">No</th>
                            <th @if(!empty($_GET['loaded']) && $_GET['loaded']=='detail' ) style="width:95%;" @else
                                style="width:85%;" @endif>Paket</th>
                            @if(empty($_GET['loaded']))
                            <th style="width:10%;" class="text-center">Action</th>
                            @endif
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
                        @if(empty($_GET['loaded']))
                        <div class="row">
                            <button class="col-md-3 btn btn-xs btn-info add-row-paket" type="button"><em
                                    class="fa fa-plus"></em>
                                Baris</button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>
