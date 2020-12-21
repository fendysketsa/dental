$(".load-data").delegate('.detail', 'click', function (e) {
    var event = $(this);
    load_detail(event);
});


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
    return !bilangan_ ? 0 : rupiah;
}

function currentDate() {
    var today = new Date();
    var date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    return date + ' ' + time;
}



function load_data() {
    var cont = $(".load-data");
    cont.load(base_url + '/members-info/data', function (e, s, f) {
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

function load_detail_data_left(table, idm) {
    var cont = $(".load-detail-history-" + table);
    cont.load(base_url + '/members-info/data/history?table=' + table, function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat data!';
            toastr.error(fls, 'Oops!', {
                timeOut: 2000
            })
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            data_attribut_history_left('layanan', idm);
        }
    });
}

function load_detail_data_right(table, idm) {
    var cont = $(".load-detail-history-" + table);
    cont.load(base_url + '/members-info/data/history?table=' + table, function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat data!';
            toastr.error(fls, 'Oops!', {
                timeOut: 2000
            })
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            data_attribut_history_right('produk', idm);
        }
    });
}

function load_detail(e) {
    var ths = $(e);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'GET',
        url: ths.data('route'),
        success: function (result) {
            $('.modal-title').html('<em class="fa fa-pencil-square-o"></em> History Transaksi Dan Reservasi')
            load_detail_data_left('layanan', ths.data('id-member'))
            load_detail_data_right('produk', ths.data('id-member'))
        },
        error: function () {
            toastr.error('Gagal mengambil data', 'Oops!', {
                timeOut: 2000
            });
        }
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
    var img = !data ? '/images/noimage.jpg' : '/storage/master-data/member/uploads/' + data;
    return '<img onerror="imgError(this);" width="70" height="70" style="border-radius:50%;" src="' + base_url + img + '">';
}

function data_attribut_history_left(table, member) {
    var dTable_left = $('#data-table-view-' + table).DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/members-info/json/history?table=" + table + "&member=" + member,
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
                data: 'waktu',
                name: 'waktu',
                className: "text-center",
                render: function (data, type, row) {
                    return data ? getIndoDate(data) : '';
                }
            },
            {
                data: 'nama',
                name: 'nama',
                className: 'td-height-img'
            },
            {
                data: 'harga',
                name: 'harga',
                className: 'td-height-img',
                render: convertRupiah
            },

        ],
        order: [
            [0, 'desc']
        ]
    });
    dTable_left.ajax.reload();
    $("select[name=data-table-view_length]").on('change', function () {
        dTable_left.ajax.reload();
    });
    $("input[type=search]").on('input', function (e) {
        dTable_left.ajax.reload();
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

function data_attribut_history_right(table, member) {
    var dTable_right = $('#data-table-view-' + table).DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/members-info/json/history?table=" + table + "&member=" + member,
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
                data: 'waktu',
                name: 'waktu',
                className: "text-center",
                render: function (data, type, row) {
                    return data ? getIndoDate(data) : '';
                }
            },
            {
                data: 'nama',
                name: 'nama',
                className: 'td-height-img'
            },
            {
                data: 'harga',
                name: 'harga',
                className: 'td-height-img',
                render: convertRupiah
            },
            {
                data: 'jumlah',
                name: 'jumlah',
                className: "text-center"
            },
        ],
        order: [
            [0, 'desc']
        ]
    });
    dTable_right.ajax.reload();
    $("select[name=data-table-view_length]").on('change', function () {
        dTable_right.ajax.reload();
    });
    $("input[type=search]").on('input', function (e) {
        dTable_right.ajax.reload();
    });
}

function data_attribut() {

    var dTable = $('#data-table-view').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/members-info/json",
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
                className: 'td-height-img'
            },
            {
                data: 'no_member',
                name: 'no_member',
                className: 'td-height-img'
            },
            {
                data: 'nama',
                name: 'nama',
                className: 'td-height-img'
            },
            {
                data: 'jenis_kelamin',
                name: 'jenis_kelamin',
                className: 'td-height-img'
            },
            {
                data: 'kelahiran',
                name: 'kelahiran',
                className: 'td-height-img'
            },
            {
                data: 'email',
                name: 'email',
                className: 'td-height-img'
            },
            {
                data: 'telepon',
                name: 'telepon',
                className: 'td-height-img'
            },
            {
                data: 'saldo',
                name: 'saldo',
                className: 'td-height-img'
            },
            {
                data: 'count_trans',
                name: 'count_trans',
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

}

$(document).ready(function () {
    load_data();
});
