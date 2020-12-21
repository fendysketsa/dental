$(".load-data").delegate('.edit', 'click', function (e) {
    var event = $(this);
    load_formEdit(event);
});

$(".button-action").delegate('.cancel-form', 'click', function () {
    load_formAdd();
    refresh_action_table();
});

function select_(id, table) {
    var id_ = id || '';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

    $.ajax({
        url: base_url + "/packages/option",
        method: "POST",
        data: {
            table: table
        },
        dataType: 'json',
        success: function (data) {
            if (table == 'layanan' && $('input[name=id]').val()) {
                setTimeout(function () { //harus pake ini
                    var elemt = $("select.layanan").data('selected');
                    var id_mul = elemt.toString().length == 1 ? elemt : elemt.split(',');
                    $("select.layanan").val(id_mul).select2({
                        theme: "bootstrap",
                        templateResult: formatState
                    });
                }, 0); //meskipun 0
            }
            var html = `<option></option>`;
            var i;
            for (i = 0; i < data.length; i++) {
                var selected = table == 'cabang' ? (data[i].id == id_ ? "selected" : '') : '';
                var gambar = table == 'layanan' ? ` data-gambar='` + data[i].gambar + `'` : '';
                html += `<option ` + selected + gambar + ` value='` + data[i].id + `'>` + data[i].nama + `</option>`;
            }
            $("select." + table).html(html);
        }
    });
}

function load_formAdd() {
    var cont = $(".load-form");
    $(".display-future").addClass('blocking-content');
    cont.load(base_url + '/packages/add', function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat form!';
            toastr.error(fls, 'Oops!', {
                timeOut: 2000
            })
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            form_attribut();
            $(".display-future").removeClass('blocking-content');
            $(".button-action").removeClass('hide');
            submit();
        }
    });
}

function load_formEdit(e) {
    var elemtTb = $('#data-table-view').DataTable();
    var dTbPageInfo = elemtTb.page.info().page;

    var cont = $(".load-form");
    $(".display-future").addClass('blocking-content');
    var ths = $(e);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

    $.ajax({
        type: 'PUT',
        url: ths.data('route'),
        success: function (result) {
            refresh_action_table();
            cont.html(result);
            var iD_branch = $("select[name=cabang]").data('selected');
            var iD_services = $("select[name=layanan]").data('selected');
            form_attribut(iD_branch, iD_services);
            $(".display-future").removeClass('blocking-content');
            $(".button-action").removeClass('hide');

            var attrbt = ths.parents('div.btn-group').find('a.btn-remove-id');
            attrbt.addClass('disabled');
            attrbt.removeAttr('data-route');

            submit(dTbPageInfo);
        },
        error: function () {
            toastr.error('Gagal mengambil data', 'Oops!', {
                timeOut: 2000
            });
        }
    });
}

function refresh_action_table() {
    var ths = $('tbody');
    var attrbt = ths.find('a.edit');
    attrbt.each(function (e, f) {
        $(f).closest('tr').find('a.btn-remove-id').removeClass('disabled').attr('data-route', $(f).data('route'));
    });
}

function formatState(state) {
    if (!state.id) {
        return state.text;
    }
    var img_load = '/storage/master-data/service/uploads/'
    var img = state.element.attributes[0].value == 'null' ? "/images/noimage.jpg" : img_load + state.element.attributes[0].value;

    var $state = $(
        '<span><img onerror="imgError(this);" width="50" height="40" src="' + base_url + img + '" /> ' + state.text + '</span>'
    );

    return $state;
};

function changeProfile() {
    $('#file').click();
}

function readPreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#preview_image').attr('src', e.target.result);
            $('#file_name').val(e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removeFile() {
    if ($('#file_name').val() != '') {
        $('#preview_image').attr('src', base_url + '/images/noimage.jpg');
        $('#file_name').val('');
        $("#file").val('');
    }
}

function form_attribut(id, id2) {
    select_(id, 'cabang');
    select_(id2, 'layanan');
    $('.select2').select2({
        placeholder: "Please select!",
        allowClear: true,
        theme: "bootstrap"
    });
    $('.select2-multiple').select2({
        placeholder: "Please select!",
        theme: "bootstrap",
        templateResult: formatState
    });

    $('#file').change(function () {
        if ($(this).val() != '') {
            var file = this.files[0];
            var imagefile = file.type;
            var match = ["image/jpeg", "image/png", "image/jpg"];
            if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
                $('#preview_image').attr('src', base_url + '/images/noimage.jpg');
                $('#file_name').val('');
                $("#file").val('');
                var fls = "Pilih gambar yang sesuai!, hanya diperbolehkan format jpeg, jpg and png!</ul>";
                toastr.warning(fls, 'Oops!', {
                    timeOut: 2000
                })
                return false;
            } else {
                readPreview(this);
            }
        }
    });

    onInputRupiah()
}

function load_data() {
    var cont = $(".load-data");
    $(".button-action").addClass('hide');
    $(".display-future").addClass('blocking-content');
    cont.load(base_url + '/packages/data', function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat data!';
            toastr.error(fls, 'Oops!', {
                timeOut: 2000
            })
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            $(".display-future").removeClass('blocking-content');
            $(".button-action").removeClass('hide');
            data_attribut();
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
    var img = !data ? '/images/noimage.jpg' : '/storage/master-data/package/uploads/' + data;
    return '<img onerror="imgError(this);" width="80" height="60" src="' + base_url + img + '">';
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

function onInputRupiah() {
    $("input[type=rupiah]").on('input', function (e) {
        var inpValue = $(this).val().replace(/[^,\d]/g, "").toString();

        if (isNaN(inpValue)) {
            $(this).val('0');
            return false;
        }

        if (inpValue < 1) {
            $(this).val('');
            toastr.warning('Tidak diperkenankan input angka 0 di depan!', 'Ooopps!', {
                timeOut: 2000
            });
            return false;
        }

        var Inp = inpValue.replace(/^0/gi, '');
        $(this).val(convertRupiah(Inp));
    });
}

function data_attribut() {
    var response_load_dt = function () {
        var ins = $("input[name=id]").val() || 0;
        $(".btn-id-" + ins).addClass('disabled').removeAttr('data-load');
    }

    var dTable = $('#data-table-view').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/packages/json",
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
                render: getImg,
                className: "td-height-img"
            },
            {
                data: 'cabang',
                name: 'cabang',
                className: "td-height-img"
            },
            {
                data: 'nama',
                name: 'nama',
                className: "td-height-img"
            },
            {
                data: 'harga',
                name: 'harga',
                className: "td-height-img",
                render: convertRupiah
            },
            {
                data: 'keterangan',
                name: 'keterangan',
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
        dTable.ajax.reload(response_load_dt);
    });
    $("input[type=search]").on('input', function (e) {
        dTable.ajax.reload(response_load_dt);
    });
    remove();
}

function remove() {
    $('tbody').delegate('.btn-remove-id', 'click', function () {
        var event = $(this);
        var target = event.data('route');
        var tables = event.closest('table');

        var ins = $("input[name=id]").val() || 0;
        var response_load_dt = function () {
            $(".btn-id-" + ins).addClass('disabled').removeAttr('data-load');
        };

        swal({
            title: "Menghapus data?",
            text: "Data yang dihapus tidak bisa dikembalikan.",
            icon: "warning",
            buttons: ["Batal", "Ok"],
            dangerMode: true,
        }).then(function (willExec) {
            if (willExec) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    }
                });
                $.ajax({
                    url: target,
                    type: 'DELETE',
                    dataType: 'JSON',
                    success: function (data) {
                        switch (data.cd) {
                            case 200:
                                tables.DataTable().ajax.reload(response_load_dt);
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
                        toastr.error('Kesalahan system!', 'Error!', {
                            timeOut: 2000
                        })
                    }
                });
            } else {
                swal.close()
            }
        });
    });
}

function submit(page) {
    $("form#formPaket").submit(function (e) {
        e.preventDefault();

        var event = $(this)[0];

        $(".display-future").addClass('blocking-content');
        var data = new FormData(event);
        var url = event.action;

        var reload_form = function () {
            load_formAdd();
        }

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
            success: function (data) {
                switch (data.cd) {
                    case 200:
                        $('table#data-table-view').DataTable().ajax.reload(reload_form);
                        if (page) {
                            $('table#data-table-view').DataTable().page(page).draw('page');
                        }
                        toastr.success(data.msg, 'Success!', {
                            timeOut: 2000
                        })
                        break;
                    default:
                        $(".display-future").removeClass('blocking-content');
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
                        $(".display-future").removeClass('blocking-content');
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
            }
        });
    });
}

$(document).ready(function () {
    load_formAdd();
    load_data();
});
