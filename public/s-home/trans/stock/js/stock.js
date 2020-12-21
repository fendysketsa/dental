function load_formEdit() {
    $('tbody').delegate('.edit', 'click', function () {
        $('.load-form-modal').html('')
        var event = $(this);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });

        $.ajax({
            type: 'GET',
            url: event.data('route'),
            success: function (result) {
                $('.load-form-modal').html(result);
                $('.modal-title').html('<em class="fa fa-pencil-square-o"></em> Form Stok Management')
                form_attribute()
            },
            error: function () {
                toastr.error('Gagal mengambil data', 'Oops!', {
                    timeOut: 2000
                });
            }
        });
    });
    setTimeout(function () {
        submit();
    }, 500);
}

function load_detail() {
    $('tbody').delegate('.detail', 'click', function () {
        $('.load-detail-modal').html('')
        var event = $(this);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });

        $.ajax({
            type: 'GET',
            url: event.data('route'),
            success: function (result) {
                $('.load-detail-modal').html(result);
                $('.modal-title').html('<em class="fa fa-th-large"></em> History Stok Produk')
                load_history(event.data('id-produk'))
            },
            error: function () {
                toastr.error('Gagal mengambil data', 'Oops!', {
                    timeOut: 2000
                });
            }
        });
    });
}

function form_attribute() {
    var delay = (function () {
        var timer = 0;
        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();
    $("input[name=stok]").on('input', function () {
        var values = $(this).val();
        var last_stok = parseInt($(".last-stok").text());

        if (!values) {
            delay(function () {
                $(".keterangan-opname").html("")
            }, 0);
            return false;
        } else {
            if (values < last_stok) {
                $(".keterangan-opname").html("<td colspan='3'><small><em class='fa fa-refresh fa-spin'></em> Loading...</small></td>")
                delay(function () {
                    $(".keterangan-opname").html(keterangan())
                }, 500);
            } else {
                delay(function () {
                    $(".keterangan-opname").html("")
                }, 0);
                return false;
            }
        }
    });
}

function keterangan() {
    var html = `<td colspan="3">
                    <div class="form-group input-group-sm">
                        <textarea name="keterangan" cols="15" rows="4" class="form-control add-style" style="height:120px;" placeholder="Keterangan..."
                        form="formStockManagement"></textarea>
                    </div>
                </td>`;
    return html;
}

function load_data() {
    var cont = $(".load-data");
    cont.load(base_url + '/stocks/data', function (e, s, f) {
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

function load_history(idProduk) {
    var cont = $(".load-history");
    cont.load(base_url + '/stocks-history/data', function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat data!';
            toastr.error(fls, 'Oops!', {
                timeOut: 2000
            })
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            data_attribut_history(idProduk);
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

    var jam = elemnt.getHours();
    var menit = elemnt.getMinutes() < 10 ? "0" + elemnt.getMinutes() : elemnt.getMinutes();

    return hari + ', ' + tanggal + ' ' + bulan + ' ' + tahun + ' ' + jam + ':' + menit;

}

function getImg(data, type, full, meta) {
    var img = !data ? '/images/noimage.jpg' : '/s-home/master-data/product/uploads/' + data;
    return '<img width="100" height="55" src="' + base_url + img + '">';
}

function data_attribut() {

    var dTable = $('#data-table-view').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/stocks/json",
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
                data: 'gambar',
                name: 'gambar',
                className: "td-height-img",
                render: getImg,
            },
            {
                data: 'nama',
                name: 'nama',
                className: "td-height-img"
            },
            {
                data: 'kategori',
                name: 'kategori',
                className: "td-height-img"
            },
            {
                data: 'updated_at',
                name: 'updated_at',
                className: 'td-height-img',
                render: function (data, type, row) {
                    return data ? getIndoDate(data) : '';
                }
            },
            {
                data: 'stok',
                name: 'stok',
                className: "td-height-img"
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
    load_formEdit();
    load_detail();
}

function data_attribut_history(idProduk) {

    var dTable = $('#data-table-view-history').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/stocks-history/json?id_produk=" + idProduk,
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
                className: "td-height-img",
                render: function (data, type, row) {
                    return data ? getIndoDate(data) : '';
                }
            },
            {
                data: 'masuk',
                name: 'masuk',
                className: "td-height-img"
            },
            {
                data: 'keluar',
                name: 'keluar',
                className: 'td-height-img'
            },
            {
                data: 'sisa',
                name: 'sisa',
                className: "td-height-img"
            },
            {
                data: 'keterangan',
                name: 'keterangan',
                className: "td-height-img",
                orderable: false,
                render: function (data, type, row) {
                    return data.toString().split("&lt;").join('<').split("&gt;").join('>');
                }
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

function submit() {

    $("form#formStockManagement").submit(function (e) {
        e.preventDefault();

        var event = $("form#formStockManagement")[1];
        var close_modal = function () {
            $(".modal").modal('hide');
        }
        var data = new FormData($("form#formStockManagement")[0]);
        var url = event.action;

        $(".preloader").fadeIn();
        $(".modal-content").addClass('mod-cont-blur');
        $(".modal-body").addClass('mod-bod-blur');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });

        $.ajax({
            url: url,
            data: data,
            contentType: false,
            processData: false,
            type: 'POST',
            dataType: 'JSON',
            cache: false,
            success: function (data) {
                switch (data.cd) {
                    case 200:
                        $(".preloader").fadeOut('fast', close_modal);
                        $('table#data-table-view').DataTable().ajax.reload();
                        toastr.success(data.msg, 'Success!', {
                            timeOut: 2000
                        })
                        break;
                    default:
                        toastr.warning(data.msg, 'Peringatan!', {
                            timeOut: 2000
                        })
                        break;
                }
            },
            error: function () {
                var timer = 5; // timer in seconds
                (function customSwal() {
                    swal({
                        title: "Kesalahan sistem!",
                        text: "Sistem error, menutup otomatis pada " + timer + ' detik !',
                        timer: timer * 1000,
                        button: false,
                        icon: base_url + '/images/icons/loader.gif',
                        closeOnClickOutside: false,
                        closeOnEsc: false
                    }).then(() => {
                        setTimeout(function () {
                            swal.close()
                        }, 1000)
                    });

                    if (timer) {
                        timer--;
                        if (timer > 0) {
                            setTimeout(customSwal, 1000);
                        }
                    }
                })();
            },
            complete: function () {
                $(".preloader").fadeOut();
                setTimeout(function () {
                    $(".modal-content").removeClass('mod-cont-blur');
                    $(".modal-body").removeClass('mod-bod-blur');
                }, 500);
            }
        });
    });
}

$(document).ready(function () {
    load_data();
});
