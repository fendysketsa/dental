function load_data(table) {
    var cont = $(".load-data-" + table);
    cont.load(base_url + '/sales-prod-serv/data?table=' + table, function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat data!';
            toastr.error(fls, 'Oops!', {
                timeOut: 2000
            })
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            data_attribut(table);
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

function data_attribut(table, start, ends) {
    if (table == 'produk') {
        var dr = !start && !ends ? '' : '&start=' + start + '&ends=' + ends;
        var dTableP = $('#data-table-view-' + table).DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: base_url + "/sales-prod-serv/json?table=" + table + dr,
                type: 'GET',
            },
            "fnDrawCallback": function () {
                var api = this.api()
                var json = api.ajax.json();
                totalProduk(json);
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'urutan-produk'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'terjual',
                    name: 'terjual',
                    className: 'trjl-prdk',
                    render: function (data, type, row) {
                        return data ? convertRupiah(data, "Rp. ") : '0'
                    }
                },
                {
                    data: 'pendapatan',
                    name: 'pendapatan',
                    className: 'pdptn-prdk',
                    render: function (data, type, row) {
                        return data ? convertRupiah(data, "Rp. ") : '0'
                    }
                },
                {
                    data: 'stok',
                    name: 'stok',
                    render: function (data, type, row) {
                        return data ? convertRupiah(data, "Rp. ") : '0'
                    }
                },
                {
                    data: 'harga',
                    name: 'harga',
                    render: function (data, type, row) {
                        return data ? convertRupiah(data, "Rp. ") : '0'
                    }
                },
            ],
            order: [
                [0, 'desc']
            ]
        });

        $("select[name=data-table-view_length]").on('change', function () {
            dTableP.ajax.reload();
        });
        $("input[type=search]").on('input', function (e) {
            dTableP.ajax.reload();
        });
    } else if (table == 'paket') {
        var dr = !start && !ends ? '' : '&start=' + start + '&ends=' + ends;
        var dTablePk = $('#data-table-view-' + table).DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: base_url + "/sales-prod-serv/json?table=" + table + dr,
                type: 'GET',
            },
            "fnDrawCallback": function () {
                var api = this.api()
                var json = api.ajax.json();
                totalPaket(json);
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'urutan-paket'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'terjual',
                    name: 'terjual',
                    className: 'trjl-pkt',
                    render: function (data, type, row) {
                        return data ? convertRupiah(data, "Rp. ") : '0'
                    }
                },
                {
                    data: 'pendapatan',
                    name: 'pendapatan',
                    className: 'pdptn-pkt',
                    render: function (data, type, row) {
                        return data ? convertRupiah(data, "Rp. ") : '0'
                    }
                },
                {
                    data: 'harga',
                    name: 'harga',
                    render: function (data, type, row) {
                        return data ? convertRupiah(data, "Rp. ") : '0'
                    }
                },
            ],
            order: [
                [0, 'desc']
            ]
        });

        $("select[name=data-table-view_length]").on('change', function () {
            dTablePk.ajax.reload();
        });
        $("input[type=search]").on('input', function (e) {
            dTablePk.ajax.reload();
        });
    } else {
        var dr = !start && !ends ? '' : '&start=' + start + '&ends=' + ends;
        var dTable = $('#data-table-view-' + table).DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: base_url + "/sales-prod-serv/json?table=" + table + dr,
                type: 'GET',
            },
            "fnDrawCallback": function () {
                var api = this.api()
                var json = api.ajax.json();
                totalLayanan(json);
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'urutan-layanan'
                },
                {
                    data: 'kategori',
                    name: 'kategori',
                    searchable: false,
                },
                {
                    data: 'nama',
                    name: 'nama',
                    searchable: true
                },
                {
                    data: 'terjual',
                    name: 'terjual',
                    searchable: false,
                    className: 'trjl-lynn',
                    render: function (data, type, row) {
                        return data ? convertRupiah(data, "Rp. ") : '0'
                    }
                },
                {
                    data: 'pendapatan',
                    name: 'pendapatan',
                    searchable: false,
                    className: 'pdptn-lynn',
                    render: function (data, type, row) {
                        return data ? convertRupiah(data, "Rp. ") : '0'
                    }
                },
                {
                    data: 'harga',
                    name: 'harga',
                    searchable: false,
                    render: function (data, type, row) {
                        return data ? convertRupiah(data, "Rp. ") : '0'
                    }
                },
            ],
            order: [
                [0, 'desc']
            ]
        });

        $("select[name=data-table-view_length]").on('change', function () {
            dTable.ajax.reload();
        });
        $("input[type=search]").on('input', function (e) {
            dTable.ajax.reload();
        });
    }

    $('.add-on-daterpicker').daterangepicker({
        alwaysShowCalendars: true,
        showCustomRangeLabel: true,
        startDate: !$("input[name=id]").val() ? moment().add(0, 'd').toDate() : $("input[name=berlaku_dari]").val(),
        endDate: !$("input[name=id]").val() ? moment().add(0, 'd').toDate() : $("input[name=berlaku_sampai]").val(),
        singleDatePicker: false,
        showDropdowns: false,
        autoUpdateInput: true,
        locale: {
            cancelLabel: 'Clear',
            format: 'DD-MM-YYYY'
        },
    });

    first_fill_field_daterange(moment().add(0, 'd').format("YYYY-MM-DD"), moment().add(1, 'd').format("YYYY-MM-DD"));

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

function totalLayanan(json) {
    $(".total-layanan, .total-pendapatan-layanan").html('<em class="fa fa-spin fa-spinner"></em>')
    setTimeout(() => {
        $(".total-layanan").html(convertRupiah(json.total_item));
        $(".total-pendapatan-layanan").html(convertRupiah(json.total_pendapatan));
    }, 1000);
}

function totalProduk(json) {
    $(".total-produk, .total-pendapatan-produk").html('<em class="fa fa-spin fa-spinner"></em>')
    setTimeout(() => {
        $(".total-produk").html(convertRupiah(json.total_item));
        $(".total-pendapatan-produk").html(convertRupiah(json.total_pendapatan));
    }, 1000);
}

function totalPaket(json) {
    $(".total-paket, .total-pendapatan-paket").html('<em class="fa fa-spin fa-spinner"></em>')
    setTimeout(() => {
        $(".total-paket").html(convertRupiah(json.total_item));
        $(".total-pendapatan-paket").html(convertRupiah(json.total_pendapatan));
    }, 1000);
}

function first_fill_field_daterange(start, end) {
    $("input[name=berlaku_dari]").val(rangeDate(0))
    $("input[name=berlaku_sampai]").val(rangeDate(1))

    $('#data-table-view-layanan').DataTable().ajax.url(base_url +
        "/sales-prod-serv/json?table=layanan&starts=" + start +
        "&ends=" + end).load();

    $('#data-table-view-paket').DataTable().ajax.url(base_url +
        "/sales-prod-serv/json?table=paket&starts=" + start +
        "&ends=" + end).load();

    $('#data-table-view-produk').DataTable().ajax.url(base_url +
        "/sales-prod-serv/json?table=produk&starts=" + start +
        "&ends=" + end).load();
}

function fill_field_daterange(picker) {
    $("input[name='berlaku_dari']").val(picker.startDate.format('DD-MM-YYYY'));
    $("input[name='berlaku_sampai']").val(picker.endDate.format('DD-MM-YYYY'));

    $('#data-table-view-layanan').DataTable().ajax.url(base_url +
        "/sales-prod-serv/json?table=layanan&starts=" + picker.startDate.format('YYYY-MM-DD') +
        "&ends=" + picker.endDate.format('YYYY-MM-DD')).load();

    $('#data-table-view-paket').DataTable().ajax.url(base_url +
        "/sales-prod-serv/json?table=paket&starts=" + picker.startDate.format('YYYY-MM-DD') +
        "&ends=" + picker.endDate.format('YYYY-MM-DD')).load();

    $('#data-table-view-produk').DataTable().ajax.url(base_url +
        "/sales-prod-serv/json?table=produk&starts=" + picker.startDate.format('YYYY-MM-DD') +
        "&ends=" + picker.endDate.format('YYYY-MM-DD')).load();
}

function remove_field_daterange(start, end) {

    $("input[name='berlaku_dari']").val(start);
    $("input[name='berlaku_sampai']").val(end);

    $('#data-table-view-layanan').DataTable().ajax.url(base_url +
        "/sales-prod-serv/json?table=layanan").load();

    $('#data-table-view-paket').DataTable().ajax.url(base_url +
        "/sales-prod-serv/json?table=paket").load();

    $('#data-table-view-produk').DataTable().ajax.url(base_url +
        "/sales-prod-serv/json?table=produk").load();
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

$(document).ready(function () {

    load_data('layanan');
    load_data('paket');
    load_data('produk');
});
