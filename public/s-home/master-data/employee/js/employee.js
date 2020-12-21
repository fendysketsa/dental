$(".load-data").delegate('.edit', 'click', function (e) {
    var event = $(this);
    load_formEdit(event);

});

$(".button-action").delegate('.cancel-form', 'click', function () {
    load_formAdd();
    refresh_action_table();
});

function select_(id, table, role) {
    var id_ = id || '';
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

    $.ajax({
        url: base_url + "/employees/option",
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
            if (role == 5) {
                html += `<option selected value='5'>Owner</option>`;
            }
            $("select[name=" + table + "]").html(html);
        }
    });
}

function select_multi_(id, table, cabang) {
    var id_ = id || '';
    var cabang_id_ = cabang || '';
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

    $.ajax({
        url: base_url + "/employees/option",
        method: "POST",
        data: {
            table: table,
            cabang_id: cabang_id_
        },
        dataType: 'json',
        success: function (data) {
            var html = `<option></option>`;
            var i;
            for (i = 0; i < data.length; i++) {
                var selected = data[i].id == id_ ? "selected" : '';
                html += `<option ` + selected + ` value='` + data[i].id + `'>` + data[i].nama + `</option>`;
            }
            $("select.cabang_other").html(html);
        }
    });
}

function avail_serv(id, table) {
    var id_ = id || '';
    $(".load-more-acc").html('loading...');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

    $.ajax({
        url: base_url + "/employees/option",
        method: "POST",
        data: {
            table: table,
            cabang_id: $('select[name=cabang]').val()
        },
        dataType: 'json',
        success: function (data) {
            $("#av_layanan").html('loading...');

            var html = `<div class="box box-default display-future">`;
            html += `<div class="box-header with-border bg-default">`;
            html += `<h3 class="box-title"><em class='fa fa-th-large'></em> Layanan & Penempatan Kerja</h3>`;
            html += `</div>`;
            html += `<div class="box-body">`;

            html += `<div class="nav-tabs-custom">`;
            html += `<ul class="nav nav-tabs">`;

            html += `<li class="active">`;
            html += `<a href="#av_layanan" data-toggle="tab" aria-expanded="false">Layanan tersedia <sup><small>(Kualifikasi Terapist)</small></sup></a>`;
            html += `</li>`;

            html += `<li class="">`;
            html += `<a href="#av_cabang" data-toggle="tab" aria-expanded="false">Cabang Lain <sup><small>(Penempatan Kerja)</small></sup></a>`
            html += `</li>`;

            html += `</ul>`;

            html += `<div class="tab-content">`;
            html += `<div class="tab-pane active" id="av_layanan">`;
            html += `<table class="table hover input-sm" width="100%" cellspacing="0">`;
            html += `<thead class="bg-navy disabled color-palette">`;
            html += `<tr style="display:table; width:100%; table-layout:fixed">`;
            html += `<th style="width:10%;">No</th>`;
            html += `<th style="width:30%;">Kategori</th>`;
            html += `<th style="width:45%;">Layanan</th>`;
            html += `<th style="width:15%;" class="text-center">&nbsp;</th>`;
            html += `</tr>`;
            html += `</head>`;

            html += `<tbody style="max-height:350px; display:block; overflow:auto;">`;

            for (var i = 0; i < data.length; i++) {

                html += `<tr style="display:table; width:100%; table-layout:fixed">`;
                html += `<td style="width:10%;">` + (i + 1) + `</td>`;
                html += `<td style="width:30%;">` + data[i].kategori + `</td>`;
                html += `<td style="width:45%;">` + data[i].nama + `</td>`;
                html += `<td style="width:15%;" class="text-center">`;
                html += `<label><input name="kualifikasi[]" type="checkbox" class="kualifikasi-pegawai-cek minimal cbx-` + i + `" value="` + data[i].id + `"
                form="formPegawai"></label>`;
                html += `</td>`;
                html += `</tr>`;
            }

            html += `</tbody>`;
            html += `</table>`;

            html += `</div>`;
            html += `<div class="tab-pane" id="av_cabang">`;
            html += `<div class="form-group input-group-sm">`;
            html += `<label>Penempatan di Cabang Lain: </label>`;
            html += `<select name="cabang_lain[]" form="formPegawai" class="form-control select2-multiple-cbg-oth cabang_other" multiple="multiple"
            style="width: 100%;" ></select>`;
            html += `</div>`;
            html += `</div>`;
            html += `</div>`;
            html += `</div>`;
            html += `</div>`;

            $(".load-more-acc").html(html);

            setTimeout(function () {
                select_multi_(id_, 'cabang_oth', ($("select[name=cabang]").val() ? $("select[name=cabang]").val() : ''));

                var data_ = $(".kualifikasi-pegawai-cek");
                var data_ceked = $("input[name=id]").data('pegawai');

                $.each(data_, function (i, values) {
                    if ($.inArray(parseInt(data_[i].value), data_ceked) !== -1) {
                        $("input.cbx-" + i).iCheck('check');
                    }
                });

                $('.select2-multiple-cbg-oth').select2({
                    placeholder: "Please select!",
                    theme: "bootstrap",
                });

                $(".select2-search__field").css({
                    'width': '100px'
                })

            }, 500);

            $('input[type="checkbox"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
            });
        },
        complete: function () {
            setTimeout(() => {
                $(".cabang_other").val($('input[name=id]').data('cabang-lain')).trigger('change')
            }, 1000);
        }
    });
}

function load_formAdd() {
    var cont = $(".load-form");
    $(".display-future").addClass('blocking-content');
    cont.load(base_url + '/employees/add', function (e, s, f) {
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

            setTimeout(function () {
                load_more_edit(iD_role);

            }, 500);

            var iD_branch = $("select[name=cabang]").data('selected');
            var iD_role = $("select[name=role]").data('selected');
            var iDroleOwner = $("input[name=id]").data('role');

            form_attribut(iD_branch, iD_role, iDroleOwner);
            loadSwitch(iD_role)
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

function load_more_edit(event) {
    if (event === '' || event === 3) {
        avail_serv('', 'layanan');

    } else {
        $(".load-more-acc").html(f_user_acc);
        setTimeout(function () {
            $("input[name=email]").removeAttr('readonly');
            $("input[name=password]").removeAttr('readonly');
        }, 500);
    }
}

function load_more(event) {
    loadSwitch(event)
    switch (event) {
        case '':
            $(".load-more-acc").html('');
            break;
        case '3':
            avail_serv('', 'layanan');
            break;
        default:
            $(".load-more-acc").html(f_user_acc);
            setTimeout(function () {
                $("input[name=email]").removeAttr('readonly');
                $("input[name=password]").removeAttr('readonly');
            }, 500)
            break;
    }
}

function loadHTMLKomisi(load) {
    var idEdit = $("input[name=id]");
    var isId = idEdit.val() ? idEdit.data('komisi') : '';

    var html = `<div class="col-md-4 col-xs-12">
        <div class="form-group">
            <label>Komisi:</label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-percent"></i>
                </div>
                <input type="number" min="0" max="100" step="0.01" name="komisi" value="` + isId + `" @endif class="form-control" placeholder="Komisi..."
                    form="formPegawai">
            </div>
        </div>
    </div>`;

    return $("#load-komisi").html((load || load == 'load' ? html : ''));
}

function loadSwitch(ev) {
    if (!ev) {
        loadHTMLKomisi()
        return false;
    }
    var geserKolom = $(".colom-geser");
    console.log(ev)
    if (ev == '3') {
        geserKolom.addClass('col-md-8').removeClass('col-md-12');
        loadHTMLKomisi('load')
    } else {
        geserKolom.addClass('col-md-12').removeClass('col-md-8');
        loadHTMLKomisi()
    }
}

function load_name_role(event) {
    var name_ = '';
    switch (event.text()) {
        case '1':
            name_ = 'Super Admin';
            break;
        case '2':
            name_ = 'Manager';
            break;
        case '3':
            name_ = 'Terapis';
            break;
        case '4':
            name_ = 'Kasir';
            break;
        case '5':
            name_ = 'Owner';
            break;
        default:
            break;
    }
    return event.text(name_);
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

function inp_komisi() {
    $('input[name=komisi]').on('input', function () {
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

function form_attribut(id, id2, id3) {
    inp_komisi();
    select_(id, 'cabang');
    select_(id2, 'role', id3);

    $('.select2').select2({
        placeholder: "Please select!",
        allowClear: true,
        theme: "bootstrap"
    });
    $(".role-option").on('change', function (e) {
        var event = $(this).val();
        load_more(event);
    });

    $("select[name=cabang]").on('change', function (e) {
        var roles = $("select[name=role]").val();
        if (roles == 3) {
            avail_serv('', 'layanan');
        }
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

    var timer;
    $("input[name=komisi]").bind("keypress", function (e) {
        var keyCode = e.which ? e.which : e.keyCode
        if (!(keyCode >= 48 && keyCode <= 57)) {
            window.clearTimeout(timer);
            timer = window.setTimeout(function () {
                toastr.warning('Isian wajib angka!!', 'Oopss!', {
                    timeOut: 3000
                });
            }, 1000);
            return false;
        }
    });
}

function f_user_acc() {
    var evPegawai = $("input[name=id]");
    var idPegawai = evPegawai.val() || null;
    var elEmailValue = idPegawai ? "value='" + evPegawai.data('email') + "'" : '';
    var elPassword = !idPegawai ? `<em class="text-danger">*</em>` : '';
    var content_info = idPegawai ? `<blockquote><small>Apabila password tidak diisi, Email sebagai password!</small></blockquote>` : ``;

    var html = '';
    html += `<div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email: <em class="text-danger">*</em></label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </div>
                        <input type="email" name="email" ` + elEmailValue + ` class="form-control" placeholder="Email..." form="formPegawai" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Password: ` + elPassword + `</label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-key"></i>
                            </div>
                            <input type="password" name="password" class="form-control" placeholder="Password..." form="formPegawai" readonly>
                        </div>
                    </div>
                </div>
            </div>` + content_info;
    return html;
}

function load_data() {
    var cont = $(".load-data");
    $(".button-action").addClass('hide');
    $(".display-future").addClass('blocking-content');
    cont.load(base_url + '/employees/data', function (e, s, f) {
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

function data_attribut() {
    var cek_role_name = function () {
        var ins = $("input[name=id]").val() || 0;
        $(".btn-id-" + ins).addClass('disabled').removeAttr('data-route');

        $('table tbody').find('td.roles').each(function (e) {
            var role = $(this);
            load_name_role(role);
        });

        $(".load-data").find('ul.pagination > li').delegate('a', 'click', function () {
            $('#data-table-view').DataTable().ajax.reload(cek_role_name)
        });
    }

    var dataAfterLoad = function () {
        var ins = $("input[name=id]").val() || 0;
        $(".btn-id-" + ins).addClass('disabled').removeAttr('data-route');

        $('table tbody').find('td.roles').each(function (e) {
            var role = $(this);
            if (role.text() == 1) {
                role.text('Super Admin')
            } else if (role.text() == 2) {
                role.text('Manager')
            } else if (role.text() == 3) {
                role.text('Terapis')
            } else if (role.text() == 4) {
                role.text('Kasir')
            } else if (role.text() == 5) {
                role.text('Owner')
            }
        });
    }

    var dTable = $('#data-table-view').DataTable({
        scrollY: true,
        scrollX: true,
        initComplete: function () {
            this.api().columns(5).every(function () {
                var column = this;
                var select = $('<select id="addSelectEmp" class="form-control"><option value="">Semua</option></select>')
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
                        dName = 'Super Admin';
                    } else if (d == 2) {
                        dName = 'Manager';
                    } else if (d == 3) {
                        dName = 'Terapis';
                    } else if (d == 4) {
                        dName = 'Kasir';
                    } else if (d == 5) {
                        dName = 'Owner';
                    }
                    select.append('<option value="' + d + '">' + dName + '</option>')
                    $("#addSelectEmp").attr('onChange', 'reFilter()');
                });
            });
        },
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/employees/json",
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
                className: "text-center td-height-img"
            },
            {
                data: 'cabang',
                name: 'cabang',
                className: 'td-height-img'
            },
            {
                data: 'nama',
                name: 'nama',
                className: 'td-height-img'
            },
            {
                data: 'jabatan',
                name: 'jabatan',
                className: 'td-height-img'
            },
            {
                data: 'role',
                name: 'role',
                className: "roles td-height-img"
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
    dTable.ajax.reload(cek_role_name);
    $("select[name=data-table-view_length]").on('change', function () {
        dTable.ajax.reload(cek_role_name);
    });

    $("input[type=search]").on('input', function (e) {
        dTable.ajax.reload(dataAfterLoad);
    });

    remove();
}

function reFilter() {
    var search_select_group = function () {
        var ins = $("input[name=id]").val() || 0;
        $(".btn-id-" + ins).addClass('disabled').removeAttr('data-route');

        $('table tbody').find('td.roles').each(function (e) {
            var role = $(this);
            load_name_role(role);
        });

        $(".load-data").find('ul.pagination > li').delegate('a', 'click', function () {
            $('#data-table-view').DataTable().ajax.reload(search_select_group)
        });
    }

    $('table#data-table-view').DataTable().ajax.reload(search_select_group);

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

function refresh_select(back) {
    var uniqueItems = [];
    var uniqueItemsText = [];
    var tbl = $('table#data-table-view tbody');
    var html = `<option value="">Semua</option>`;

    tbl.find('td.roles').filter(function (index, element) {
        if ($.inArray($(element).text(), uniqueItems) === -1) {
            var data = '';
            if (!back) {
                if ($(element).text() == 1) {
                    data = 'Super Admin';
                } else if ($(element).text() == 2) {
                    data = 'Manager'
                } else if ($(element).text() == 3) {
                    data = 'Terapis'
                } else if ($(element).text() == 4) {
                    data = 'Kasir'
                } else if ($(element).text() == 5) {
                    data = 'Owner'
                }
            } else {
                if ($(element).text() == 'Super Admin') {
                    data = 1;
                } else if ($(element).text() == 'Manager') {
                    data = 2
                } else if ($(element).text() == 'Terapis') {
                    data = 3
                } else if ($(element).text() == 'Kasir') {
                    data = 4
                } else if ($(element).text() == 'Owner') {
                    data = 5
                }
            }
            uniqueItems.push($(element).text());
            uniqueItemsText.push(data);
        }
    });

    if (!back) {
        tbl.find('td.roles').each(function (e) {
            var type = $(this);
            if (type.text() == 1) {
                type.text('Super Admin')
            } else if (type.text() == 2) {
                type.text('Manager')
            } else if (type.text() == 3) {
                type.text('Terapis')
            } else if (type.text() == 4) {
                type.text('Kasir')
            } else if (type.text() == 5) {
                type.text('Owner')
            }
        });
    }
    if (!back) {
        for (var i = 0; i < uniqueItems.length; i++) {
            html += `<option value='` + uniqueItems[i] + `'>` + uniqueItemsText[i] + `</option>`;
        }
    } else {
        for (var i = 0; i < uniqueItemsText.length; i++) {
            html += `<option value='` + uniqueItemsText[i] + `'>` + uniqueItems[i] + `</option>`;
        }
    }

    $("select#addSelectEmp").html(html);
}

function remove() {
    $('tbody').delegate('.btn-remove-id', 'click', function () {
        var event = $(this);
        var target = event.data('route');
        var tables = event.closest('table');

        var response_load_dt = function () {

            var ins = $("input[name=id]").val() || 0;
            $(".btn-id-" + ins).addClass('disabled').removeAttr('data-route');
            $('table tbody').find('td.roles').each(function (e) {
                var role = $(this);
                load_name_role(role);
            });
            refresh_select('back');
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
    $("form#formPegawai").submit(function (e) {
        e.preventDefault();

        var event = $(this)[0];
        var data = new FormData(event);
        var url = event.action;

        var reload_form = function () {
            load_formAdd();
            refresh_select();
        }

        $(".display-future").addClass('blocking-content');

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
