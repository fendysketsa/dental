function load_data() {
    var cont = $(".load-data");
    cont.load(base_url + '/mntrg/members/data', function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat data!';
            toastr.error(fls, 'Oops!', {
                timeOut: 2000
            })
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            load_data_more('list', 'table');
            load_data_more('chart', 'chart');
        }
    });
}

function load_data_more(data, loadd) {
    var cont = $(".load-data-" + data);
    cont.load(base_url + '/mntrg/members/data?data=' + loadd, function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat data!';
            toastr.error(fls, 'Oops!', {
                timeOut: 2000
            })
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            switch (data) {
                case 'list':
                    data_attribut();
                    break;
                default:
                    data_chart();
                    break;
            }
        }
    });
}

function charts_attribute() {
    var ctx = document.getElementById('chart-member').getContext('2d');
    let data_chart = $("canvas#chart-member").data('chart')
    let data_chart_name = $("canvas#chart-member").data('chart-name')

    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data_chart_name,
            datasets: [{
                label: "Member",
                backgroundColor: 'lightblue',
                borderColor: 'royalblue',
                data: data_chart,
            }]
        },

        options: {
            layout: {
                padding: 10,
            },
            legend: {
                position: 'bottom',
            },
            title: {
                display: true,
                text: 'Line Chart - Member'
            },
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Kali Kunjungan'
                    }
                }],
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Nama'
                    }
                }]
            }
        }
    });
    chart.update();
}

function data_chart() {
    var cont = $(".load-data-chart");
    cont.load(base_url + '/mntrg/members/data?data=chart', function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat data!';
            toastr.error(fls, 'Oops!', {
                timeOut: 2000
            })
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            charts_attribute();
        }
    });
}

function convertRupiah(bilangan_) {
    var bilangan = bilangan_;

    var number_string = bilangan.toString(),
        sisa = number_string.length % 3,
        rupiah = number_string.substr(0, sisa),
        ribuan = number_string.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    return rupiah;
}

function getIndoDate(date) {
    var _hari = ['Ming', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sabt'];
    var _bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agust', 'Sept', 'Okt', 'Nov', 'Des'
    ];
    var elemnt = new Date(date);
    var hari_ = elemnt.getDay();
    var tanggal_ = elemnt.getDate();
    var bulan_ = elemnt.getMonth();
    var tahun_ = elemnt.getFullYear();

    var hari = _hari[hari_];
    var tanggal = tanggal_;
    var bulan = _bulan[bulan_];
    var tahun = tahun_;

    return hari + ', ' + tanggal + ' ' + bulan + ' ' + tahun;

}

function detail_attribute(id, e, def) {
    var groupColumn = 1;
    var dTable = $('#data-table-view-detail-' + def + '-' + id).DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        stateSave: true,
        // displayLength: 25,
        ajax: {
            url: e + (def ? '?det=' + def : ''),
            type: 'GET',
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                className: "text-center td-height-img",

            },
            {
                data: 'tanggal',
                name: 'tanggal',
                className: "td-height-img text-left",
                render: getIndoDate
            },
            {
                data: 'no_transaksi',
                name: 'no_transaksi',
                className: "text-center td-height-img",
                render: function (data, type, row) {
                    return !data ? '<div style="width:35%; margin-right:25px; float:right; border-left:1px dotted #000; border-bottom:1px dotted #000;">&nbsp;</div>' : data;
                }
            },
            {
                data: 'gambar',
                name: 'gambar',
                className: "td-height-img",
                render: getImgDetail,
            },
            {
                data: 'nama',
                name: 'nama',
                className: "td-height-img text-left"
            },
            {
                data: 'harga',
                name: 'harga',
                className: "text-right td-height-img harga-sum",
                render: function (data, type, row) {
                    return convertRupiah(data) + '<input type="hidden" name="row_harga" value="' + data + '">'
                }
            }
        ],
        order: [
            [0, 'desc']
        ],
        drawCallback: function (settings) {
            var api = this.api();
            var rows = api.rows({
                page: 'current'
            }).nodes();
            var last = null;

            api.column(groupColumn, {
                page: 'current'
            }).data().each(function (group, i) {
                if (last !== group) {
                    $(rows).eq(i).before(
                        '<tr class="group text-center" style="color:#fff !important; background-color:#7d8084 !important;"><td colspan="6"><strong>' + getIndoDate(group) + '</strong></td></tr>'
                    );

                    last = group;
                }
            });
        },
        columnDefs: [{
            orderable: false,
            targets: [5]
        }]
    });

    setTimeout(() => {
        $(".c-" + def).text(dTable.page.info().recordsTotal);
    }, 500);

    $("select[name=data-table-view_length]").on('change', function () {
        dTable.ajax.reload();
    });
    $("input[type=search]").on('input', function (e) {
        dTable.ajax.reload();
    });
}

function loadTableDetail(id) {
    html = `<div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#detLayanan" data-toggle="tab" aria-expanded="false">Layanan <sup><label class="c-layanan btn-info badge">...</label></sup></a></li>
                    <li class="">
                        <a href="#detProduk" data-toggle="tab" aria-expanded="false">Produk <sup><label class="c-produk btn-info badge">...</label></sup></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="detLayanan">
                        <table class="table table-striped" width="100%" cellspacing="0" id="data-table-view-detail-layanan-` + id + `">
                            <thead class="bg-navy disabled color-palette">
                                <tr>
                                    <th style="width:5%;" class="text-center">No</th>
                                    <th style="width:25%;">Tanggal</th>
                                    <th style="width:15%;">No. Transaksi</th>
                                    <th style="width:20%;">Gambar</th>
                                    <th style="width:25%;" class="text-left">Layanan</th>
                                    <th style="width:10%;">Harga</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="tab-pane" id="detProduk">
                        <table class="table table-striped" width="100%" cellspacing="0" id="data-table-view-detail-produk-` + id + `">
                            <thead class="bg-navy disabled color-palette">
                                <tr>
                                    <th style="width:5%;" class="text-center">No</th>
                                    <th style="width:25%;">Tanggal</th>
                                    <th style="width:15%;">No. Transaksi</th>
                                    <th style="width:20%;">Gambar</th>
                                    <th style="width:25%;" class="text-left">Produk</th>
                                    <th style="width:10%;">Harga</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>`;

    return html;
}

function load_DetailHistoryTrans() {
    $('tbody').delegate('.detail', 'click', function () {
        var ths = $(this);
        var member_ = ths.closest('tr');

        $(".foto-member").html(member_.find('td.foto-member_').html())
        $(".foto-member img").attr('style', 'border-radius:50%; width:165px; height:165px;');
        $(".no-member").text(member_.find('td.no-member_').text());
        $(".nama-member").text(member_.find('td.nama-member_').text());
        $(".gender-member").text(member_.find('td.gender-member_').text());
        $(".email-member").text(member_.find('td.email-member_').text());
        $(".telepon-member").text(member_.find('td.phone-member_').text());
        $('.modal-title').html('<em class="fa fa-search"></em> Detail Transaksi');
        $(".load-detail-modal").html(loadTableDetail(ths.closest('tr').find('a#more').data('id')));
        detail_attribute(ths.closest('tr').find('a#more').data('id'), ths.data('route'), 'layanan');
        detail_attribute(ths.closest('tr').find('a#more').data('id'), ths.data('route'), 'produk');

        // setTimeout(() => {
        //     $(document).ready(function () {
        //         var table = $('#data-table-view-detail-layanan-' + ths.closest('tr').find('a#more').data('id')).DataTable({
        //             "paging": false,
        //             "ordering": false,
        //             "info": false
        //         });
        //         var params = table.$('input').serializeArray();

        //         // Iterate over all form elements
        //         $.each(params, function (e, i) {
        //             console.log(e, i)

        //         });
        //     });
        // }, 1000);

    });
}

function data_attribut() {

    var dTable = $('#data-table-view').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/mntrg/members/json",
            type: 'GET',
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                className: 'td-height-img'
            },
            {
                data: 'foto',
                name: 'foto',
                render: getImg,
                className: "text-center td-height-img foto-member_"
            },
            {
                data: 'no_member',
                name: 'no_member',
                className: 'td-height-img no-member_'
            },
            {
                data: 'nama',
                name: 'nama',
                className: 'td-height-img nama-member_'
            },
            {
                data: 'gender',
                name: 'gender',
                className: 'td-height-img gender-member_'
            },
            {
                data: 'email',
                name: 'email',
                className: 'td-height-img email-member_'
            },
            {
                data: 'telepon',
                name: 'telepon',
                className: 'td-height-img phone-member_'
            },
            {
                data: 'saldo',
                name: 'saldo',
                className: 'td-height-img'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                className: "text-center td-height-img"
            },
        ],
        order: [
            [0, 'desc']
        ]
    });
    dTable.ajax.reload();
    $("select[name=data-table-view_length]").on('change', function () {
        dTable.ajax.reload();
    });
    $("input[type=search]").on('input', function (e) {
        dTable.ajax.reload();
    });
    load_DetailHistoryTrans();
}

function imgError(image) {
    image.onerror = "";
    image.src = "/images/brokenimage.jpg";
    image.alt = "Images corrupt!";
    image.title = "Images corrupt!";
    return true;
}

function getImg(data, type, full, meta) {
    var img = !data ? '/images/noimage.jpg' : '/storage/master-data/member/uploads/' + data;
    return '<img onerror="imgError(this);" style="border-radius:50%;" width="70" height="70" src="' + base_url + img + '">';
}

function getImgDetail(data, type, full, meta) {
    var img = !data ? base_url + '/images/noimage.jpg' : data;
    return '<img onerror="imgError(this);" style="border-radius:50%;" width="70" height="70" src="' + img + '">';
}

$(document).ready(function () {
    load_data();
});
