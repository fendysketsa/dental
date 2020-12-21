$(".load-data").delegate('.edit', 'click', function (e) {
    var event = $(this);
    load_formEdit(event);
});

function refresh_action_table() {
    var ths = $('tbody');
    var attrbt = ths.find('a.edit');
    attrbt.each(function (e, f) {
        $(f).closest('tr').find('a.btn-remove-id').removeClass('disabled').attr('data-route', $(f).data('route'));
    });
}

$(".reload-on-table").on('click', function () {
    var ins = $("input[name=id]").val() || 0;
    var response_load_dt = function () {
        $(".btn-id-" + ins).addClass('disabled').removeAttr('data-route');
    };
    $('tbody').closest('table').DataTable().ajax.reload(response_load_dt);
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
        url: base_url + "/promos/option",
        method: "POST",
        data: {
            table: table
        },
        dataType: 'json',
        success: function (data) {
            var html = `<option></option>`;
            var i;
            for (i = 0; i < data.length; i++) {
                var selected = data[i].id == id_ ? "selected" : '';
                html += `<option ` + selected + ` value='` + data[i].id + `'>` + data[i].nama + `</option>`;
            }
            $("select[name=" + table + "]").html(html);
        }
    });
}

function load_formAdd() {
    var cont = $(".load-form");
    $(".display-future").addClass('blocking-content');
    cont.load(base_url + '/promos/add', function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat form!';
            toastr.error(fls, 'Oops!', {
                timeOut: 2000
            })
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            $(".display-future").removeClass('blocking-content');
            $(".button-action").removeClass('hide');
            form_attribut();
            submit();
        }
    });
}

function load_formEdit(e) {
    var elemtTb = $('#data-table-view').DataTable();
    var dTbPageInfo = elemtTb.page.info().page;

    var cont = $(".load-form");
    var ths = $(e);
    $(".display-future").addClass('blocking-content');

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
            $(".display-future").removeClass('blocking-content');
            $(".button-action").removeClass('hide');
            var attrbt = ths.parents('div.btn-group').find('a.btn-remove-id');
            attrbt.addClass('disabled');
            attrbt.removeAttr('data-route');
            var iD_branch = $("select[name=cabang]").data('selected');
            form_attribut(iD_branch);
            submit(dTbPageInfo);
        },
        error: function () {
            toastr.error('Gagal mengambil data', 'Oops!', {
                timeOut: 2000
            });
        }
    });
}

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

function addOn(date) {
    $('.on-date input').val(date.format('DD-MM-YYYY'));
}

function form_attribut(id) {
    select_(id, 'cabang');
    $('.select2').select2({
        placeholder: "Semua Cabang",
        allowClear: true,
        theme: "bootstrap"
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

    $('.add-on-daterpicker').daterangepicker({
        alwaysShowCalendars: true,
        showCustomRangeLabel: true,
        minDate: moment().add(0, 'd').toDate(),
        startDate: !$("input[name=id]").val() ? moment().add(0, 'd').toDate() : $("input[name=berlaku_dari]").val(),
        endDate: !$("input[name=id]").val() ? moment().add(9, 'd').toDate() : $("input[name=berlaku_sampai]").val(),
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

    setTimeout(function () {
        $(function () {
            CKEDITOR.replace('deskripsi', {
                height: '25em'
            })
        })
    }, 1000);
}

function fill_field_daterange(picker) {
    $("input[name='berlaku_dari']").val(picker.startDate.format('DD-MM-YYYY'));
    $("input[name='berlaku_sampai']").val(picker.endDate.format('DD-MM-YYYY'));
}

function remove_field_daterange() {
    $("input[name='berlaku_dari']").removeAttr('value').val('').trigger('change');
    $("input[name='berlaku_sampai']").removeAttr('value').val('').trigger('change');
}

function load_data() {
    var cont = $(".load-data");
    $(".button-action").addClass('hide');
    $(".display-future").addClass('blocking-content');
    cont.load(base_url + '/promos/data', function (e, s, f) {
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

function data_attribut() {
    var response_load_dt = function () {
        var ins = $("input[name=id]").val() || 0;
        $(".btn-id-" + ins).addClass('disabled').removeAttr('data-route');
    }

    var dTable = $('#data-table-view').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/promos/json",
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
                data: 'gambar',
                name: 'gambar',
                render: getImg,
                className: "text-center td-height-img"
            },
            {
                data: 'cabang',
                name: 'cabang',
                className: 'td-height-img',
                render: getCabang
            },
            {
                data: 'tanggal_dari',
                name: 'tanggal_dari',
                className: "text-center td-height-img",
                render: function (data, type, row) {
                    return data ? getIndoDate(data) : '';
                }
            },
            {
                data: 'tanggal_sampai',
                name: 'tanggal_sampai',
                className: "text-center td-height-img",
                render: function (data, type, row) {
                    return data ? getIndoDate(data) : '';
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
        dTable.ajax.reload(response_load_dt);
    });
    $("input[type=search]").on('input', function (e) {
        dTable.ajax.reload(response_load_dt);
    });

    remove();
}

function getCabang(data, type, full, meta) {
    return !data ? 'Semua Cabang' : data;
}

function imgError(image) {
    image.onerror = "";
    image.src = "/images/brokenimage.jpg";
    image.alt = "Images corrupt!";
    image.title = "Images corrupt!";
    return true;
}

function getImg(data, type, full, meta) {
    var img = !data ? '/images/noimage.jpg' : '/storage/master-data/promo/uploads/' + data;
    return '<img onerror="imgError(this);" width="130" height="60" src="' + base_url + img + '">';
}

function remove() {
    $('tbody').delegate('.btn-remove-id', 'click', function () {
        var event = $(this);
        var target = event.data('route');
        var tables = event.closest('table');

        var ins = $("input[name=id]").val() || 0;
        var response_load_dt = function () {
            $(".btn-id-" + ins).addClass('disabled').removeAttr('data-route');
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
    $("form#formPromo").submit(function (e) {
        e.preventDefault();
        CKEDITOR.instances['deskripsi'].updateElement();

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
                        setTimeout(function () {
                            $(".display-future").removeClass('blocking-content');
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
