$(".load-form").delegate('select[name=param]', 'change', function (ev) {
    var event = $(this);
    case_param(event);
});

$(".load-form").delegate('.select2-selection__rendered', 'click', function (prd) {
    $(".select2-results__option[aria-disabled=true]").css({
        'color': 'red'
    });
});

$(".button-action").delegate('.cancel-form', 'click', function () {
    load_formAdd();
    refresh_action_table();
});

$(".load-data").delegate('.edit', 'click', function (e) {
    var event = $(this);
    load_formEdit(event);
});

function cek_load() {
    case_param($("select[name=param]"));
}

function load_formAdd() {
    var cont = $(".load-form");
    $(".display-future").addClass('blocking-content');
    cont.load(base_url + '/discounts/add', function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat form!';
            toastr.error(fls, 'Oops!', {
                timeOut: 2000
            })
            $(".load-form").html('<div class="box-body">' + fls + '</div>');
        } else {
            $(".display-future").removeClass('blocking-content');
            $(".button-action").removeClass('hide');
            form_attribut();
            submit()
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
            $(".display-future").removeClass('blocking-content');
            $(".button-action").removeClass('hide');
            var iD_product = $("select[name=product]").data('selected');
            var iD_services = $("select[name=services]").data('selected');
            form_attribut(iD_product, iD_services);
            var attrbt = ths.parents('div.btn-group').find('a.btn-remove-id');
            attrbt.addClass('disabled');
            attrbt.removeAttr('data-route');

            $('.product').on("select2:unselect", function (e) {
                if (!e.params.originalEvent) {
                    return
                }
                e.params.originalEvent.stopPropagation();
            });

            submit(dTbPageInfo);
        },
        error: function () {
            toastr.error('Gagal mengambil data', 'Oops!', {
                timeOut: 2000
            });
        }
    });
}

function matchCustom(params, data) {
    if ($.trim(params.term) === '') {
        return data;
    }

    if (typeof data.children === 'undefined') {
        return null;
    }

    var filteredChildren = [];
    $.each(data.children, function (idx, child) {
        if (child.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
            filteredChildren.push(child);
        }
    });

    if (filteredChildren.length) {
        var modifiedData_ = $.extend({}, data, true);
        modifiedData_.children = filteredChildren;
        return modifiedData_;
    }
    return null;
}

function select_(id, table) {
    var id_ = id || '';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

    $.ajax({
        url: base_url + "/discounts/option",
        method: "POST",
        data: {
            table: table
        },
        dataType: 'json',
        success: function (data) {
            if ($('input[name=id]').val()) {
                setTimeout(function () {
                    $('.product').val($(".product").data('selected')).select2()
                    $('.select2-multiple').select2({
                        placeholder: "Please select!",
                        theme: "bootstrap",
                        templateResult: formatState
                    });
                }, 500)

                setTimeout(function () {
                    $('.services').val($(".services").data('selected')).select2()
                    $('.select2-multiple-services').select2({
                        placeholder: "Please select!",
                        allowClear: true,
                        theme: "bootstrap",
                        matcher: matchCustom
                    });
                }, 1000)
            }

            var html = `<option></option>`;
            var i;
            for (i = 0; i < data.length; i++) {

                if (table == 'services') {
                    html += `<optgroup label="` + data[i].nama + `">`;
                    for (var ii = 0; ii < data[i].data.length; ii++) {

                        var diskon_ = !data[i].data[ii].layanan_discount ?
                            ` value='` + data[i].data[ii].id + `'` : (data[i].data[ii].layanan_discount && $('input[name=id]').val() == data[i].data[ii].grp1_discount ?
                                ` ` : ` disabled`);
                        var diskon_my_ = $('input[name=id]').val() == data[i].data[ii].grp1_discount ? ` value='` + data[i].data[ii].id + `'` : ` `;
                        var diskon_lain_ = (data[i].data[ii].layanan_discount && $('input[name=id]').val() == data[i].data[ii].grp1_discount) ? ` class='my-edit1'` : '';

                        var usediskon_ = !data[i].data[ii].layanan_discount ?
                            "" : ($('input[name=id]').val() == data[i].data[ii].grp1_discount ?
                                ` (my diskon)` : ($('input[name=id]').val() ?
                                    ` (in other diskon)` : ` (in diskon)`));

                        setTimeout(function () {
                            $(".services").find('option.my-edit1').removeAttr('disabled')
                        }, 800);

                        var harga_ = table == 'layanan' ? `data-harga='` + data[i].data[ii].harga + `'` : '';
                        html += `<option ` + harga_ + diskon_ + diskon_my_ + diskon_lain_ + ` alt="` + data[i].data[ii].nama + `" value='` + data[i].data[ii].id + `'>` + data[i].data[ii].nama + usediskon_ + `</option>`;
                    }
                    html += `</optgroup>`;
                } else {

                    var gambar = table == 'product' ? ` data-gambar='` + data[i].gambar + `'` : '';
                    var diskon = !data[i].produk_discount ?
                        ` value='` + data[i].id + `'` : (data[i].produk_discount && $('input[name=id]').val() == data[i].grp_discount ?
                            ` ` : ` disabled`);
                    var diskon_my = $('input[name=id]').val() == data[i].grp_discount ? ` value='` + data[i].id + `'` : ` `;
                    var diskon_lain = (data[i].produk_discount && $('input[name=id]').val() == data[i].grp_discount) ? ` class='my-edit'` : '';

                    var usediskon = !data[i].produk_discount ?
                        "" : ($('input[name=id]').val() == data[i].grp_discount ?
                            ` (my diskon)` : ($('input[name=id]').val() ?
                                ` (in other diskon)` : ` (in diskon)`));

                    setTimeout(function () {
                        $(".product").find('option.my-edit').removeAttr('disabled')
                    }, 600);

                    html += `<option ` + gambar + diskon + diskon_my + diskon_lain + `>` + data[i].nama + usediskon + `</option>`;
                }
            }
            $("select." + table).html(html);
        }
    });
}

function load_data() {
    var cont = $(".load-data");
    $(".display-future").addClass('blocking-content');
    $(".button-action").addClass('hide');
    cont.load(base_url + '/discounts/data', function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat data!';
            toastr.error(fls, 'Oops!', {
                timeOut: 2000
            })
            $(".load-data").html('<div class="box-body">' + fls + '</div>');
        } else {
            $(".display-future").removeClass('blocking-content');
            $(".button-action").removeClass('hide');
            data_attribut();
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

function case_param(value) {
    var type_input = $('.currency-percent');
    switch (value.val()) {
        case 'Rp':
            $(".param-method").removeAttr('class').addClass('fa param-method text-bold').text('Rp');
            type_input.removeAttr('min max step type').attr({
                'type': 'rupiah'
            }).removeClass('percentage');
            break;
        case '%':
            change_percent_max(type_input);
            $(".param-method").removeAttr('class').addClass('fa fa-percent param-method').text('');
            type_input.removeAttr('type').attr({
                'type': 'number',
                'min': '0',
                'max': '100',
                'step': '00.1'
            }).addClass('percentage');
            on_percentage();
            break;
        default:
            $(".param-method").removeAttr('class').attr({
                'type': 'text'
            }).addClass('fa fa-question param-method').text('').removeClass('percentage');
    }
}

function on_percentage() {
    $(".nominal-to-percent").delegate('.percentage', 'keyup', function (ev) {
        change_percent_max($(this));
    });
}

function change_percent_max(input) {
    var value_ = input.val();
    switch (true) {
        case (value_ > 100):
            input.val(0);
            break;
        default:
            break;
    }
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
        fontRedLabel();
    }

    var dTable = $('#data-table-view').DataTable({
        stateSave: true,
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/discounts/json",
            type: 'GET',
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'nama',
                name: 'nama'
            },
            {
                data: 'nominals',
                name: 'nominals'
            },
            {
                data: 'berlaku_dari',
                name: 'berlaku_dari',
                className: "text-center",
                render: function (data, type, row) {
                    return data ? getIndoDate(data) : '';
                }
            },
            {
                data: 'berlaku_sampai',
                name: 'berlaku_sampai',
                className: "text-center",
                render: function (data, type, row) {
                    return data ? getIndoDate(data) : '';
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                className: "text-center"
            },
        ],
        order: [
            [0, 'desc']
        ]
    });
    dTable.ajax.reload(response_load_dt);
    $("select[name=data-table-view_length]").on('change', function () {
        dTable.ajax.reload(response_load_dt);
    });
    $("input[type=search]").on('input', function (e) {
        dTable.ajax.reload(response_load_dt);
    });
    remove();
}

function imgError(image) {
    image.onerror = "";
    image.src = "/images/brokenimage.jpg";
    image.alt = "Images corrupt!";
    image.title = "Images corrupt!";
    return true;
}

function formatState(state) {
    if (!state.id) {
        return state.text;
    }
    var img_load = '/storage/master-data/product/uploads/'
    var img = state.element.attributes[0].value == 'null' ? "/images/noimage.jpg" : img_load + state.element.attributes[0].value;

    var $state = $(
        '<span><img onerror="imgError(this);" width="50" height="40" src="' + base_url + img + '" /> ' + state.text + '</span>'
    );

    return $state;
};

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
    $(".nominal-to-percent").delegate('input[type=rupiah]', 'input', function (e) {
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

function form_attribut(id, id2) {
    cek_load();
    select_(id, 'product');
    select_(id2, 'services');

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

    $('.select2-multiple-services').select2({
        placeholder: "Please select!",
        theme: "bootstrap",
        allowClear: true,
        matcher: matchCustom
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

    onInputRupiah()
}

function fill_field_daterange(picker) {
    $("input[name='berlaku_dari']").val(picker.startDate.format('DD-MM-YYYY'));
    $("input[name='berlaku_sampai']").val(picker.endDate.format('DD-MM-YYYY'));
}

function remove_field_daterange() {
    $("input[name='berlaku_dari']").removeAttr('value').val('').trigger('change');
    $("input[name='berlaku_sampai']").removeAttr('value').val('').trigger('change');
}

function fontRedLabel() {
    $("tbody").find(".btn-default").closest('tr').css({
        'color': 'red'
    });
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
    $("form#formDiskon").submit(function (e) {
        e.preventDefault();

        var event = $(this)[0];

        $(".display-future").addClass('blocking-content');
        var data = new FormData(event);
        var url = event.action;

        var reload_form = function () {
            load_formAdd();
            fontRedLabel();
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
                            swal.close();
                            $(".display-future").removeClass('blocking-content');
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
    cek_load();
    load_formAdd();
    load_data();
});
