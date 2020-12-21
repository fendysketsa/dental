function load_formAdd() {
    submit();
    $('.btn-form-pembelian').on('click', function () {
        $(".preloader").fadeIn();
        $('.load-form-modal').html('')
        $('.load-form-modal').load(base_url + '/trans/purchases/buy/create' + ' .modal-content');
        $("table tbody.data-pembelian").empty()
        setTimeout(function () {
            $('.modal-title').html('<em class="fa fa-pencil-square-o"></em> Form Pembelian')
            form_attribut();
            $(".preloader").fadeOut();
        }, 1500)
    });
}

function load_formEdit() {
    $('tbody').delegate('.edit', 'click', function () {
        var event = $(this);
        var dRoute = event.data('route').split('/');
        var dLength = dRoute.length;
        var dId = dRoute[dLength - 1];

        $(".preloader").fadeIn();
        $('.load-form-modal').html('')

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });

        $.ajax({
            type: 'PUT',
            url: event.data('route'),
            success: function (result) {
                console.log(result)
                $('.load-form-modal').html(result);
                $('.modal-title').html('<em class="fa fa-pencil-square-o"></em> Form Pembelian')
                var iD_supplier = $("select[name=ssupplier]").data('selected');
                form_attribut(iD_supplier);
                load_detail_pembelian(dId);
            },
            error: function () {
                toastr.error('Gagal mengambil data', 'Oops!', {
                    timeOut: 2000
                });
            },
            complete: function () {
                $(".preloader").fadeOut();
            }
        });
    });
}

function load_formChecklist() {
    $('tbody').delegate('.checklist', 'click', function () {
        var event = $(this);

        var dRoute = event.data('route').split('/');
        var dLength = dRoute.length;
        var CKpostId = dRoute[dLength - 1];

        $(".preloader").fadeIn();
        $('.load-formCK-modal').html('')

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $("meta[name=csrf-token]").attr('content')
            }
        });

        $.ajax({
            url: event.data('route'),
            method: "PUT",
            success: function (data) {
                $('.load-formCK-modal').html(data);
                $('.modal-title').html('<em class="fa fa-pencil-square-o"></em> Form Cek list Pembelian')
                load_checklist_pembelian(CKpostId);
                $("table tbody.data-pembelian").delegate('tr > td > label > input.cKlist', 'click', function (e) {
                    var name_prod = $(this).parents("tr").find('em.by-status');
                    if ($(this)[0].checked == true) {
                        name_prod.html('<em class="fa fa-check-square-o text-success"></em>')
                    } else {
                        name_prod.html("")
                    }
                });
            },
            error: function () {
                toastr.error('Gagal memuat data checklist!', 'Oopps!')
            },
            complete: function () {
                $(".preloader").fadeOut();
                submitCK()
            }
        });
    });
}

function load_checklist_pembelian(id) {
    var checRow = function (e) {
        var name_prod = $(this).closest('tr').find('em.by-status');


        if ($(this)[0].checked == true) {
            name_prod.html('<em class="fa fa-check-square-o text-success"></em>');
            $(this).closest('tr').find('input.input-eid').removeAttr('disabled');
        } else {
            name_prod.html("")
            $(this).closest('tr').find('input.input-eid').attr('disabled', true);
        }
    }

    $.ajax({
        url: base_url + "/trans/purchases/buy/load",
        method: "POST",
        data: {
            id: id,
            _token: $("meta[name=csrf-token]").attr('content')
        },
        dataType: 'json',
        success: function (data) {
            $.each(data, function (i, item) {
                var numb = i + 1;
                var markup = `<tr class="check-point-nom">`
                markup += `<td><em class="by-status">` + (data[i].status == 2 ? '<em class="fa fa-check-square-o text-success"></em>' : '') + `</em></td>`
                markup += `<td class="nom">` + numb + `</td>`
                markup += `<td>` + data[i].nama
                markup += `<input class="input-eid" type="hidden" disabled readonly name="eid[]" value="` + data[i].id + `" form="formTransCKPembelian"></td>`
                markup += `<td class="text-center">` + data[i].harga + `</td>`
                markup += `<td class="text-center">` + data[i].jumlah + `</td>`
                markup += `<td class="text-center">` + (parseInt(data[i].harga) * parseInt(data[i].jumlah)) + `</td>`
                markup += `<td class="text-center"><label><input ` + (data[i].status == 2 ? 'disabled checked' : '') + ` type="checkbox" name="optioned[]" class="flat-red cKlist" value="1" form="formTransCKPembelian"></label></td>`
                markup += `</tr>`;

                $("table tbody.data-check-pembelian").append(markup);
            });
            $(".cKlist").on("ifChanged", checRow);
        },
        error: function () {
            toastr.error('Gagal memuat data detail', 'Oopps!')
        },
        complete: function () {
            $(".preloader").fadeOut();
            $(".look-check-ttal").removeClass('hide').fadeIn('slow');
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            })
        }
    });
}

function load_detail_pembelian(id) {
    $.ajax({
        url: base_url + "/trans/purchases/buy/load",
        method: "POST",
        data: {
            id: id,
            _token: $("meta[name=csrf-token]").attr('content')
        },
        dataType: 'json',
        success: function (data) {
            $("table tbody.data-pembelian").empty()

            var total_all = 0;
            $.each(data, function (i, item) {

                var numb = i + 1;

                var markup = `<tr class="point-nom">`
                markup += `<td class="nom td-height-img">` + numb + `</td>`
                markup += `<td class="select"><div id="block"></div><select disabled class="select2 form-control produk-slct-avail" id="on-prd-` + numb + `"></select>`
                markup += `<input type="hidden" readonly name="eproduk[]" value="` + data[i].produk_id + `" id="on-prd-val-` + numb + `" form="formTransPembelian"></td>`
                markup += `<td class="prc-buy text-center"><input type="text" name="eharga[]" value="` + data[i].harga + `" id="prc-buy-` + numb + `" class="form-control on-harga-buy" form="formTransPembelian"></td>`
                markup += `<td class="qty text-center"><input id="qty-` + numb + `" type="number" name="ejumlah[]" value="` + data[i].jumlah + `" class="form-control on-qty" value="1"  min="1" form="formTransPembelian"></td>`
                markup += `<td class="stk text-center td-height-img"><input type="hidden" readonly name="idDetail[]" value="` + data[i].id + `" id="edit-on-id-val-` + numb + `" form="formTransPembelian"><em id="stok-` + numb + `" class="on-stok">0</em></td>`
                markup += `<td class="prc-sell text-center"><input type="text" name="harga_jual[]" value="` + data[i].harga_jual + `" id="prc-sell-` + numb + `" class="form-control on-harga-sell" form="formTransPembelian"></td>`
                markup += `<td class="subtotal text-center td-height-img"><input type="hidden" class="delete-selected" name="delete_pembelian[]" value="" id="delete-sell-` + numb + `" form="formTransPembelian"><em id="subtotal-` + numb + `" class="subtotal">0</em></td>`
                markup += `<td class="text-center td-height-img"><em class="fa fa-times delete-rows-cancel text-danger"></em></td>`
                markup += `</tr>`;

                var prd_id = data[i].produk_id;

                setTimeout(() => {
                    load_avail_produk(numb, prd_id);
                }, 500);

                $("table tbody.data-pembelian").append(markup);

                var harga_buy = data[i].harga;
                var qty = data[i].jumlah;
                var sub_harga = $("#subtotal-" + numb);
                var total = harga_buy * qty;
                sub_harga.text(total);
                total_all += total;
            });
            $("table tbody.data-pembelian").append(dt_row);
            $(".total-belanja").val(total_all);
        },
        complete: function () {
            $(".preloader").fadeOut();
        }
    });
}

function addOn(date) {
    $('.on-date input').val(date.format('DD-MM-YYYY'));
}

function form_attribut(id) {
    select_supplier(id);
    if (id) {
        var tlp = $(".telepon");
        var almt = $(".alamat");
        $.ajax({
            url: base_url + "/trans/purchases/buy/supplier/explore",
            method: "POST",
            data: {
                id: id,
                _token: $("meta[name=csrf-token]").attr('content')
            },
            dataType: 'json',
            success: function (data) {
                tlp.val(data[0].telepon);
                almt.val(data[0].alamat);
            },
            complete: function () {
                $(".preloader").fadeOut();
            }
        });
    }
    $('.select2').select2({
        placeholder: "Please select!",
        allowClear: true,
        theme: "bootstrap"
    });

    var tgl = $("input[name=tanggal]");
    $('.add-on-daterpicker').daterangepicker({
        singleDatePicker: true,
        autoUpdateInput: true,
        minDate: tgl.val() ? moment().add().format(tgl.val()) : moment(),
        startDate: !tgl.val() ? moment() : moment().add().format(tgl.val()),
        locale: {
            format: 'DD-MM-YYYY'
        }
    }, addOn);

    setTimeout(function () {

        $("em.f-input-an").delegate('select[name=ssupplier]', 'change', function (e) {
            var id = $(this).val();
            var tlp = $(".telepon");
            var almt = $(".alamat");
            if (!id) {
                tlp.val('');
                almt.val('');
                return false;
            }

            var token = $("input[name=_token]").val();
            $.ajax({
                url: base_url + "/trans/purchases/buy/supplier/explore",
                method: "POST",
                data: {
                    id: id,
                    _token: token
                },
                dataType: 'json',
                success: function (data) {
                    tlp.val(data[0].telepon);
                    almt.val(data[0].alamat);
                }
            });
        });

        $("div.change-to-field").delegate("div.add", "click", function (e) {
            var chg_ = $(this);
            var attr_ = $(".f-input-an");
            var tlp = $(".telepon");
            var almt = $(".alamat");
            $(".to-fill").text("*");
            chg_.removeClass('bg-green add').addClass('bg-blue use');
            chg_.find('i').removeClass('fa-user-plus').addClass('fa-search');
            attr_.html(input_supplier());
            tlp.attr({
                'name': 'telepon',
                'disabled': false
            });
            almt.attr({
                'name': 'alamat',
                'disabled': false
            });
            tlp.val('');
            almt.val('');
        });

        $("div.change-to-field").delegate("div.use", "click", function (e) {
            var chg_ = $(this);
            var attr_ = $(".f-input-an");
            var tlp = $(".telepon");
            var almt = $(".alamat");
            $(".to-fill").text("");
            chg_.removeClass('bg-blue use').addClass('bg-green add');
            chg_.find('i').removeClass('fa-search').addClass('fa-user-plus');
            attr_.html(select_suppliers());
            select_supplier();
            $('.select2').select2({
                placeholder: "Please select!",
                allowClear: true,
                theme: "bootstrap"
            });
            tlp.attr({
                'disabled': true
            }).removeAttr('name');
            almt.attr({
                'disabled': true
            }).removeAttr('name');
        });

        if (!id) {
            $("table tbody.data-pembelian").append(dt_row);
        }

        add_row_click();

        $("table tbody").delegate('tr > td > em.delete-rows-cancel', 'click', function (e) {
            $(this).parents('tr').find('em.subtotal').removeClass('subtotal').addClass('cancel-subtotal');

            var rest = parseInt($(this).parents("tr").find('em.cancel-subtotal').text());
            var total = parseInt($(".total-belanja").val());
            $(".total-belanja").val(total - rest);

            $(this).parents("tr").find('input.delete-selected').val('deleted');
            $(this).parents('tr').find('div#block').addClass("blocking-cancel");
            $(this).parents("tr").find('em.delete-rows-cancel').removeClass('delete-rows-cancel fa-times').addClass('delete-rows-undo fa-history');
        });

        $("table tbody").delegate('tr > td > em.delete-rows-undo', 'click', function (e) {
            $(this).parents('tr').find('em.cancel-subtotal').removeClass('cancel-subtotal').addClass('subtotal');

            var rest = parseInt($(this).parents("tr").find('em.subtotal').text());
            var total = parseInt($(".total-belanja").val());
            $(".total-belanja").val(total + rest);

            $(this).parents("tr").find('input.delete-selected').val('');
            $(this).parents('tr').find('div#block').removeClass("blocking-cancel");
            $(this).parents("tr").find('em.delete-rows-undo').removeClass('delete-rows-undo fa-history').addClass('delete-rows-cancel fa-times');
        });

        // Find and remove selected table rows
        $("table tbody").delegate('tr > td > em.delete-rows', 'click', function (e) {
            $(this).parents("tr").remove();
            var total_all = 0;
            var count_valin = 0;
            var trs = $('.data-pembelian tr.point-nom');
            trs.each(function (e, f) {
                $(trs[e]).find('td.nom').text(e + 1);
                $(trs[e]).find('td.select').find('select').removeAttr('id').attr('id', 'on-prd-' + (e + 1));
                $(trs[e]).find('td.select').find('input').removeAttr('id').attr('id', 'on-prd-val-' + (e + 1));
                $(trs[e]).find('td.stk').find('input').removeAttr('id').attr('id', 'edit-on-id-val-' + (e + 1));
                $(trs[e]).find('td.prc-buy').find('input').removeAttr('id').attr('id', 'prc-buy-' + (e + 1));
                $(trs[e]).find('td.qty').find('input').removeAttr('id').attr('id', 'qty-' + (e + 1));
                $(trs[e]).find('td.stk').find('em').removeAttr('id').attr('id', 'stok-' + (e + 1));
                $(trs[e]).find('td.prc-sell').find('input').removeAttr('id').attr('id', 'prc-sell-' + (e + 1));
                $(trs[e]).find('td.subtotal').find('input').removeAttr('id').attr('id', 'delete-sell-' + (e + 1));
                $(trs[e]).find('td.subtotal').find('em').removeAttr('id').attr('id', 'subtotal-' + (e + 1));

                var valin = $(trs[e]).find('select#on-prd-' + (e + 1) + ' option:selected').val();
                var valin_prc_buy = $(trs[e]).find('input#prc-buy-' + (e + 1)).val();
                if (valin !== '' && valin_prc_buy !== '') {
                    count_valin++;
                }

                var total = $(trs[e]).find('td.subtotal').find('em.subtotal').text()
                if (!isNaN(total) && total) {
                    total_all += parseInt(total);
                }
            });
            if (trs.length == 0 || trs.length === count_valin) {
                $("#add-row").removeClass('disabled')
                $("#add-row").addClass('add-row')
            }
            $(".total-belanja").val(total_all);
        });

        //isian jumlah dan harga
        $("table tbody").delegate('tr > td', 'input', function (e) {
            var trs = $('.data-pembelian tr.point-nom');
            var total_all = 0;
            trs.each(function (e, f) {
                var harga_buy = $(trs[e]).find('input.on-harga-buy').val();
                var qty = $(trs[e]).find('input.on-qty').val();
                var sub_harga = $(trs[e]).find('td.subtotal').find('em.subtotal');
                var total = harga_buy * qty;
                sub_harga.text(total).trigger('change');
                if (!isNaN(sub_harga.text()) && sub_harga.text()) {
                    total_all += parseInt(sub_harga.text());
                }
            });
            $(".total-belanja").val(total_all);
        });

        //isian jumlah dan harga
        $("table tbody").delegate('tr > td.select > select', 'change', function (e) {
            var total_all = 0;

            var fls = peringatan($(this));
            if (fls > 0) {
                return false;
            }
            var trs = $('.data-pembelian tr.point-nom');

            trs.each(function (e, f) {
                var harga_jual = $(trs[e]).find('select#on-prd-' + (e + 1) + ' option:selected').data('harga-jual');
                var sisa_stok = $(trs[e]).find('select#on-prd-' + (e + 1) + ' option:selected').data('sisa-stok');
                var cek = $(trs[e]).find('select#on-prd-' + (e + 1) + ' option:selected').val();
                var total = $(trs[e]).find('td.subtotal').find('em.subtotal').text()
                if (!isNaN(total) && total) {
                    total_all += parseInt(total);
                }

                if (!cek) {
                    $("#add-row").addClass('disabled')
                    $("#add-row").removeClass('add-row')
                    back_true(e);
                } else {
                    $("#add-row").removeClass('disabled')
                    $("#add-row").addClass('add-row')
                    var elemt_select_arr = $(trs[e]);
                    elemt_select_arr.find('select#on-prd-' + (e + 1)).prop("disabled", true);
                    elemt_select_arr.find('input#on-prd-val-' + (e + 1)).val(elemt_select_arr.find('select#on-prd-' + (e + 1)).val());

                    if (fls == 0) {
                        $('#on-prd-val-' + (e + 1)).removeAttr('disabled');
                        $('#edit-on-id-val-' + (e + 1)).removeAttr('disabled');
                        $('#prc-sell-' + (e + 1)).val(harga_jual).removeAttr('disabled');
                        $('#prc-buy-' + (e + 1)).removeAttr('disabled');
                        $('#qty-' + (e + 1)).removeAttr('disabled');
                        $('#stok-' + (e + 1)).text(sisa_stok);
                    }
                }
            });
            $(".total-belanja").val(total_all);
        });
        //end
    }, 500);
}

function add_row_click() {
    //start
    $(".add-rows").delegate('.add-row', 'click', function () {
        var trs = $('.data-pembelian tr.point-nom');
        var fls = 0;
        trs.each(function (e, f) {
            var harga_buy = $(trs[e]).find('input#prc-buy-' + (e + 1));
            if (!harga_buy.val()) {
                fls++;
                swal('Oopss!', 'Isikan harga beli', 'warning').then(() => {
                    harga_buy.focus();
                });
                return false;
            }
            if (harga_buy.length < 3 && harga_buy.val() < 100) {
                fls++;
                swal('Oopss!', 'Nominal harga beli minimal 100', 'warning').then(() => {
                    harga_buy.focus();
                });
                return false;
            }
        });
        if (fls == 0) {
            $("table tbody.data-pembelian").append(dt_row);
            $("#add-row").addClass('disabled');
            $("#add-row").removeClass('add-row');
        }
    });
}

function load_data() {
    var cont = $(".load-data");
    cont.load(base_url + '/trans/purchases/buy/data', function (e, s, f) {
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

    var dTable = $('#data-table-view').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/trans/purchases/buy/json",
            type: 'GET',
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'tanggal',
                name: 'tanggal',
                className: "text-center",
                render: function (data, type, row) {
                    return data ? getIndoDate(data) : '';
                }
            },
            {
                data: 'supplier',
                name: 'supplier'
            },
            {
                data: 'total_pembelian',
                name: 'total_pembelian'
            },
            {
                data: 'pegawai',
                name: 'pegawai'
            },
            {
                data: 'status',
                name: 'status',
                orderable: false,
                className: "text-center"
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
    dTable.ajax.reload();
    $("select[name=data-table-view_length]").on('change', function () {
        dTable.ajax.reload();
    });
    $("input[type=search]").on('input', function (e) {
        dTable.ajax.reload();
    });
    load_formEdit();
    load_formChecklist();
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

    $("form#formTransPembelian").submit(function (e) {
        e.preventDefault();

        var event = $(this)[0];
        var close_modal = function () {
            $(".modal").modal('hide');
        }
        var data = new FormData(event);
        var url = event.action;

        $(".preloader").fadeIn();
        $(".modal-content").addClass('mod-cont-blur');
        $(".modal-body").addClass('mod-bod-blur');

        var trs = $('.data-pembelian tr.point-nom');

        if (trs.length == 0) {
            swal('Oopss!', 'Pilih minimal 1 Produk untuk dibeli!', 'warning').then(() => {
                $(".preloader").fadeOut();
                setTimeout(function () {
                    $(".modal-content").removeClass('mod-cont-blur');
                    $(".modal-body").removeClass('mod-bod-blur');
                }, 500);
            });
            return false;
        }

        var fls = 0;
        var fls_ = 0;
        trs.each(function (e, f) {
            var prd = $(trs[e]).find('select#on-prd-' + (e + 1));
            var harga_buy = $(trs[e]).find('input#prc-buy-' + (e + 1));
            if (prd.val()) {
                if (harga_buy.length < 3 && harga_buy.val() < 100) {
                    fls++;
                    swal('Oopss!', 'Isikan nominal harga beli minimal 100', 'warning').then(() => {
                        harga_buy.focus();
                        $(".preloader").fadeOut();
                        setTimeout(function () {
                            $(".modal-content").removeClass('mod-cont-blur');
                            $(".modal-body").removeClass('mod-bod-blur');
                        }, 500);
                    });
                    return false;
                }
            } else {
                fls_++;
            }
        });
        if (fls_ > 0) {
            if (trs.length > 0) {
                swal('Oopss!', 'Pilih Produk!', 'warning').then(() => {
                    $(".preloader").fadeOut();
                    setTimeout(function () {
                        $(".modal-content").removeClass('mod-cont-blur');
                        $(".modal-body").removeClass('mod-bod-blur');
                    }, 500);
                });
                return false;
            } else {
                swal('Oopss!', 'Pilih minimal 1 Produk untuk dibeli!', 'warning').then(() => {
                    $(".preloader").fadeOut();
                    setTimeout(function () {
                        $(".modal-content").removeClass('mod-cont-blur');
                        $(".modal-body").removeClass('mod-bod-blur');
                    }, 500);
                });
                return false;
            }
        }

        if (fls == 0) {
            $("#add-row").addClass('disabled');
            $("#add-row").removeClass('add-row');

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
                            if (trs.length > 0 && fls == 0 && fls_ == 0) {
                                $("#add-row").removeClass('disabled')
                                $("#add-row").addClass('add-row')
                            }
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
                    if (trs.length == 0) {
                        $("#add-row").removeClass('disabled')
                        $("#add-row").addClass('add-row')
                    }

                    $(".preloader").fadeOut();
                    setTimeout(function () {
                        $(".modal-content").removeClass('mod-cont-blur');
                        $(".modal-body").removeClass('mod-bod-blur');
                    }, 500);
                }
            });
        }
    });
}

function submitCK() {

    $("form#formTransCKPembelian").submit(function (e) {
        e.preventDefault();

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

function peringatan(value) {
    var failin = 0;
    var total_all = 0;
    var trs = $('.data-pembelian tr.point-nom').length;
    for (var ii = 0; ii < trs; ii++) {
        var cek = $('select#on-prd-' + ii + ' option:selected').val();
        if (cek === value.val() && cek !== '') {
            failin++;
            var name = $('select#on-prd-' + ii + ' option:selected').text();
            var content = document.createElement('div');
            content.innerHTML = 'Duplikasi pilihan <strong>' + name + '</strong> !';
            swal({
                title: 'Ooopps!!...',
                content: content,
                icon: "warning",
            })
            setTimeout(function () {
                $(value).parents('tr').remove();
                $("#add-row").addClass('disabled');
                $("#add-row").removeClass('add-row');
                setTimeout(() => {
                    $("table tbody.data-pembelian").append(dt_row);
                    var trs = $('.data-pembelian tr.point-nom');
                    trs.each(function (e, f) {
                        $(trs[e]).find('td.nom').text(e + 1);
                        $(trs[e]).find('td.select').find('select').removeAttr('id').attr('id', 'on-prd-' + (e + 1));
                        $(trs[e]).find('td.select').find('input').removeAttr('id').attr('id', 'on-prd-val-' + (e + 1));
                        $(trs[e]).find('td.stk').find('input').removeAttr('id').attr('id', 'edit-on-id-val-' + (e + 1));
                        $(trs[e]).find('td.prc-buy').find('input').removeAttr('id').attr('id', 'prc-buy-' + (e + 1));
                        $(trs[e]).find('td.stk').find('em').removeAttr('id').attr('id', 'stok-' + (e + 1));
                        $(trs[e]).find('td.prc-sell').find('input').removeAttr('id').attr('id', 'prc-sell-' + (e + 1));
                        $(trs[e]).find('td.subtotal').find('input').removeAttr('id').attr('id', 'delete-sell-' + (e + 1));
                        $(trs[e]).find('td.qty').find('input').removeAttr('id').attr('id', 'qty-' + (e + 1));
                        $(trs[e]).find('td.subtotal').find('em').removeAttr('id').attr('id', 'subtotal-' + (e + 1));

                        var total = $(trs[e]).find('td.subtotal').find('em.subtotal').text()
                        if (!isNaN(total) && total) {
                            total_all += parseInt(total);
                        }
                    });
                    $(".total-belanja").val(total_all);
                }, 0);
            }, 0);
        }
    }
    return failin;
}

function back_true(e) {
    $('#prc-buy-' + (e + 1)).val('');
    $('#prc-sell-' + (e + 1)).val('');
    $('#delete-sell-' + (e + 1)).val('');
    $('#qty-' + (e + 1)).val('1');
    $('#stok-' + (e + 1)).text('0');
    $('#subtotal-' + (e + 1)).text('0');
}

function select_supplier(id) {
    var id_ = id || '';
    $.ajax({
        url: base_url + "/trans/show/supplier",
        method: "GET",
        dataType: 'json',
        success: function (data) {
            var html = `<option></option>`;
            var i;
            for (i = 0; i < data.length; i++) {
                var selected = data[i].id == id_ ? "selected" : '';
                html += `<option ` + selected + ` value='` + data[i].id + `'>` + data[i].nama + `</option>`;
            }
            $("select[name=ssupplier]").html(html);
        }
    });
}

function select_suppliers() {
    return `<select name="ssupplier" class="select2 form-control" style="width: 100%;" form="formTransPembelian"></select>`;
}

function input_supplier() {
    var html = '';
    html += `<input type="text" name="isupplier" class="form-control input-sm" placeholder="Supplier..." form="formTransPembelian">`;
    return html;
}

$('.edit-form-purchase-buy').on('click', function (e) {
    e.preventDefault()
    var url = $(this).data('href')
    $('.modal-content').html('').hide();
    $('.modal-content').load(url + ' .modal-content', function (e, s, f) {
        if (s === 'error') {
            var timer = 5; // timer in seconds
            (function customSwal() {
                swal({
                    title: "Oopps!",
                    text: "Error system, will close on " + timer + ' second(s) !',
                    timer: timer * 1000,
                    button: false,
                    icon: base_url + '/images/icons/loader.gif'
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
            $('.modal').modal('hide')
        }
    });
    setTimeout(function () {
        $('.modal-content').show();
        $('.modal-title').html('<em class="fa fa-pencil-square-o"></em> Form Pembelian')
        $(".preloader").fadeOut();
        select_supplier();
        $('.select2').select2({
            placeholder: "Please select!",
            allowClear: true,
            theme: "bootstrap"
        });
        setTimeout(function () {

            $("em.f-input-an").delegate('select[name=ssupplier]', 'change', function (e) {
                var id = $(this).val();
                var tlp = $(".telepon");
                var almt = $(".alamat");
                if (!id) {
                    tlp.attr({
                        'disabled': true
                    }).removeAttr('name');
                    almt.attr({
                        'disabled': true
                    }).removeAttr('name');
                    return false;
                }

                var token = $("input[name=_token]").val();
                $.ajax({
                    url: base_url + "/trans/supplier/explore",
                    method: "POST",
                    data: {
                        id: id,
                        _token: token
                    },
                    dataType: 'json',
                    success: function (data) {
                        tlp.attr({
                            'name': 'telepon',
                            'disabled': false
                        });
                        almt.attr({
                            'name': 'alamat',
                            'disabled': false
                        });

                    }
                });
            });

            $("div.change-to-field").delegate("div.add", "click", function (e) {
                var chg_ = $(this);
                var attr_ = $(".f-input-an");
                chg_.removeClass('bg-green add').addClass('bg-blue use');
                chg_.find('i').removeClass('fa-plus').addClass('fa-search');
                attr_.html(input_supplier());
            });

            $("div.change-to-field").delegate("div.use", "click", function (e) {
                var chg_ = $(this);
                var attr_ = $(".f-input-an");
                chg_.removeClass('bg-blue use').addClass('bg-green add');
                chg_.find('i').removeClass('fa-search').addClass('fa-plus');
                attr_.html(select_suppliers());
                select_supplier();
                $('.select2').select2({
                    placeholder: "Please select!",
                    allowClear: true,
                    theme: "bootstrap"
                });
            });
        }, 500);
    }, 1500);
});

function dt_row() {
    //variable element
    var thisElem = $(".point-nom");
    var numb = thisElem.length + 1;
    //end variable element
    //html
    var markup = `<tr class="point-nom">`
    markup += `<td class="nom td-height-img">` + numb + `</td>`
    markup += `<td class="select"><select class="select2 form-control produk-slct-avail" id="on-prd-` + numb + `"></select>`
    markup += `<input type="hidden" readonly disabled name="produk[]" id="on-prd-val-` + numb + `" form="formTransPembelian"></td>`
    markup += `<td class="prc-buy text-center"><input disabled type="text" name="harga[]" id="prc-buy-` + numb + `" class="form-control on-harga-buy" form="formTransPembelian"></td>`
    markup += `<td class="qty text-center"><input disabled id="qty-` + numb + `" type="number" name="jumlah[]" class="form-control on-qty" value="1"  min="1" form="formTransPembelian"></td>`
    markup += `<td class="stk text-center td-height-img"><input type="hidden" readonly name="idDetail[]" value="0" id="edit-on-id-val-` + numb + `" form="formTransPembelian"><em id="stok-` + numb + `" class="on-stok">0</em></td>`
    markup += `<td class="prc-sell text-center"><input disabled type="text" name="harga_jual[]" id="prc-sell-` + numb + `" class="form-control on-harga-sell" form="formTransPembelian"></td>`
    markup += `<td class="subtotal text-center td-height-img"><em id="subtotal-` + numb + `" class="subtotal">0</em></td>`
    markup += `<td class="text-center td-height-img"><em class="fa fa-times delete-rows text-danger"></em></td>`
    markup += `</tr>`;
    //end html
    setTimeout(() => {
        load_avail_produk(numb);
    }, 500);
    return markup;
}

function load_avail_produk(numb, p_id) {
    var pID = p_id || 0;
    $("select#on-prd-" + numb).select2({
        placeholder: "Please select!",
        allowClear: true
    });
    $.ajax({
        url: base_url + "/trans/show/produk",
        method: "GET",
        dataType: 'json',
        success: function (data) {
            var html = `<option></option>`;
            var i;
            for (i = 0; i < data.length; i++) {
                var selected = data[i].id == pID ? 'selected' : '';
                html += `<option data-sisa-stok='` + data[i].stok + `' data-harga-jual='` + data[i].harga_jual + `' ` + selected + ` value='` + data[i].id + `'>` + data[i].nama + `</option>`;
            }
            $("select#on-prd-" + numb).html(html);
        }
    });
}
