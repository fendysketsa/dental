<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print View</title>

    <link rel="stylesheet" href="{{ asset('s-home/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('s-home/bower_components/font-awesome/css/font-awesome.min.css') }}">

    <style>
        .header-address {
            font-size: 11px;
            line-height: 2px;
        }

        .table {
            font-size: 11px;
        }

        .signature {
            width: 120px;
            margin-top: 70px;
        }
    </style>

</head>

<body>
    <div class="col-lg-12">
        <div class="card">
            <div class="col-lg-12">
                <div class="header-address">
                    <p>Klinik Gigi Medina</p>
                    <p>Jl. Imam Benjol 63A Jepara</p>
                    <p>Pendaftaran: {{ $member['telepon'] }}</p>
                </div>

                <hr style="border-color: #bdbdbd;">
                <h4 class="text-center">KWITANSI</h4>
                <div style="margin-top:25px;">
                    <table class="table" cellspacing="0" style="border:0;">
                        <tr>
                            <th style="width:20%; font-weight:normal;">Tanggal Order</th>
                            <th style="width:5%;">:</th>
                            <th style="width:75%">
                                {{ \Carbon\Carbon::parse($transaksi['created_at'])->format('l, d F Y')}}
                            </th>
                        </tr>
                        <tr>
                            <th style="width:20%; font-weight:normal;">Jam Order</th>
                            <th style="width:5%;">:</th>
                            <th style="width:75%">{{ \Carbon\Carbon::parse($transaksi['created_at'])->format('H:i:s')}}
                            </th>
                        </tr>
                        <tr>
                            <th style="width:20%; font-weight:normal;">No. Transaksi</th>
                            <th style="width:5%;">:</th>
                            <th style="width:75%">{{ $transaksi['no_transaksi'] }}</th>
                        </tr>
                        <tr>
                            <th style="width:20%; font-weight:normal;">Member</th>
                            <th style="width:5%;">:</th>
                            <th style="width:75%">{{ $member['no_member'] }} <em>( {{ $member['nama'] }} )</em></th>
                        </tr>
                        <tr>
                            <th style="width:20%; font-weight:normal;">Alamat</th>
                            <th style="width:5%;">:</th>
                            <th style="width:75%">{{ $member['alamat'] }}</th>
                        </tr>

                    </table>
                </div>

                <div style="margin-top:25px;">
                    <table class="table" cellspacing="0" style="border:0;">
                        <tr>
                            <th style="width:5%;">#</th>
                            <th style="width:60%;">Tindakan</th>
                            <th style="width:15%;">Tarif</th>
                            <th style="width:20%">Jumlah</th>
                        </tr>

                        <tr>
                            <td colspan="2" style="text-align:right;">Sub total</td>
                            <td style="width:15%;">0</td>
                            <td style="width:20%">0</td>
                        </tr>

                        <tr style="border-top:2px solid #BDBDBD;">
                            <td colspan="2" style="text-transform: uppercase;">Total Pembayaran Diterima</td>
                            <td style="width:15%;">0</td>
                            <td style="width:20%">0</td>
                        </tr>

                        <tr style="border-top:2px solid #BDBDBD;">
                            <td colspan="4">Catatan: </td>
                        </tr>
                    </table>

                    <table class="table" cellspacing="0" style="border:0; width:80%;">
                        <tr>
                            <td style="text-transform: uppercase; border-top:0px; float: right; text-align: center;">
                                Admin
                                <div class="signature">
                                    <hr style="1.5px solid #BDBDBD;">
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>


                <div class="form-group" style="margin-top: 50px;">
                    <button class="btn btn-info btn-xs to-print-view" style="display:none;">
                        <em class="fa fa-print"></em> Print!
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('s-home/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('s-home/dist/js/sweetalert.min.js') }}"></script>

<script>
    $(document).ready(function() {

        $('body').keydown(function(event) {
            if (event.which == 80 && event.ctrlKey) {
                swal({
                    title: "Peringatan",
                    text: "Gunakan button print untuk print!",
                    icon: "warning",
                    dangerMode: true,
                }).then(()=>{
                    $(".to-print-view").focus()
                })
                return false;
            }
        });

        setTimeout(function() {
            $(".to-print-view").show()
        }, 2000);

    $(".to-print-view").on('click', function() {
        $(this).hide()
        window.print()
        $(this).show()
    });
})
</script>

</html>
