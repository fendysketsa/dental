function load_data() {
    var cont = $(".load-data");
    cont.load(base_url + '/therapists/fee/data', function (e, s, f) {
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

function loadTableDetail(id) {
    html = `<table class="table table-striped" width="100%" cellspacing="0" id="data-table-view-detail-` + id + `">
            <thead class="bg-navy disabled color-palette">
                <tr>
                    <th style="width:5%;" class="text-center">No</th>
                    <th style="width:15%;">No. Transaksi</th>
                    <th style="width:25%;">Layanan</th>
                    <th style="width:20%;">Harga</th>
                    <th style="width:15%;">Komisi ( % )</th>
                    <th style="width:20%;">Komisi ( Rp. )</th>
                </tr>
            </thead>
        </table>`;
    return html;
}

function load_DetailKom() {
    $('tbody').delegate('.detail', 'click', function () {
        var ths = $(this);
        var terapis_ = ths.closest('tr');
        $(".foto-terapis").html(terapis_.find('td.foto-terapis_').html())
        $(".foto-terapis img").attr('style', 'border-radius:50%; width:165px; height:165px;');
        $(".nama-terapis").text(terapis_.find('td.nama-terapis_').text());
        $(".jabatan-terapis").text(terapis_.find('td.jabatan-terapis_').text());
        $(".komisi-terapis").text(terapis_.find('td.komisi-terapis_').text());
        $('.modal-title').html('<em class="fa fa-search"></em> Detail Komisi');
        $(".load-detail-modal").html(loadTableDetail(ths.closest('tr').find('a#more').data('id')));
        detail_attribute(ths.closest('tr').find('a#more').data('id'), ths.data('route'));
    });
}

function imgError(image) {
    image.onerror = "";
    image.src = "/images/brokenimage.jpg";
    image.alt = "Images corrupt!";
    image.title = "Images corrupt!";
    return true;
}

function getImg(data, type, full, meta) {
    var img = !data ? '/images/noimage.jpg' : '/storage/master-data/employee/uploads/' + data;
    return '<img onerror="imgError(this);" style="border-radius:50%;" width="70" height="70" src="' + base_url + img + '">';
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

    var dTable = $('#data-table-view').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/therapists/fee/json",
            type: 'GET',
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                className: "text-center td-height-img"
            },
            {
                data: 'foto',
                name: 'foto',
                render: getImg,
                className: "text-center td-height-img foto-terapis_"
            },
            {
                data: 'nama',
                name: 'nama',
                className: "td-height-img nama-terapis_"
            },
            {
                data: 'jabatan',
                name: 'jabatan',
                className: "td-height-img jabatan-terapis_"
            },
            {
                data: 'upah',
                name: 'upah',
                searchable: false,
                className: "text-center td-height-img komisi-terapis_"
            },
            {
                data: 'total_komisi',
                name: 'total_komisi',
                searchable: false,
                className: "text-center td-height-img",
                render: function (data, type, row) {
                    return convertRupiah(data)
                }
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
    load_DetailKom();

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

}

function fill_field_daterange(picker) {
    var NewDataGetStart = picker.startDate.format('YYYY-MM-DD');
    var NewDataGetEnd = picker.endDate.format('YYYY-MM-DD');

    $("input[name='berlaku_dari']").val(picker.startDate.format('DD-MM-YYYY')).attr('data-date-start', NewDataGetStart);
    $("input[name='berlaku_sampai']").val(picker.endDate.format('DD-MM-YYYY')).attr('data-date-end', NewDataGetEnd);

    $('#data-table-view').DataTable().ajax.url(base_url + "/therapists/fee/json?starts=" + NewDataGetStart +
        "&ends=" + NewDataGetEnd).load();
}

function remove_field_daterange() {
    $("input[name='berlaku_dari']").val('').removeAttr('data-date-start');
    $("input[name='berlaku_sampai']").val('').removeAttr('data-date-end');

    $('#data-table-view').DataTable().ajax.url(base_url +
        "/therapists/fee/json").load();
}

function detail_attribute(id, e) {
    var dateStart = $("input[name='berlaku_dari']").attr('data-date-start');
    var dateEnd = $("input[name='berlaku_sampai']").attr('data-date-end');

    var dTable = $('#data-table-view-detail-' + id).DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: e + (dateStart && dateEnd ? '?starts=' + dateStart + '&ends=' + dateEnd : ''),
            type: 'GET',
        },
        "fnDrawCallback": function () {
            var api = this.api()
            var json = api.ajax.json();
            KomisiLayanan(json);
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false,
                className: "text-center td-height-img urutan-layanan",

            },
            {
                data: 'no_transaksi',
                name: 'no_transaksi',
                orderable: false,
                searchable: false,
                className: "text-center td-height-img",
                render: function (data, type, row) {
                    return !data ? '<div style="width:35%; margin-right:25px; float:right; border-left:1px dotted #000; border-bottom:1px dotted #000;">&nbsp;</div>' : data;
                }
            },
            {
                data: 'nama',
                name: 'nama',
                orderable: false,
                searchable: false,
                className: "td-height-img nama-layanan",
                render: function (data, type, row) {
                    return row.paket_id ? `<label class="btn btn-xs btn-success">( ` + row.paket + ` )</label> <p>` + row.nama + `</p>` : `<p>` + row.nama + `</p>`;
                }
            },
            {
                data: 'harga',
                name: 'harga',
                orderable: false,
                searchable: false,
                className: "td-height-img text-right",
                render: function (data, type, row) {
                    return convertRupiah(data)
                }
            },
            {
                data: 'komisi',
                name: 'komisi',
                orderable: false,
                searchable: false,
                className: "text-center td-height-img"
            },
            {
                data: 'sub_komisi',
                name: 'sub_komisi',
                searchable: false,
                orderable: false,
                className: "text-right td-height-img komisi-layanan",
                render: function (data, type, row) {
                    return convertRupiah(data)
                }
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
}

function KomisiLayanan(json) {
    $(".jumlah-layanan, .total-komisi").html('<em class="fa fa-spin fa-spinner"></em>')
    setTimeout(() => {
        $(".jumlah-layanan").html(convertRupiah(json.total_layanan));
        $(".total-komisi").html(convertRupiah(json.total_komisi));
    }, 1000);
}

function downloadReport(st, en) {
    var http = new XMLHttpRequest();
    http.responseType = 'blob';

    var blob;
    var url = base_url + '/therapists/fee/export' + (st && en ? '?starts=' + st + '&ends=' + en : '');
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
    $(".export-komisi").on('click', function () {
        var start = $("input[name='berlaku_dari']").attr('data-date-start')
        var end = $("input[name='berlaku_sampai']").attr('data-date-end')

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
