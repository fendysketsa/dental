function dt_row() {
    //variable element
    let thisElem = $(".point-nom");
    var numb = thisElem.length + 1;
    //end variable element
    //html
    var markup = `<tr class="point-nom">`
    markup += `<td class="nom td-height-img">` + numb + `</td>`
    markup += `<td class="textarea"><div class="input-group input-group-sm">`
    markup += `<textarea class="form-control min-height-ta" id="on-textarea-` + numb + `" name="keterangan[]" form="formTransPengeluaran"></textarea>`
    markup += `<div class="input-group-addon"><i class="fa fa-check" id="a-e"></i></div></div></td>`
    markup += `<td class="prc text-center"><input disabled type="text" name="harga[]" id="prc-` + numb + `" class="form-control on-harga input-sm" form="formTransPengeluaran"></td>`
    markup += `<td class="qty text-center"><input disabled id="qty-` + numb + `" type="number" name="jumlah[]" class="form-control on-qty input-sm" value="1"  min="1" form="formTransPengeluaran"></td>`
    markup += `<td class="subtotal text-center td-height-img"><em id="subtotal-` + numb + `" class="subtotal">0</em></td>`
    markup += `<td class="text-center td-height-img"><em class="fa fa-times delete-rows text-danger"></em></td>`
    markup += `</tr>`;
    //end html

    return markup;
}

function back_true(e) {
    $('#prc-' + (e + 1)).val('');
    $('#qty-' + (e + 1)).val('1');
    $('#delete-sell-' + (e + 1)).val('');
    $('#subtotal-' + (e + 1)).text('0');
}


function load_formAdd() {
    submit();
    $('.btn-form-pengeluaran').on('click', function () {
        $(".preloader").fadeIn();
        $('.load-form-modal').html('')
        $('.load-form-modal').load(base_url + '/trans/spends/sell/create' + ' .modal-content');
        setTimeout(function () {
            $('.modal-title').html('<em class="fa fa-pencil-square-o"></em> Form Pengeluaran')
            form_attribut();
            $(".preloader").fadeOut();
        }, 1500)
    });
}

function load_formEdit() {
    $('tbody').delegate('.edit', 'click', function () {
        var event = $(this);
        var target = event.data('route');
        $(".preloader").fadeIn();
        $('.load-form-modal').html('')
        $('.load-form-modal').load(target + ' .modal-content');
        setTimeout(function () {
            $('.modal-title').html('<em class="fa fa-pencil-square-o"></em> Form Pengeluaran')
            form_attribut('edit');
            load_detail_pengeluaran(postId);
        }, 1500);
    });
}

function load_formEdit() {
    $('tbody').delegate('.edit', 'click', function () {
        $('.load-form-modal').html('')
        var event = $(this);
        var dRoute = event.data('route').split('/');
        var dLength = dRoute.length;
        var dId = dRoute[dLength - 1];

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
                form_attribut('edit');
                load_detail_pengeluaran(dId);
            },
            error: function () {
                toastr.error('Gagal mengambil data', 'Oops!', {
                    timeOut: 2000
                });
            }
        });
    });
}

function load_detail_pengeluaran(id) {
    $.ajax({
        url: base_url + "/trans/spends/sell/load",
        method: "POST",
        data: {
            id: id,
            _token: $("meta[name=csrf-token]").attr('content')
        },
        dataType: 'json',
        success: function (data) {
            var total_all = 0;
            $.each(data, function (i, item) {

                var numb = i + 1;
                var markup = `<tr class="point-nom">`
                markup += `<td class="nom td-height-img">` + numb + `</td>`
                markup += `<td class="textarea"><div id="block"></div><input type="hidden" readonly name="idDetail[]" value="` + data[i].id + `" id="edit-on-id-val-` + numb + `" form="formTransPengeluaran"><div class="input-group input-group-sm">`
                markup += `<textarea class="form-control min-height-ta" id="on-textarea-` + numb + `" name="eketerangan[]" form="formTransPengeluaran">` + data[i].keterangan + `</textarea>`
                markup += `<div class="input-group-addon"><i class="fa fa-check" id="a-e"></i></div></div></td>`
                markup += `<td class="prc text-center"><input type="text" name="eharga[]" value="` + data[i].harga + `" id="prc-` + numb + `" class="form-control on-harga input-sm" form="formTransPengeluaran"></td>`
                markup += `<td class="qty text-center"><input id="qty-` + numb + `" type="number" value="` + data[i].jumlah + `" name="ejumlah[]" class="form-control on-qty input-sm" min="1" form="formTransPengeluaran"></td>`
                markup += `<td class="subtotal text-center td-height-img"><input type="hidden" class="delete-selected" name="delete_pengeluaran[]" value="" id="delete-sell-` + numb + `" form="formTransPengeluaran"><em id="subtotal-` + numb + `" class="subtotal">0</em></td>`
                markup += `<td class="text-center td-height-img"><em class="fa fa-times delete-rows-cancel text-danger"></em></td>`
                markup += `</tr>`;

                $("table tbody.data-pengeluaran").append(markup);

                var harga = data[i].harga;
                var qty = data[i].jumlah;
                var sub_harga = $("#subtotal-" + numb);
                var total = harga * qty;
                sub_harga.text(total);
                total_all += total;
            });
            $("table tbody.data-pengeluaran").append(dt_row);
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

function form_attribut(edit) {
    var tgl = $("input[name=tanggal]");
    $('.add-on-daterpicker').daterangepicker({
        singleDatePicker: true,
        autoUpdateInput: true,
        minDate: moment(),
        startDate: !tgl.val() ? moment() : moment().add().format(tgl.val()),
        locale: {
            format: 'DD-MM-YYYY'
        }
    }, addOn);

    if (!edit) {
        $("table tbody.data-pengeluaran").append(dt_row);
    }
    //start
    $(".add-rows").delegate('.add-row', 'click', function (ev) {
        var trs = $('.data-pengeluaran tr.point-nom');
        var fls = 0;
        trs.each(function (e, f) {
            var harga = $(trs[e]).find('input#prc-' + (e + 1));
            if (!harga.val()) {
                fls++;
                swal('Oopss!', 'Isikan harga', 'warning').then(() => {
                    harga.focus();
                });
                return false;
            }
        });
        if (fls == 0) {
            $("table tbody.data-pengeluaran").append(dt_row);
            $(trs).find('textarea').prop("readonly", true);
            $(trs).find('i#a-e').removeClass('fa-check').addClass('fa-pencil edit-keterangan')
            $("#add-row").addClass('disabled');
            $("#add-row").removeClass('add-row');
        }
    });

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
        var trs = $('.data-pengeluaran tr.point-nom');
        trs.each(function (e, f) {
            $(trs[e]).find('td.nom').text(e + 1);
            $(trs[e]).find('td.textarea').find('textarea').removeAttr('id').attr('id', 'on-textarea-' + (e + 1));
            $(trs[e]).find('td.textarea').find('input').removeAttr('id').attr('id', 'edit-on-id-val-' + (e + 1));
            $(trs[e]).find('td.prc').find('input').removeAttr('id').attr('id', 'prc-' + (e + 1));
            $(trs[e]).find('td.qty').find('input').removeAttr('id').attr('id', 'qty-' + (e + 1));
            $(trs[e]).find('td.subtotal').find('em').removeAttr('id').attr('id', 'subtotal-' + (e + 1));
            $(trs[e]).find('td.subtotal').find('input').removeAttr('id').attr('id', 'delete-sell-' + (e + 1));

            var valin = $(trs[e]).find('textarea#on-textarea-' + (e + 1)).val();
            var valin_prc = $(trs[e]).find('input#prc-' + (e + 1)).val();
            if (valin !== '' && valin_prc !== '') {
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
    //edit keterangan
    $("table tbody").delegate('tr > td > div > div > i.edit-keterangan', 'click', function (e) {
        $(this).parents("tr").find('i.edit-keterangan').removeClass('fa-pencil edit-keterangan').addClass('fa-check fix-edit');
        $(this).parents("tr").find('textarea').removeAttr('readonly');
    });

    $("table tbody").delegate('tr > td > div > div > i.fix-edit', 'click', function (e) {
        $(this).parents("tr").find('i.fix-edit').removeClass('fa-check fix-edit').addClass('fa-pencil edit-keterangan');
        $(this).parents("tr").find('textarea').attr('readonly', true);
    });

    //isian jumlah dan harga
    $("table tbody").delegate('tr > td > input', 'input change', function (e) {
        var trs = $('.data-pengeluaran tr.point-nom');
        var total_all = 0;
        trs.each(function (e, f) {
            var harga = $(trs[e]).find('input.on-harga').val();
            var qty = $(trs[e]).find('input.on-qty').val();
            var sub_harga = $(trs[e]).find('td.subtotal').find('em.subtotal');
            var total = harga * qty;
            sub_harga.text(total).trigger('change');
            if (!isNaN(sub_harga.text()) && sub_harga.text()) {
                total_all += parseInt(sub_harga.text());
            }
        });
        $(".total-belanja").val(total_all);
    });

    //isian keterangan
    $("table tbody").delegate('tr > td > div > textarea', 'input', function (e) {
        var trs = $('.data-pengeluaran tr.point-nom');
        var total_all = 0;
        trs.each(function (e, f) {

            var cek = $(trs[e]).find('textarea#on-textarea-' + (e + 1)).val();
            var total = $(trs[e]).find('td.subtotal').find('em.subtotal').text()

            if (!isNaN(total) && total) {
                total_all += parseInt(total);
            }

            if (!cek) {
                $("#add-row").addClass('disabled')
                $("#add-row").removeClass('add-row')
                $('#prc-' + (e + 1)).attr('disabled', true);
                $('#qty-' + (e + 1)).attr('disabled', true);
                back_true(e);
            } else {
                $("#add-row").removeClass('disabled')
                $("#add-row").addClass('add-row')
                $('#prc-' + (e + 1)).removeAttr('disabled');
                $('#qty-' + (e + 1)).removeAttr('disabled');

            }
        });
        $(".total-belanja").val(total_all);
    });
    //end
}

function load_data() {
    var cont = $(".load-data");
    cont.load(base_url + '/trans/spends/sell/data', function (e, s, f) {
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
    return !bilangan ? 0 : rupiah;
}

function data_attribut() {

    var dTable = $('#data-table-view').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/trans/spends/sell/json",
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
                data: 'total_pengeluaran',
                name: 'total_pengeluaran',
                render: convertRupiah
            },
            {
                data: 'pegawai',
                name: 'pegawai'
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
                    url: base_url + target + postId,
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

    $("form#formTransPengeluaran").submit(function (e) {
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

        var trs = $('.data-pengeluaran tr.point-nom');

        if (trs.length == 0) {
            swal('Oopss!', 'Isikan minimal 1 keterangan pengeluaran!', 'warning').then(() => {
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
            var keterangan = $(trs[e]).find('textarea#on-textarea-' + (e + 1));
            var harga = $(trs[e]).find('input#prc-' + (e + 1));
            if (keterangan.val()) {
                if (harga.length < 3 && harga.val() < 100) {
                    fls++;
                    swal('Oopss!', 'Isikan nominal harga pengeluaran minimal 100', 'warning').then(() => {
                        harga.focus();
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
                swal('Oopss!', 'Isikan keterangan!', 'warning').then(() => {
                    $(".preloader").fadeOut();
                    setTimeout(function () {
                        $(".modal-content").removeClass('mod-cont-blur');
                        $(".modal-body").removeClass('mod-bod-blur');
                    }, 500);
                });
                return false;
            } else {
                swal('Oopss!', 'Isikan minimal 1 keterangan pengeluaran!', 'warning').then(() => {
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

$(document).ready(function () {
    load_formAdd();
    load_data();
});
