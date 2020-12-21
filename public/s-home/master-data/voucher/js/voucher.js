function load_formAdd() {
    submit();
    $('.btn-form-voucher').on('click', function () {
        $('.load-form-modal').html('')
        $('.load-form-modal').load(location.href + ' .modal-content');
        setTimeout(function () {
            $('.modal-title').html('<em class="fa fa-pencil-square-o"></em> Form Voucher')
            form_attribut();
        }, 1500)
    });
}

function load_formEdit() {
    $('tbody').delegate('.edit', 'click', function () {
        $('.load-form-modal').html('')
        var event = $(this);
        var target = event.data('route');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });

        $.ajax({
            type: 'PUT',
            url: target,
            success: function (result) {
                $('.load-form-modal').html(result);
                $('.modal-title').html('<em class="fa fa-pencil-square-o"></em> Form Voucher')
                var iD_services = $("select[name=layanan]").data('selected');
                form_attribut(iD_services);
            },
            error: function () {
                toastr.error('Gagal mengambil data', 'Oops!', {
                    timeOut: 2000
                });
            }
        });
    });
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
    var img_load = '/storage/master-data/service/uploads/'
    var img = state.element.attributes[0].value == 'null' ? "/images/noimage.jpg" : img_load + state.element.attributes[0].value;

    var $state = $(
        '<span><img onerror="imgError(this);" width="50" height="40" src="' + base_url + img + '" /> ' + state.text + '</span>'
    );

    return $state;
};

function select_(id, table) {
    var id_ = id || '';
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

    $.ajax({
        url: base_url + "/vouchers/option",
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
                var selected = data[i].id == id_ ? "selected" : '';
                var gambar = table == 'layanan' ? ` data-gambar='` + data[i].gambar + `'` : '';
                html += `<option ` + selected + gambar + ` value='` + data[i].id + `'>` + data[i].nama + `</option>`;
            }
            $("select." + table).html(html);
        }
    });
}

function changePicture() {
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

function form_attribut(id) {
    select_(id, 'layanan');
    $('.select2-multiple').select2({
        placeholder: "Please select!",
        theme: "bootstrap",
        templateResult: formatState
    })

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
}

function load_name_type(event) {
    var name_ = '';
    switch (event.text()) {
        case '1':
            name_ = 'Spa & Message';
            break;
        case '2':
            name_ = 'Rambut';
            break;
        case '3':
            name_ = 'Kulit & Wajah';
            break;
        case '4':
            name_ = 'Kuku';
            break;
        default:
            break;
    }
    return event.text(name_);
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
    cont.load(base_url + '/vouchers/data', function (e, s, f) {
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

function data_attribut() {
    var cek_type_name = function () {

        $('table tbody').find('td.type').each(function (e) {
            var type = $(this);
            load_name_type(type);
        });

    }

    var dataAfterLoad = function () {

        $('table tbody').find('td.type').each(function (e) {
            var type = $(this);
            if (type.text() == 1) {
                type.text('Spa & Message')
            } else if (type.text() == 2) {
                type.text('Rambut')
            } else if (type.text() == 3) {
                type.text('Kulit & Wajah')
            } else if (type.text() == 4) {
                type.text('Kuku')
            }
        });
    }

    var dTable = $('#data-table-view').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        initComplete: function () {
            this.api().columns(3).every(function () {
                var column = this;
                var select = $('<select id="addSelect" class="form-control"><option value="">Semua</option></select>')
                    .appendTo($(column.footer()).empty())
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column
                            .search(val ? '^' + val + '$' : '', true, false)
                            .draw();
                    });

                column.data().unique().sort().each(function (d, j) {
                    var dName = '';
                    if (d == 1) {
                        dName = 'Spa & Message';
                    } else if (d == 2) {
                        dName = 'Rambut';
                    } else if (d == 3) {
                        dName = 'Kulit & Wajah';
                    } else if (d == 4) {
                        dName = 'Kuku';
                    }
                    select.append('<option value="' + d + '">' + dName + '</option>')
                    $("#addSelect").attr('onChange', 'reFilter()');

                });
            });
        },
        ajax: {
            url: base_url + "/vouchers/json",
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
                className: "text-center td-height-img",
            },
            {
                data: 'nama',
                name: 'nama',
                className: 'td-height-img'
            },
            {
                data: 'berlaku_dari',
                name: 'berlaku_dari',
                className: 'td-height-img',
                render: function (data, type, row) {
                    return data ? getIndoDate(data) : '';
                }
            },
            {
                data: 'berlaku_sampai',
                name: 'berlaku_sampai',
                className: 'td-height-img',
                render: function (data, type, row) {
                    return data ? getIndoDate(data) : '';
                }
            },
            {
                data: 'diskon',
                name: 'diskon',
                className: 'td-height-img'
            },
            {
                data: 'keterangan',
                name: 'keterangan',
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
    dTable.ajax.reload(cek_type_name);
    $("select[name=data-table-view_length]").on('change', function () {
        dTable.ajax.reload(cek_type_name);
    });
    $("input[type=search]").on('input', function (e) {
        dTable.ajax.reload(dataAfterLoad);
    });
    load_formEdit();
    remove();
}

function reFilter() {
    var search_select_group = function () {

        $('table tbody').find('td.type').each(function (e) {
            var type = $(this);
            if (type.text() == 1) {
                type.text('Spa & Message')
            } else if (type.text() == 2) {
                type.text('Rambut')
            } else if (type.text() == 3) {
                type.text('Kulit & Wajah')
            } else if (type.text() == 4) {
                type.text('Kuku')
            }
        });
    }
    $('table#data-table-view').DataTable().ajax.reload(search_select_group);
}

function getImg(data, type, full, meta) {
    var img = !data ? '/images/noimage.jpg' : '/storage/master-data/voucher/uploads/' + data;
    return '<img onerror="imgError(this);" width="100" height="70" src="' + base_url + img + '">';
}

function refresh_select() {
    var uniqueItems = [];
    var uniqueItemsText = [];
    var tbl = $('table#data-table-view tbody');
    var html = `<option value="">Semua</option>`;

    tbl.find('td.type').filter(function (index, element) {
        if ($.inArray($(element).text(), uniqueItems) === -1) {
            var data = '';
            if ($(element).text() == 'Spa & Message') {
                data = 1;
            } else if ($(element).text() == 'Rambut') {
                data = 2
            } else if ($(element).text() == 'Kulit & Wajah') {
                data = 3
            } else if ($(element).text() == 'Kuku') {
                data = 4
            }
            uniqueItems.push($(element).text());
            uniqueItemsText.push(data);
        }
    });

    for (var i = 0; i < uniqueItemsText.length; i++) {
        html += `<option value='` + uniqueItemsText[i] + `'>` + uniqueItems[i] + `</option>`;
    }

    $("#addSelect").html(html);
}

function remove() {
    $('tbody').delegate('.btn-remove-id', 'click', function () {
        var event = $(this);
        var target = event.data('route');
        var tables = event.closest('table');

        var search_select_group = function () {
            reFilter();
            refresh_select();
        }

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
                                tables.DataTable().ajax.reload(search_select_group);
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

function submit() {

    $("form#formVoucher").submit(function (e) {
        e.preventDefault();

        var elemtTb = $('#data-table-view').DataTable();
        var dTbPageInfo = elemtTb.page.info().page;

        var event = $(this)[0];
        var close_modal = function () {
            $(".modal").modal('hide');
        }
        var data = new FormData(event);
        var url = event.action;
        var search_select_group = function () {
            $('table tbody').find('td.type').each(function (e) {
                var type = $(this);
                if (type.text() == 1) {
                    type.text('Spa & Message')
                } else if (type.text() == 2) {
                    type.text('Rambut')
                } else if (type.text() == 3) {
                    type.text('Kulit & Wajah')
                } else if (type.text() == 4) {
                    type.text('Kuku')
                }
            });
            refresh_select()
        }

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
                        $('table#data-table-view').DataTable().ajax.reload(search_select_group);
                        $('table#data-table-view').DataTable().page(dTbPageInfo).draw('page');
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
    load_formAdd();
    load_data();
});
