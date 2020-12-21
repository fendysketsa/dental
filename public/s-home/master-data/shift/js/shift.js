$(".load-data").delegate('.edit', 'click', function (e) {
    var event = $(this);
    load_formEdit(event);
});

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

function refresh_action_table() {
    var ths = $('tbody');
    var attrbt = ths.find('a.edit');
    attrbt.each(function (e, f) {
        $(f).closest('tr').find('a.btn-remove-id').removeClass('disabled').attr('data-route', $(f).data('route'));
    });
}

function load_formAdd() {
    var cont = $(".load-form");
    $(".display-future").addClass('blocking-content');
    cont.load(base_url + '/set/shifts/create', function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat form!';
            toastr.error(fls, 'Oops!', { timeOut: 2000 })
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
            form_attribut();
            $(".display-future").removeClass('blocking-content');
            $(".button-action").removeClass('hide');
            var attrbt = ths.parents('div.btn-group').find('a.btn-remove-id');
            attrbt.addClass('disabled');
            attrbt.removeAttr('data-route');
            submit();
        }, error: function () {
            toastr.error('Gagal mengambil data', 'Oops!', { timeOut: 2000 });
        }
    });
}

function load_data() {
    var cont = $(".load-data");
    $(".button-action").addClass('hide');
    $(".display-future").addClass('blocking-content');
    cont.load(base_url + '/set/shifts/data', function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat data!';
            toastr.error(fls, 'Oops!', { timeOut: 2000 })
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            $(".display-future").removeClass('blocking-content');
            $(".button-action").removeClass('hide');
            data_attribut();
        }
    });
}

function form_attribut() {
    $('.timepicker').timepicker({
        showInputs: false,
        showMeridian: false,
    });
    $('#cpLabelShift').colorpicker();
}

function getIndoMinute(minute) {
    var splt_elem = minute.split(' - ');
    var html = '';
    var waktu = '';
    var count = splt_elem.length;
    $.each(splt_elem, function (e, f) {
        var elem_minut = splt_elem[e].split(':');
        $.each(elem_minut, function (ee, ff) {
            var jam = elem_minut[0];
            var menit = elem_minut[1];
            var ket = null;
            var space = ((e + 1) == count) ? "" : " - ";
            switch (true) {
                default:
                    ket = " Dini Hari"
                    break;
                case (jam >= 3 && jam <= 10):
                    ket = " Pagi"
                    break;
                case (jam >= 11 && jam <= 14):
                    ket = " Siang"
                    break;
                case (jam >= 15 && jam <= 17):
                    ket = " Sore"
                    break;
                case (jam >= 18 && jam <= 19):
                    ket = " Petang"
                    break;
                case (jam >= 20 && jam <= 24):
                    ket = " Malam"
                    break;
            }
            waktu = jam + ':' + menit + ket + space;
        })
        html += waktu;
    })
    return html;
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
            url: base_url + "/set/shifts/json",
            type: 'GET',
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nama', name: 'nama' },
            {
                data: 'waktu', name: 'waktu',
                render:
                    function (data, type, row) {
                        return data ? getIndoMinute(data) : '';
                    }
            },
            { data: 'action', name: 'action', orderable: false, className: "text-center" },
        ],
        order: [[0, 'desc']]
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
                                toastr.success(data.msg, 'Success!', { timeOut: 2000 })
                                break;
                            default:
                                toastr.warning(data.msg, 'Peringatan!', { timeOut: 2000 })
                                break;
                        }
                    },
                    error: function () {
                        toastr.error('Kesalahan system!', 'Error!', { timeOut: 2000 })
                    }
                });
            } else {
                swal.close()
            }
        });
    });
}

function submit() {
    $("form#formSetShift").submit(function (e) {
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
                        toastr.success(data.msg, 'Success!', { timeOut: 2000 })
                        break;
                    default:
                        $(".display-future").removeClass('blocking-content');
                        toastr.warning(data.msg, 'Peringatan!', { timeOut: 2000 })
                        break;
                }
            },
            error: function () {
                var timer = 5;// timer in seconds
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
