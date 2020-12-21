function load_data() {
    var cont = $(".load-data");
    cont.load(base_url + '/payments/info/data', function (e, s, f) {
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

    var ElemtStart = rangeDate(0, 'back');
    var ElemtEnd = rangeDate(1, 'back');

    var dateranges = (ElemtStart ? '?starts=' + ElemtStart : '') +
        (ElemtEnd ? '&ends=' + ElemtEnd : '');

    var groupColumn = 1;
    var dTable = $('#data-table-view-payment-info').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/payments/info/json" + dateranges,
            type: 'GET',
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'no_member',
                name: 'no_member',
                searchable: false
            },
            {
                data: 'nama_member',
                name: 'nama_member',
                searchable: false
            },
            {
                data: 'no_transaksi',
                name: 'no_transaksi',
                className: "text-center"
            },
            {
                data: 'waktu_reservasi',
                name: 'waktu_reservasi',
                className: "text-center",
                render: function (data, type, row) {
                    return data ? getIndoDate(data) : '';
                },
                searchable: false
            },
            {
                data: 'created_at',
                name: 'created_at',
                className: "text-center",
                render: function (data, type, row) {
                    return data ? getIndoDate(data) : '';
                },
                searchable: false
            },
            {
                data: 'hutang_biaya',
                name: 'hutang_biaya',
                render: convertRupiah,
                searchable: false
            },
            {
                data: 'total_biaya',
                name: 'total_biaya',
                render: convertRupiah,
                searchable: false
            },
            {
                data: 'agent',
                name: 'agent',
                orderable: false,
                className: "text-center",
                searchable: false
            },
            {
                data: 'lunas',
                name: 'lunas',
                orderable: false,
                className: "text-center",
                searchable: false
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
        ],
        drawCallback: function (settings) {
            var api = this.api();
            var rows = api.rows({
                page: 'current'
            }).nodes();
            var last = null;

            api.column(groupColumn, {
                page: 'current'
            }).data().each(function (group, i) {
                if (last !== group) {
                    $(rows).eq(i).before(
                        '<tr class="group text-center" style="color:#fff !important; background-color:#7d8084 !important;"><td colspan="11"><strong>' + group + ' ( ' + api.column(2).data()[i] + ' ) ' + '</strong></td></tr>'
                    );

                    last = group;
                }
            });
        }
    });

    $("select[name=data-table-view_length]").on('change', function () {
        dTable.ajax.reload();
    });
    $("input[type=search]").on('input', function (e) {
        dTable.ajax.reload();
    });

    $('.add-on-daterpicker').on('apply.daterangepicker', function (ev, picker) {
        fill_field_daterange(picker, dTable);
    });

    $('.add-on-daterpicker').on('cancel.daterangepicker', function (ev, picker) {
        remove_field_daterange();
    });

    $('.group-date-range').delegate('.remove-on-daterpicker', 'click', function () {
        remove_field_daterange();
    });

    $('.add-on-daterpicker').daterangepicker({
        alwaysShowCalendars: true,
        showCustomRangeLabel: true,
        startDate: !$("input[name=id]").val() ? moment().add(0, 'd').toDate() : $("input[name=berlaku_dari]").val(),
        endDate: !$("input[name=id]").val() ? moment().add(1, 'd').toDate() : $("input[name=berlaku_sampai]").val(),
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

    $("input[type=search]").attr('placeholder', 'Cari No Transaksi');

    load_detailPembayaran();
    load_printCase();
}

function fill_field_daterange(picker) {
    $("input[name='berlaku_dari']").val(picker.startDate.format('DD-MM-YYYY'));
    $("input[name='berlaku_sampai']").val(picker.endDate.format('DD-MM-YYYY'));

    $('#data-table-view-payment-info').DataTable().ajax.url(base_url +
        "/payments/info/json?starts=" + picker.startDate.format('YYYY-MM-DD') +
        "&ends=" + picker.endDate.format('YYYY-MM-DD')).load();
}

function remove_field_daterange() {
    $("input[name='berlaku_dari']").val('');
    $("input[name='berlaku_sampai']").val('');

    $('#data-table-view-payment-info').DataTable().ajax.url(base_url +
        "/payments/info/json").load();
}

function rangeDate(range_, back) {

    Date.prototype.addDays = function (days) {
        var date = new Date(this.valueOf());
        date.setDate(date.getDate() + days);
        return date;
    }

    var date = new Date();
    var ranges = range_
    var currentTime = date.addDays(ranges);
    var day = currentTime.getDate();
    var month = currentTime.getMonth() + 1;
    var year = currentTime.getFullYear();

    if (day < 10) {
        day = "0" + day;
    }

    if (month < 10) {
        month = "0" + month;
    }

    var today_date = !back ? day + "-" + month + "-" + year : year + "-" + month + "-" + day;

    return today_date.toString();
}

function load_detailPembayaran() {
    $('tbody').delegate('.detail', 'click', function () {
        var event = $(this);
        $("#load-detail").html('')
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'GET',
            url: event.data('route'),
            success: function (result) {
                $("#load-detail").html(result)
                $(".modal-title").html('<em class="fa fa-search"></em> Detail Pembayaran')

                var EId = $("em#detailPembayaran");
                setTimeout(function () {
                    $(".load-detail-left-info-transaksi").html(contInfoTrans(EId))
                    getMemberInfo(EId.data('member'));
                    load_detailRightOrder()

                    setTimeout(() => {

                        setTimeout(function () {
                            $(".load-row-layanan").html('');
                            for (var rL = 1; rL <= EId.data('layanan').length; rL++) {
                                $(".load-row-layanan").append(load_row_layanan(rL));
                            }
                            if (EId.data('layanan').length == 0) {
                                if (EId.data('paket').length == 0 && EId.data('layanan').length == 0) {
                                    $(".load-detail-right-order").html('<em class="fa fa-warning"></em> tidak layanan yang dipilih').addClass('text-center').attr('style', 'margin-bottom:20px;');
                                }
                            }
                        }, 1000);

                        setTimeout(() => {
                            $(".load-row-paket").html('');
                            for (var rP = 1; rP <= EId.data('paket').length; rP++) {
                                $(".load-row-paket").append(load_row_paket(rP));
                            }
                            if (EId.data('paket').length == 0) {
                                $(".load-detail-right-paket").html('<em class="fa fa-warning"></em> tidak paket yang dipilih').addClass('text-center').attr('style', 'margin-bottom:20px;');
                            } else {
                                $(".load-detail-right-paket").html('');
                                if (EId.data('layanan').length == 0) {
                                    $(".load-form-table-layanan").html('<em class="fa fa-warning"></em> tidak layanan yang dipilih').addClass('text-center').attr('style', 'margin-bottom:20px;');
                                }
                            }
                        }, 1500);

                        setTimeout(() => {
                            $(".loading-data-produk").html('');
                            for (var rPd = 1; rPd <= EId.data('produk').length; rPd++) {
                                $(".data-product").append(load_row_produk(rPd));
                            }
                            if (EId.data('produk').length == 0) {
                                $(".loading-data-produk").html('<td colspan="6" class="text-center"><em class="fa fa-warning"></em> tidak ada produk yang dipilih</td>').addClass('text-center');
                                $(".total-belanja-produk").text('0')
                            }
                        }, 2000);

                        setTimeout(() => {
                            var caraBayar = !EId.data('cara-bayar') ? '-' : (EId.data('cara-bayar') == 1 ? 'Cash' : 'Card');
                            $(".t-diskon").html(convertRupiah(EId.data('diskon')));
                            setTimeout(() => {
                                $(".total-belanja").html(convertRupiah(EId.data('total-biaya')));
                            }, 500);
                            $(".cara-bayar").html(caraBayar);
                            $(".total-nominal-bayar").html(convertRupiah(EId.data('nominal-bayar')));
                            $(".total-kembalian").html(convertRupiah(EId.data('kembalian-bayar')));
                        }, 500);
                    }, 2000);
                }, 1500);
            },
            error: function () {
                toastr.error('Gagal mengambil data', 'Oops!', {
                    timeOut: 2000
                });
            }
        });
    })
}

function load_row_produk(idProduk) {

    let thisElem = $(".point-nom");
    var numb = thisElem.length + 1;

    var markup = `<tr class="point-nom">`
    markup += `<td class="nom" style="vertical-align:middle;">` + numb + `</td>`
    markup += `<td class="select input-group-sm" style="vertical-align:middle;">`
    markup += `<select style="width:100%;" class="select2 form-control produk-slct-avail" id="on-prd-` + numb + `"></select>
    <input type="hidden" name="produk[]" form="formKasir" id="on-prd-value-` + numb + `"></td>`
    markup += `<td class="prc text-center" style="vertical-align:middle;"><em id="prc-` + numb + `" class="on-harga">0</em></td>`
    markup += `<td class="qty text-center input-group-sm" style="vertical-align:middle;">`
    markup += `<input disabled name="jml_produk[]" id="qty-` + numb + `" type="number" class="form-control on-qty input-sm" value="1"  min="1" form="formKasir"></td>`
    // markup += `<td class="disc text-center" style="vertical-align:middle;"><em id="disc-` + numb + `" class="on-disc">0</em></td>`
    markup += `<td class="subtotal text-center" style="vertical-align:middle;"><em id="subtotal-` + numb + `" class="subtotal">0</em></td>`
    markup += `</tr>`;
    //end html

    setTimeout(() => {

        if (idProduk) {
            load_avail_produk(numb, $("em#detailPembayaran").data('produk')[idProduk - 1]);
        } else {
            load_avail_produk(numb);
        }

        $("select#on-prd-" + numb).select2({
            placeholder: "Please select!",
            allowClear: true,
            templateResult: formatState,
            theme: "bootstrap"
        });

    }, 500);

    return markup;
}

function load_row_layanan(idLayanan, idTerapis) {
    var thisElem = $(".n-f-layanan");
    var numb = thisElem.length + 1;

    var html = `<tr class="n-f-layanan">`;
    html += `<td class="nom-layanan td-height-img text-center">` + numb + `</td>`;
    html += `<td class="select-layanan td-height-img">
                <div id="block" class="blocking-loading-row"><em class="fa fa-spinner fa-spin"></em> Loading...</div>
                <div class="input-group-sm">
                    <select name="layanan[]" class="select2 form-control input-group-sm" disabled id="on-select-layanan-` + numb + `"></select>
                </div>
            </td>`;
    html += `<td class="select-terapis td-height-img">
                <div class="input-group-sm">
                    <select name="terapis[]" class="select2 form-control input-group-sm" disabled id="on-select-terapis-` + numb + `"></select>
                </div>
            </td>`;
    html += `</tr>`;

    setTimeout(() => {

        if (idLayanan) {
            load_avail_layanan('layanan', numb, $("em#detailPembayaran").data('layanan')[idLayanan - 1]);
            load_avail_layanan('terapis', numb, $("em#detailPembayaran").data('layanan')[idLayanan - 1], $("em#detailPembayaran").data('terapis')[idLayanan - 1]);
        } else {
            load_avail_layanan('layanan', numb);
        }

    }, 500);

    return html;
}

function load_row_paket(idPket) {
    var thisElem = $(".n-f-paket");
    var numb = thisElem.length + 1;

    var html = `<tr class="n-f-paket">`;
    html += `<td class="nom-paket text-center">` + numb + `</td>`;
    html += `<td class="select-paket td-height-img">
                <div id="block" class="blocking-loading-row"><em class="fa fa-spinner fa-spin"></em> Loading...</div>
                <div class="input-group-sm">
                    <select name="paket[]" class="select2 form-control input-group-sm" disabled id="on-select-paket-` + numb + `"></select>
                </div>`
    html += `<div id="table-th" class="row load-more-paket-` + numb + ` hide" style="margin-top:10px !important;">
                <div class="col-md-12">
                    <table class="table hover" width="100%" cellspacing="0" style="margin-bottom: 0px !important;">
                        <thead class="bg-navy disabled color-palette">
                            <tr>
                                <th class="text-center" style="width:5%;">No</th>
                                <th class="text-left" style="width:55%;">Layanan</th>
                                <th class="text-left" style="width:40%">Terapis</th>
                            </tr>
                        </thead>
                        <tbody id="table-td" class="load-row-paketlayanan-` + numb + `">
                            <tr>
                                <td colspan="4">
                                    <em class='fa fa-spin fa-spinner'></em> Loading...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>`;
    html += `</td>`;
    html += `</tr>`;

    setTimeout(() => {

        if (idPket) {

            var exp = $("button.collap-paket").attr('aria-expanded');
            if (exp == 'true') {
                $("#cont-paket").removeClass('in').removeAttr('aria-expanded', false)
                $("button.collap-paket").find('em').removeClass('fa-minus').addClass('fa-plus').trigger('change')
            } else {
                $("#cont-paket").addClass('in').attr('aria-expanded', true)
                $("button.collap-paket").find('em').removeClass('fa-plus').addClass('fa-minus').trigger('change')
            }

            load_avail_layanan('paket', numb, $("em#detailPembayaran").data('paket')[idPket - 1]);
            $(".load-more-paket-" + numb).removeClass('hide')
            load_avail_layanan_on_paket('layanan', numb, $("em#detailPembayaran").data('paket')[idPket - 1], $("em#detailPembayaran").data('paket-terapis-' + numb).length);
        } else {
            load_avail_layanan('paket', numb);
        }

        $(".input-group-sm").delegate("#on-select-paket-" + numb, 'change', function (e) {
            var PktlayId = $(this).val()
            loadTotal()

            if (PktlayId) {
                $(".load-more-paket-" + numb).removeClass('hide')
                load_avail_layanan_on_paket('layanan', numb, PktlayId);
            } else {
                $(".load-more-paket-" + numb).addClass('hide')
                var trps = $("#on-select-onpkt-terapis-" + numb);
                trps.attr('disabled', true);
                trps.val('').trigger('change')
            }
        });
    }, 500);

    return html;
}

function load_avail_layanan(table, numb, p_id, p_terps) {
    var pID = p_id || 0;
    var pTrID = p_terps || 0;

    $.ajax({
        url: base_url + "/registrations/opt/" + table + '?loaded=detail' + (table == 'terapis' ? '&layanan=' + pID : ''),
        method: "GET",
        dataType: 'json',
        success: function (data) {
            var html = `<option></option>`;
            var i;
            for (i = 0; i < data.length; i++) {
                if (table == 'layanan') {
                    html += `<optgroup id="` + data[i].id + `" label="` + data[i].nama + `">`;
                    for (var ii = 0; ii < data[i].data.length; ii++) {
                        var harga_ = `data-harga='` + data[i].data[ii].harga + `'`;
                        var selectedd_ = data[i].data[ii].id === pID ? 'selected' : '';

                        html += `<option ` + harga_ + ` alt="` + data[i].nama + `" value='` + data[i].data[ii].id + `' ` + selectedd_ + `>` +
                            data[i].data[ii].nama + `</option>`;
                    }
                    html += `</optgroup>`;
                } else if (table != 'layanan') {
                    var harga = table == 'paket' ? `data-harga='` + data[i].harga + `'` : '';
                    var selectedTrps = table == 'terapis' ? (data[i].id == pTrID ? 'selected' : '') : '';
                    var selectedPkt = table == 'paket' ? (data[i].id == pID ? 'selected' : '') : '';

                    html += `<option ` + harga + selectedTrps + selectedPkt + ` value='` + data[i].id + `'>` + data[i].nama + `</option>`;
                }
            }
            $("select#on-select-" + table + "-" + numb).html(html);
        },
        complete: function () {
            $(".select-" + table).find("div#block").removeClass('blocking-loading-row').addClass('hide')

            $("select#on-select-" + table + "-" + numb).select2({
                placeholder: "Please select!",
                allowClear: true,
                theme: "bootstrap"
            });
        }
    });
}

function formatState(state) {
    if (!state.id) {
        return state.text;
    }
    var img_load = '/s-home/master-data/product/uploads/'
    var img = state.element.attributes[0].value == 'null' ? "/images/noimage.jpg" : img_load + state.element.attributes[0].value;

    var $state = $(
        '<span><img width="70" height="50" src="' + base_url + img + '" /> ' + state.text + '</span>'
    );

    return $state;
};

function load_avail_produk(numb, Id) {
    var id_ = Id || ''
    $("select#on-prd-" + numb).select2({
        placeholder: "Please select!",
        allowClear: true,
        templateResult: formatState,
        theme: "bootstrap"
    });
    $.ajax({
        url: base_url + "/trans/show/produk",
        method: "GET",
        dataType: 'json',
        success: function (data) {
            var html = `<option></option>`;
            var i;
            for (i = 0; i < data.length; i++) {
                var selected = data[i].id == id_ ? 'selected' : '';
                html += `<option ` + selected + ` data-gambar='` + data[i].gambar + `' data-harga='` + data[i].harga_jual + `' data-harga-member='` + data[i].harga_jual_member + `' value='` + data[i].id + `'>` + data[i].nama + `</option>`;
            }
            $("select#on-prd-" + numb).html(html);
            if (id_ && $("select#on-prd-" + numb).val() == id_) {
                setTimeout(() => {
                    $("select#on-prd-" + numb).prop('disabled', true);
                    $("input#on-prd-value-" + numb).val($("#detailPembayaran").data('produk')[numb - 1]);
                    $("em#prc-" + numb).text($("#detailPembayaran").data('produk-harga')[numb - 1]);
                    $("input#qty-" + numb).val($("#detailPembayaran").data('produk-jumlah')[numb - 1]);

                    totalan();
                    loadTotal();
                }, 500);
            }
        },
    });
}

function loadTotal() {
    var total_harga_paket = 0;
    var total_harga_layanan = 0;
    var total_harga_produk = 0;

    for (var vp = 1; vp <= $(".n-f-paket").length; vp++) {
        var vvp = $("select#on-select-paket-" + vp).find('option:selected').data('harga');
        if (!isNaN(vvp) && $("select#on-select-paket-" + vp).val() != 'undefined') {
            total_harga_paket += parseInt(vvp)
        }
    }

    for (var vl = 1; vl <= $(".n-f-layanan").length; vl++) {
        var vvl = $("select#on-select-layanan-" + vl).find('option:selected').data('harga');
        if (!isNaN(vvl) && $("select#on-select-layanan-" + vl).val() != 'undefined') {
            total_harga_layanan += parseInt(vvl)
        }
    }

    for (var vpr = 1; vpr <= $(".point-nom").length; vpr++) {
        var vvpr = $("td.subtotal").find('em#subtotal-' + vpr).text();
        if (!isNaN(vvpr) && $("td.subtotal").find('em#subtotal-' + vpr).text() != 'undefined') {
            total_harga_produk += parseInt(vvpr)
        }
    }

    var Real = total_harga_paket + total_harga_layanan +
        total_harga_produk

    setTimeout(function () {
        $(".total-belanja").text(convertRupiah(Real));
    }, 0);

}

function totalan() {
    var trs = $('.data-product tr.point-nom');
    var total_all = 0;

    trs.each(function (e, f) {
        var harga = $(trs[e]).find('em.on-harga').text();
        var qty = $(trs[e]).find('input.on-qty').val();
        var sub_harga = $(trs[e]).find('em.subtotal');
        var total = harga * qty;
        sub_harga.text(total);

        total_all += total;
    });
    $(".total-belanja-produk").text(convertRupiah(total_all));

}

function load_avail_layanan_on_paket(table, numb, p_id, p_serv) {
    var pID = p_id || 0;
    var pSID = p_serv || 0;
    var html = '';

    $.ajax({
        url: base_url + "/registrations/opts/" + table + '?paket=' + pID,
        method: "GET",
        dataType: 'json',
        success: function (data) {

            for (var i = 0; i < data.length; i++) {

                html += `<tr>`;
                html += `<td class="text-center">` + (i + 1) + `</td>`;
                html += `<td>` + data[i].nama + `</td>`;
                html += `<td>
                            <div id="on-input-paketlayananterapis-` + numb + `-lay-` + data[i].id + `"></div>
                            <div class="input-group-sm">
                                <select name="pkt_layanan_terapis[` + numb + `][]" form="formKasir" class="select2 form-control input-group-sm" disabled
                                    id="on-select-paketlayananterapis-` + numb + `-lay-` + data[i].id + `"></select>
                            </div>
                        </td>`;
                html += `</tr>`;

                load_avail_playanant('pegawai', numb, data[i].id, i);
            }
            $(".load-row-paketlayanan-" + numb).html(html);
        },
        always: function () {
            setTimeout(() => {
                $(".select-layanan").find("div#block").removeClass('blocking-loading-row').addClass('hide')
            }, 1000);
        }
    });
}

function load_avail_playanant(table, numb, layanan, p_servs) {
    var pID = layanan || 0;
    var pSIDs = p_servs || 0;

    $.ajax({
        url: base_url + "/registrations/opt-terapis/" + table + '?layanan=' + pID,
        method: "GET",
        dataType: 'json',
        success: function (data) {

            if (data.length === 0) {
                $("select#on-select-paketlayananterapis-" + numb + '-lay-' + pID).attr('disabled', true).removeAttr('name,form');
                $("div#on-input-paketlayananterapis-" + numb + '-lay-' + pID).html(`<input type="hidden" form="formKasir" name="pkt_layanan_terapis[` + numb + `][]" readonly value="0">`);
            } else {
                // $("select#on-select-paketlayananterapis-" + numb + '-lay-' + pID).removeAttr('disabled');
                setTimeout(() => {
                    $("select#on-select-paketlayananterapis-" + numb + '-lay-' + pID).select2({
                        placeholder: "Please select!",
                        allowClear: true,
                        theme: "bootstrap"
                    });
                }, 1000);
            }

            var html = [];
            html += `<option></option>`;

            for (var i = 0; i < data.length; i++) {
                var selectedTrps = table == 'pegawai' ? ($("em#detailPembayaran").data('paket-terapis-' + numb) ? (data[i].id == $("em#detailPembayaran").data('paket-terapis-' + numb)[pSIDs] ? 'selected' : '') : '') : '';
                html += `<option ` + selectedTrps + ` value='` + data[i].id + `'>` + data[i].nama + `</option>`;
                $("select#on-select-paketlayananterapis-" + numb + '-lay-' + pID).html(html);
            }

            $("select#on-select-paketlayananterapis-" + numb + '-lay-' + pID).select2({
                placeholder: "Please select!",
                allowClear: true,
                theme: "bootstrap"
            });
            if (s == 'error') {
                var fls = 'Gagal memuat form!';
                toastr.error(fls, 'Oops!', {
                    timeOut: 2000
                })
                cont.html(fls);
            } else {
                $(".display-future").removeClass('blocking-content');
            }
        }
    });
}

function getMemberInfo(id) {
    var token = $("meta[name=csrf-token]").attr('content');
    $.ajax({
        url: base_url + "/cashiers/member/explore",
        method: "POST",
        data: {
            id: id,
            _token: token
        },
        dataType: 'json',
        success: function (data) {
            $(".load-detail-left-info-member").html(contInfoMember(data[0]))
        }
    });
}

function contInfoTrans(idN) {
    var tglReservasi = idN.data('reservasi');
    var html = `<ul class="list-group list-group-flush">
                    <li class="list-group-item"><i class="fa fa-check"></i> No. Transaksi: <em class="font-weight-bold pull-right" style="font-weight:bolder;">` + idN.data('transaksi-no') + `</em></li>
                    <li class="list-group-item"><i class="fa fa-check"></i> Waktu Pendaftaran:
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fa fa-calendar"></i>
                                Tanggal: <em class="font-weight-bold pull-right" style="font-weight:bolder;">` + getIndoDate(idN.data('tanggal-transaksi')) + `</em>
                                    </li>
                            <li class="list-group-item"><i class="fa fa-clock-o"></i>
                                Jam: <em class="font-weight-bold pull-right" style="font-weight:bolder;">` + idN.data('jam-transaksi') + `</em>
                                    </li>
                        </ul>
                    </li>`;
    if (tglReservasi) {
        html += `<li class="list-group-item"><i class="fa fa-check"></i> Waktu Reservasi:
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fa fa-calendar"></i>
                                Tanggal: <em class="font-weight-bold pull-right" style="font-weight:bolder;">` + getIndoDate(idN.data('tanggal-reservasi')) + `</em>
                                    </li>
                            <li class="list-group-item"><i class="fa fa-clock-o"></i>
                                Jam: <em class="font-weight-bold pull-right" style="font-weight:bolder;">` + idN.data('jam-reservasi') + `</em>
                                    </li>
                        </ul>
                </li>`;
    }
    html += `</ul>`;
    return html;
}

function imgError(image) {
    image.onerror = "";
    image.src = "/images/brokenimage.jpg";
    image.alt = "Images corrupt!";
    image.title = "Images corrupt!";
    return true;
}

function getImg(data) {
    var img = !data ? '/images/noimage.jpg' : '/storage/master-data/member/uploads/' + data;
    return '<img onerror="imgError(this);" style="border-radius:50%;" width="100" height="100" src="' + base_url + img + '">';
}

function contInfoMember(idN) {
    var html = `<ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                ` + getImg(idN.foto) + `
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item"><em class="fa fa-fa fa-bookmark-o"></em>
                        <em class="font-weight-bold pull-right" style="font-weight:bolder;">` + idN.no_member + `</em></li>
                    <li class="list-group-item"><em class="fa fa-tag"></em>
                        <em class="font-weight-bold pull-right" style="font-weight:bolder;">` + idN.nama + `</em></li>
                    <li class="list-group-item"><em class="fa fa-child"></em>
                        <em class="font-weight-bold pull-right" style="font-weight:bolder;">` + idN.jenis_kelamin + `</em></li>
                    <li class="list-group-item"><em class="fa fa-map-signs"></em>
                        <em class="font-weight-bold pull-right" style="font-weight:bolder;">` + (idN.alamat ? idN.alamat : '-') + `</em></li>
                    <li class="list-group-item"><em class="fa fa-phone"></em>
                        <em class="font-weight-bold pull-right" style="font-weight:bolder;">` + idN.telepon + `</em></li>
                    <li class="list-group-item"><em class="fa fa-envelope"></em>
                        <em class="font-weight-bold pull-right" style="font-weight:bolder;">` + idN.email + `</em></li>`;
    html += `</ul>`;
    return html;
}

function toPrint(event) {
    var target = base_url + '/monitoring/order/det';
    var idCetak = event.data('id-cetak');

    $.ajax({
        url: target + '/' + idCetak,
        type: 'GET',
        success: function (data) {
            rePrint(event, data)
        }
    });
}

function currentDate() {
    var today = new Date();
    var date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    return date + ' ' + time;
}

function rePrint(events, dataTrans) {
    var jumPaket = 0;
    localStorage.setItem("error-print", "");
    var kasire = $(".user-panel").find('p').text().trim() || '';
    var operatorCbang = $(".user-panel").find('p').data('branch-id') || '';
    var operatorCbangCode = $(".user-panel").find('p').data('branch-code') || '';
    var printer = new Recta('7963354012', '1811')
    printer.open().then(function () {
        printer.align('center')
            .text('GULA Waxing - Make Up');

        if (operatorCbang == 2 || !operatorCbang) {
            printer.align('center').text('Jl. Gejayan No. 11').text('Yogyakarta').text('(0274)-589946');
        }

        if (operatorCbang == 3) {
            printer.align('center').text('Jl. Jeruk Timur II No. 7').text('Magelang Utara').text('081 6677 225');
        }

        if (operatorCbang == 4) {
            printer.align('center').text('Jl. Tambakbayan 9 No. 1').text('Yogyakarta');
        }

        if (operatorCbang == 5) {
            printer.align('center').text('Jl. Jambon No.95, Trihanggo').text('Yogyakarta').text('(0274) -589954');
        }

        printer.align('center').text("--------------------------------").text('REPRINT')
            .feed(1)
            .align('left').text('No. Trans' + sprintf('%3s', ': ') + sprintf('%20s', 'GW-' + operatorCbangCode + '-' + dataTrans.data.no_transaksi))
            .align('left').text('Tanggal' + sprintf('%5s', ': ') + sprintf('%20s', getIndoDate(dataTrans.data.created_at)))
            .align('left').text('Cetak' + sprintf('%7s', ': ') + sprintf('%20s', currentDate()))
            .align('left').text('No. Member' + sprintf('%2s', ': ') + sprintf('%20s', dataTrans.data.no_member))
            .align('left').text('Member' + sprintf('%6s', ': ') + sprintf('%20s', dataTrans.data.member))
            .align('left').text('Kasir' + sprintf('%7s', ': ') + sprintf('%20s', kasire))
            .feed(1).align('center').text("--------------------------------");

        if (dataTrans.layanan.length > 0) {
            for (var lyn = 0; lyn < dataTrans.layanan.length; lyn++) {
                printer.align('left')
                    .text(dataTrans.layanan[lyn].kategori.substring(0, 25))
                    .align('left').text(
                        sprintf('%s', ' 1 X ' + dataTrans.layanan[lyn].layanan.substring(0, 13)) +
                        sprintf('%' +
                            (
                                (dataTrans.layanan[lyn].layanan.substring(0, 13).length == 13 ? 14 :
                                    (
                                        dataTrans.layanan[lyn].layanan.substring(0, 13).length == 12 ? 15 :
                                        (
                                            dataTrans.layanan[lyn].layanan.substring(0, 13).length == 11 ? 16 :
                                            (
                                                dataTrans.layanan[lyn].layanan.substring(0, 13).length == 10 ? 17 :
                                                (
                                                    dataTrans.layanan[lyn].layanan.substring(0, 13).length == 9 ? 18 :
                                                    (
                                                        dataTrans.layanan[lyn].layanan.substring(0, 13).length == 8 ? 19 :
                                                        (
                                                            dataTrans.layanan[lyn].layanan.substring(0, 13).length == 7 ? 20 :
                                                            (
                                                                dataTrans.layanan[lyn].layanan.substring(0, 13).length == 6 ? 21 :
                                                                (
                                                                    dataTrans.layanan[lyn].layanan.substring(0, 13).length == 5 ? 22 : 23
                                                                )
                                                            )
                                                        )
                                                    )
                                                )
                                            )
                                        )
                                    )
                                )
                            ) + 's', convertRupiah(dataTrans.layanan[lyn].harga))
                    );
            }
        }

        if (dataTrans.paket.length > 0) {
            for (var pktl = 0; pktl < dataTrans.paket.length; pktl++) {
                if (dataTrans.paket[pktl].paket) {
                    jumPaket += 1;
                    printer.align('left').text(dataTrans.paket[pktl].paket.substring(0, 25));
                    printer.align('left').text(' 1 X ' + '               ' +
                        sprintf('%12s', convertRupiah(dataTrans.paket[pktl].harga_paket)));
                }
            }
        }

        if (dataTrans.produk.length > 0) {
            for (var lyn = 0; lyn < dataTrans.produk.length; lyn++) {
                printer.align('left').text(dataTrans.produk[lyn].produk.substring(0, 25));
                var texts = sprintf('%s', ' ' + convertRupiah(dataTrans.produk[lyn].kuantitas) +
                        ' X ' +
                        convertRupiah(dataTrans.produk[lyn].harga)) +
                    sprintf('%' + (
                            convertRupiah(parseInt(dataTrans.produk[lyn].kuantitas * dataTrans.produk[lyn].harga)).length > 6 ? (
                                convertRupiah(parseInt(dataTrans.produk[lyn].kuantitas * dataTrans.produk[lyn].harga)).length > 8 ? 18 : 20
                            ) : 21) + 's',
                        convertRupiah(parseInt(dataTrans.produk[lyn].kuantitas * dataTrans.produk[lyn].harga))
                    );
                printer.align('left').text(texts);
            }
        }

        printer.align('center').text("--------------------------------");

        printer.align('left').text('Total Harga' +
            sprintf('%3s', ':') +
            sprintf('%18s', convertRupiah(dataTrans.data.total_biaya))
        );

        var Bayare = dataTrans.data.cara_bayar_kasir == 1 ? 'Cash' : 'Card';

        printer.align('left').text(Bayare +
            sprintf('%10s', ':') +
            sprintf('%18s', (dataTrans.data.nominal_bayar ? convertRupiah(dataTrans.data.nominal_bayar) : '0'))
        );

        printer.align('left').text('Diskon Total' +
                sprintf('%2s', ':') +
                sprintf('%18s', (dataTrans.data.diskon ? convertRupiah(dataTrans.data.diskon) : '0')))
            .align('left').text('Grand Total' +
                sprintf('%3s', ':') +
                sprintf('%18s', (dataTrans.data.total_biaya ? (dataTrans.data.diskon ? convertRupiah(parseInt(dataTrans.data.total_biaya - dataTrans.data.diskon)) : convertRupiah(dataTrans.data.total_biaya)) : '0')))

        if (dataTrans.data.cara_bayar_kasir == 1) {
            printer.align('left').text('Kembalian' +
                sprintf('%5s', ':') + sprintf('%18s', convertRupiah(dataTrans.data.kembalian)))
        }

        var totalItems = parseInt(dataTrans.layanan.length + jumPaket + dataTrans.produk.length);

        printer
            .align('center').text("-------------------------------")
            .align('center').text("*****  " + totalItems + ' item' + (totalItems > 1 ? '(s)' : '') + "  *****")
            .align('center').text("===============================").text('Terimakasih atas kunjungan Anda').text('Mohon periksa uang kembalian')
            .align('center').text('')
            .cut()
            .print();

    }).catch(function (e) {
        if (e) {
            localStorage.setItem("error-print", "tunda-print");
            toastr.error('Gagal koneksi ke printer!', 'Error!', {
                timeOut: 5000
            })
            return
        }
        Pace.stop()
    }).finally(function () {
        var Err = localStorage.getItem("error-print");
        if (!Err) {
            toastr.success('Cetak Pembayaran sukses!', 'Yeaay!', {
                timeOut: 2000
            })
        }
    });

    var timer = 5; // timer in seconds
    (function customSwal() {
        swal({
            title: "Proses",
            text: "Sedang mencetak, menutup otomatis pada " + timer + ' detik !',
            timer: timer * 1000,
            button: false,
            icon: base_url + '/images/icons/loader.gif',
            closeOnClickOutside: false,
            closeOnEsc: false
        }).then(() => {
            Pace.stop()
            swal.close()
        });

        if (timer) {
            timer--;
            if (timer > 0) {
                setTimeout(customSwal, 500);
            }
        }
    })();

}

function load_printCase() {
    $('tbody').delegate('.print', 'click', function () {
        var event = $(this);
        toPrint(event);
    });
}


$(document).ready(function () {
    $("input[name=berlaku_dari]").val(rangeDate(0))
    $("input[name=berlaku_sampai]").val(rangeDate(1))

    load_data();
});
