function load_formAdd() {
    submit();
    $('.btn-form-pelanggan').on('click', function () {
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
                $('.modal-title').html('<em class="fa fa-pencil-square-o"></em> Form Pelanggan')
                form_attribut();
            }
        });
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

function load_formEdit() {
    $('tbody').delegate('.edit', 'click', function () {
        $('.load-form-modal').html('')
        var event = $(this);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'PUT',
            url: event.data('route'),
            success: function (result) {
                $('.load-form-modal').html(result);
                $('.modal-title').html('<em class="fa fa-pencil-square-o"></em> Form Pelanggan')
                form_attribut();
            },
            error: function () {
                toastr.error('Gagal mengambil data', 'Oops!', {
                    timeOut: 2000
                });
            }
        });

    });
}

function f_user_acc() {
    var idMember = $('input[name=id]').data('email');
    var html = `<div class="row">
                <input type="hidden" name="access" form="formPelanggan" value="1">
                <div class="col-md-7">
                    <div class="form-group">
                        <label>Email: <em class="text-danger">*</em></label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </div>
                        <input type="text" name="email" ` + (!idMember ? '' : `value="` + idMember + `"`) + ` class="form-control" placeholder="Email..." form="formPelanggan" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Password:</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-addon">
                                <i class="fa fa-key"></i>
                            </div>
                            <input type="text" id="password-field-toggle" name="password" class="form-control" placeholder="Password..." form="formPelanggan" readonly>
                            <span toggle="#password-field-toggle" class="fa fa-fw fa-eye-slash field-icon-show-member toggle-password"></span>
                        </div>
                    </div>
                </div>
            </div>`;
    return html;
}

function load_data() {
    var cont = $(".load-data");
    cont.load(base_url + '/members/data', function (e, s, f) {
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

function addOn(date) {
    $('.on-date input').val(date.format('DD-MM-YYYY'));
}

function form_attribut() {
    $(".load-more").html(f_user_acc);
    $("#accordion").find('.collapsed').removeClass('for-more').addClass('for-less');
    $("#accordion").find(".show-more").removeClass('fa-arrow-down').addClass('fa-arrow-up');
    $("#collapseOne").removeClass('collapse').addClass('collapse in').attr('aria-expanded', true);
    setTimeout(function () {
        $("input[name=email]").removeAttr('readonly').attr('type', 'email');
        $("input[name=password]").removeAttr('readonly').attr('type', 'password');
    }, 500);

    $("#accordion").delegate('.for-more', 'click', function (e) {
        $(this).removeClass('for-more').addClass('for-less');
        $(".show-more").removeClass('fa-arrow-down').addClass('fa-arrow-up');
        $(".load-more").html(f_user_acc);
        setTimeout(function () {
            $("input[name=email]").removeAttr('readonly').attr('type', 'email');
            $("input[name=password]").removeAttr('readonly').attr('type', 'password');
        }, 500)
    });

    $("#accordion").delegate('.toggle-password', 'click', function (e) {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    $("#accordion").delegate('.for-less', 'click', function (e) {
        var cekId = $("input[name=id]").data('email');
        if (cekId) {
            Notify.confirm({
                'title': 'Ingin menghapus akses login?',
                'left': 'Batal',
                'class': 'button',
                'right': 'Ya',
                modal: true,
                fn: function (e) {
                    if (e == 'success') {
                        $("#accordion").html('');
                    }
                }
            });

            return false;
        } else {
            $(this).removeClass('for-less').addClass('for-more');
            $(".show-more").removeClass('fa-arrow-up').addClass('fa-arrow-down');
            $(".load-more").html('');
        }
    });

    var tgl_lahir = $("input[name=tanggal_lahir]");
    $('.add-on-daterpicker').daterangepicker({
        singleDatePicker: true,
        autoUpdateInput: true,
        showDropdowns: true,
        startDate: !tgl_lahir.val() ? moment() : moment().add().format(tgl_lahir.val()),
        locale: {
            format: 'DD-MM-YYYY'
        }
    }, addOn);

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
    return '<img onerror="imgError(this);" style="border-radius:50%;" width="70" height="70" src="' + base_url + img + '">';
}

function data_attribut() {

    var dTable = $("#data-table-view").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/members/json",
            type: "GET",
        },
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                orderable: false,
                searchable: false,
                className: "td-height-img",
            },
            {
                data: "foto",
                name: "foto",
                render: getImg,
                className: "text-center td-height-img",
            },
            {
                data: "no_member",
                name: "no_member",
                className: "td-height-img",
            },
            {
                data: "no_member",
                name: "no_member",
                className: "td-height-img",
            },
            {
                data: "nama",
                name: "nama",
                className: "td-height-img",
            },
            {
                data: "email",
                name: "email",
                className: "td-height-img",
            },
            {
                data: "telepon",
                name: "telepon",
                className: "td-height-img",
            },
            {
                data: "saldo",
                name: "saldo",
                className: "td-height-img",
            },
            {
                data: "action",
                name: "action",
                orderable: false,
                className: "text-center td-height-img",
            },
        ],
        order: [[0, "desc"]],
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
    $("form#formPelanggan").submit(function (e) {
        e.preventDefault();

        var elemtTb = $('#data-table-view').DataTable();
        var dTbPageInfo = elemtTb.page.info().page;

        var event = $(this)[0];
        var close_modal = function () {
            $(".modal").modal('hide');
        }

        $(".preloader").fadeIn();
        $(".modal-content").addClass('mod-cont-blur');
        $(".modal-body").addClass('mod-bod-blur');

        var data = new FormData(event);
        var url = event.action;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });

        $.ajax({
            url: url,
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            type: 'POST',
            dataType: 'JSON',
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
