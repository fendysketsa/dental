function load_formAdd() {
    submit();
    $('.btn-form-layanan').on('click', function () {
        $('.load-form-modal').html('')
        $('.load-form-modal').load(location.href + ' .modal-content', function (e, s, f) {
            if (s == 'error') {
                toastr.error('Gagal memuat form', 'Oops!', {
                    timeOut: 2000,
                    onHidden: function () {
                        $(".modal").modal('hide')
                    }
                });
            } else {
                $('.modal-title').html('<em class="fa fa-pencil-square-o"></em> Form Layanan')
                form_attribut();
            }
        });
    });
}

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
            type: 'PUT',
            url: event.data('route'),
            success: function (result) {
                $('.load-form-modal').html(result);
                $('.modal-title').html('<em class="fa fa-pencil-square-o"></em> Form Layanan')
                var iD_category = $("select[name=kategori]").data('selected');
                form_attribut(iD_category);
            },
            error: function () {
                toastr.error('Gagal mengambil data', 'Oops!', {
                    timeOut: 2000
                });
            }
        });
    });
}

function select_(id, table) {
    var id_ = id || '';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

    $.ajax({
        url: base_url + "/services/option",
        data: {
            table: table
        },
        method: "POST",
        dataType: 'json',
        success: function (data) {
            if (table == 'brand' && $('input[name=id]').val()) {
                setTimeout(function () { //harus pake ini
                    var elemt = $("select.brand").data('selected');
                    var id_mul = elemt.toString().length == 1 ? elemt : elemt.split(',');
                    if (elemt) {
                        $("select.brand").val(id_mul).select2({
                            theme: "bootstrap",
                            templateResult: formatState
                        });
                    }
                }, 0); //meskipun 0
            }

            if (table == 'cabang' && $('input[name=id]').val()) {
                setTimeout(function () { //harus pake ini
                    var elemt = $("select.cabang").data('selected');
                    var id_mul = elemt.toString().length == 1 ? elemt : elemt.split(',');
                    $("select.cabang").val(id_mul).select2({
                        theme: "bootstrap",
                    });
                }, 0); //meskipun 0
            }

            var html = `<option></option>`;
            var i;
            for (i = 0; i < data.length; i++) {
                var selected = data[i].id == id_ ? "selected" : '';
                var gambar = table == 'brand' ? ` data-gambar='` + data[i].gambar + `'` : '';
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

function formatState(state) {
    if (!state.id) {
        return state.text;
    }
    var img_load = '/storage/master-data/brand/uploads/'
    var img = state.element.attributes[0].value == 'null' ? "/images/noimage.jpg" : img_load + state.element.attributes[0].value;

    var $state = $(
        '<span><img width="50" height="40" src="' + base_url + img + '" /> ' + state.text + '</span>'
    );

    return $state;
};

function inp_waktu() {
    $('input[name=retouch_waktu]').on('input', function () {
        if ($(this).val().length < 1) {
            $(this).val('').change()
            return false;
        } else {
            if ($(this).val() < 1) {
                $(this).val('1').change()
                return false;
            }
            if ($(this).val() > 100) {
                $(this).val('100').change()
                return false;
            }
        }
    });
}

function form_attribut(id2) {

    inp_waktu();
    select_('', 'cabang');
    select_(id2, 'kategori');
    select_('', 'brand');

    $('.select2').select2({
        placeholder: "Please select!",
        allowClear: true,
        theme: "bootstrap"
    })
    $('.select2-multiple').select2({
        placeholder: "Please select!",
        theme: "bootstrap",
        templateResult: formatState
    });

    $('.select2-multiple-cabang').select2({
        placeholder: "Please select!",
        theme: "bootstrap",
    });
    $("input.select2-search__field").removeAttr('style');

    setTimeout(function () {
        $(function () {
            CKEDITOR.replace('deskripsi')
        })
    }, 1000);
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
    cont.load(base_url + '/services/data', function (e, s, f) {
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

    var dTable = $('#data-table-view').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/services/json",
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
                className: "text-center td-height-img",
            },
            {
                data: 'kategori',
                name: 'kategori',
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
    load_formEdit();
    remove();
}

function imgError(image) {
    image.onerror = "";
    image.src = "/images/brokenimage.jpg";
    image.alt = "Images corrupt!";
    image.title = "Images corrupt!";
    return true;
}

function getImg(data, type, full, meta) {
    var img = !data ? '/images/noimage.jpg' : '/storage/master-data/service/uploads/' + data;
    return '<img onerror="imgError(this);" width="100" height="70" src="' + base_url + img + '">';
}

function remove() {
    $('tbody').delegate('.btn-remove-id', 'click', function () {
        var event = $(this);
        var target = event.data('route');
        var tables = event.closest('table');

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
                                tables.DataTable().ajax.reload();
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

    $("form#formLayanan").submit(function (e) {
        e.preventDefault();
        CKEDITOR.instances['deskripsi'].updateElement();

        var elemtTb = $('#data-table-view').DataTable();
        var dTbPageInfo = elemtTb.page.info().page;

        var event = $(this)[0];
        var close_modal = function () {
            $(".modal").modal('hide');
        }
        var data = new FormData(event);
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
