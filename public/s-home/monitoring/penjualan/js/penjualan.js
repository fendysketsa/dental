function load_data() {
    var cont = $(".load-data");
    cont.load(base_url + '/sales/data', function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat data!';
            toastr.error(fls, 'Oops!', {
                timeOut: 2000
            })
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            data_attribut();
        }
    });
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

function data_attribut() {

    var ElemtStart = rangeDate(0, 'back');
    var ElemtEnd = rangeDate(1, 'back');

    var dateranges = (ElemtStart ? '?starts=' + ElemtStart : '') +
        (ElemtEnd ? '&ends=' + ElemtEnd : '');

    var dTable = $('#data-table-view').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/sales/json" + dateranges,
            type: 'GET',
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                className: "td-height-img"
            },
            {
                data: 'tanggal',
                name: 'tanggal',
                className: "td-height-img text-center",
                render: function (data, type, row) {
                    return data ? getIndoDate(data) : '';
                }
            },
            {
                data: 'nama',
                name: 'nama',
                className: "td-height-img"
            },
            {
                data: 'tagihan',
                name: 'tagihan',
                render: function (data, type, row) {
                    return data ? convertRupiah(data, "Rp. ") : '0'
                },
                className: "td-height-img"
            },
            {
                data: 'transaksi',
                name: 'transaksi',
                className: "td-height-img"
            },
            {
                data: 'cara_bayar',
                name: 'cara_bayar',
                orderable: false,
                className: "td-height-img text-center"
            },
            {
                data: 'status_transaksi',
                name: 'status_transaksi',
                orderable: false,
                className: "td-height-img text-center"
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                className: "text-center"
            }
        ],
        order: [
            [0, 'desc']
        ]
    });

    $("select[name=data-table-view_length]").on('change', function () {
        dTable.ajax.reload();
    });
    $("input[type=search]").on('input', function (e) {
        dTable.ajax.reload();
    });

    $('.add-on-daterpicker').on('apply.daterangepicker', function (ev, picker) {
        fill_field_daterange(picker);
    });

    $('.add-on-daterpicker').on('cancel.daterangepicker', function (ev, picker) {
        remove_field_daterange();
    });

    $('.group-date-range').delegate('.remove-on-daterpicker', 'click', function () {
        remove_field_daterange();
    });

    $('.add-on-daterpicker').daterangepicker({
        alwaysShowCalendars: true,
        showCustomRangeLabel: true,
        startDate: !$("input[name=id]").val() ? moment().add(0, 'd').toDate() : $("input[name=berlaku_dari]").val(),
        endDate: !$("input[name=id]").val() ? moment().add(1, 'd').toDate() : $("input[name=berlaku_sampai]").val(),
        singleDatePicker: false,
        showDropdowns: false,
        autoUpdateInput: true,
        locale: {
            cancelLabel: 'Clear',
            format: 'DD-MM-YYYY'
        },
    });
    $('.add-on-daterpicker').on('apply.daterangepicker', function (ev, picker) {
        fill_field_daterange(picker);
    });

    $('.add-on-daterpicker').on('cancel.daterangepicker', function (ev, picker) {
        remove_field_daterange();
    });

    $('.group-date-range').delegate('.remove-on-daterpicker', 'click', function () {
        remove_field_daterange();
    });
    load_DetailHistoryTrans();
}

function detail_attribute(id, e, def) {
    var groupColumn = 2;
    var dTable = $('#data-table-view-detail-' + def + '-' + id).DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
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
                className: "text-right td-height-img",
                render: function (data, type, row) {
                    return convertRupiah(data)
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
                        '<tr class="group text-center" style="color:#fff !important; background-color:#7d8084 !important;"><td colspan="6"><strong>' + group + '</strong></td></tr>'
                    );

                    last = group;
                }
            });
        }
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

function getImgDetail(data, type, full, meta) {
    var img = !data ? base_url + '/images/noimage.jpg' : data;
    return '<img onerror="imgError(this);" style="border-radius:50%;" width="70" height="70" src="' + img + '">';
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
                                    <th style="width:25%;">Tanggal Reservasi</th>
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
        $('.modal-title').html('<em class="fa fa-search"></em> Detail Penjualan');
        $(".load-detail-modal").html(loadTableDetail(ths.closest('tr').find('a#more').data('id')));
        detail_attribute(ths.closest('tr').find('a#more').data('id'), ths.data('route'), 'layanan');
        detail_attribute(ths.closest('tr').find('a#more').data('id'), ths.data('route'), 'produk');
    });
}


function fill_field_daterange(picker) {
    $("input[name='berlaku_dari']").val(picker.startDate.format('DD-MM-YYYY'));
    $("input[name='berlaku_sampai']").val(picker.endDate.format('DD-MM-YYYY'));

    $('#data-table-view').DataTable().ajax.url(base_url +
        "/sales/json?starts=" + picker.startDate.format('YYYY-MM-DD') +
        "&ends=" + picker.endDate.format('YYYY-MM-DD')).load();
}

function remove_field_daterange() {
    $("input[name='berlaku_dari']").val('');
    $("input[name='berlaku_sampai']").val('');

    $('#data-table-view').DataTable().ajax.url(base_url +
        "/sales/json").load();
}

function rangeDate(range_, back) {
    Date.prototype.addDays = function (days) {
        var date = new Date(this.valueOf());
        date.setDate(date.getDate() + days);
        return date;
    }

    var date = new Date();
    var ranges = range_
    var currentTime = date.addDays(ranges);
    var day = currentTime.getDate();
    var month = currentTime.getMonth() + 1;
    var year = currentTime.getFullYear();

    if (day < 10) {
        day = "0" + day;
    }

    if (month < 10) {
        month = "0" + month;
    }

    var today_date = !back ? day + "-" + month + "-" + year : year + "-" + month + "-" + day;

    return today_date.toString();
}

function downloadReport(st, en) {
    var http = new XMLHttpRequest();
    http.responseType = 'blob';

    var blob;
    var url = base_url + '/sales/export' + (st && en ? '?starts=' + st + '&ends=' + en : '');
    http.open("GET", url, true);

    http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    http.onreadystatechange = function () { //Call a function when the state changes.
        if (http.readyState == 4 && http.status == 200) {
            var filename = "";
            var disposition = http.getResponseHeader('Content-Disposition');
            if (disposition && disposition.indexOf('attachment') !== -1) {
                var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                var matches = filenameRegex.exec(disposition);
                if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
            }
            var type = http.getResponseHeader('Content-Type');
            blob = new Blob([http.response], {
                type: type,
                endings: 'native'
            });
            var URL = window.URL || window.webkitURL;
            var downloadUrl = URL.createObjectURL(blob);
            var a = document.createElement("a");
            a.href = downloadUrl;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
        }
    }
    http.send();
}

$(document).ready(function () {
    $("input[name=berlaku_dari]").val(rangeDate(0))
    $("input[name=berlaku_sampai]").val(rangeDate(1))

    $(".export-komisi").on('click', function () {
        var start = $("input[name='berlaku_dari']").val();
        var end = $("input[name='berlaku_sampai']").val();

        if (!start && !end) {
            var fls = 'Isikan data tanggal dari - sampai!';
            toastr.info(fls, 'Oops!', {
                timeOut: 2000
            })
            return false;
        }
        downloadReport(start, end);
    });

    load_data();
});
