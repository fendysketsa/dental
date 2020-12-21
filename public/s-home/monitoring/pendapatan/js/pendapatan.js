function load_data(data_) {
    var cont = $(".load-data-" + data_);
    cont.load(base_url + '/incomes/data?load=' + data_, function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat data!';
            toastr.error(fls, 'Oops!', {
                timeOut: 2000
            })
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            switch (data_) {
                case 'pemasukan':
                    data_attribute_pendapatan('pemasukan')
                    break;
                case 'pengeluaran':
                    data_attribute_pendapatan('pengeluaran')
                    loadPengeluaranTotal();
                    break;
                case 'modal':
                    data_attribute_pendapatan('modal')
                    break;
            }
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

    var minus = bilangan.toString().split('-').length;

    var number_string = (minus > 1 ? bilangan.toString().split('-').join('') : bilangan.toString()),
        sisa = number_string.length % 3,
        rupiah = number_string.substr(0, sisa),
        ribuan = number_string.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    return bilangan_ ? (minus > 1 ? '-' : '') + rupiah : 0;
}

function resumeIncomes() {
    var dataResInc = $("div.inner");
    $.each(dataResInc, function (i) {
        var OldRupiah = dataResInc.find('h3 em')[i].innerText;
        dataResInc.find('h3 em')[i].innerText = convertRupiah(OldRupiah)
    })
}

function data_attribute_pendapatan(load) {
    var ElemtStart = rangeDate(0, 'back');
    var ElemtEnd = rangeDate(1, 'back');

    var dateranges = (ElemtStart ? '&starts=' + ElemtStart : '') +
        (ElemtEnd ? '&ends=' + ElemtEnd : '');

    switch (load) {
        case 'pemasukan':
            var groupColumn = 1;
            var dTablePemasukan = $('#data-table-view-' + load).DataTable({
                dom: "Bfrtip",
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: base_url + "/incomes/json?load=" + load + dateranges,
                    type: 'GET',
                },
                "fnDrawCallback": function () {
                    var api = this.api()
                    var json = api.ajax.json();
                    LoadTotalBeayaGroup(json)
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: "td-height-img urut-bea"
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                        className: "td-height-img",
                        render: getIndoDate,
                    },
                    {
                        data: 'no_transaksi',
                        name: 'no_transaksi',
                        className: "td-height-img",
                    },
                    {
                        data: 'total_biaya',
                        name: 'total_biaya',
                        className: "td-height-img text-center total-bea",
                        render: convertRupiah,
                        orderable: false
                    }
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
                                '<tr class="group pemasukan text-center" style="color:#fff !important; background-color:#7d8084 !important;"><td colspan="4"><strong class="pull-left"><em class="fa fa-calendar"></em> ' + getIndoDate(group) + '</strong><strong class="pull-right">Total: <span class="' + group + '-bea-totalan text-bold">loading...</span></strong></td></tr>'
                            );

                            last = group;
                            TotalBeayaGroup(group)
                            pencarianPemasukan(dTablePemasukan, group)
                        }
                        $(rows).eq(i).find('td.total-bea').addClass(group);
                    });
                }
            });

            $("select[name=data-table-view_length]").on('change', function () {
                dTablePemasukan.ajax.reload();
            });
            data_attribut();
            break;
        case 'pengeluaran':
            var groupColumn = 1;
            var dTablePengeluaran = $('#data-table-view-' + load).DataTable({
                dom: "Bfrtip",
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: base_url + "/incomes/json?load=" + load + '&data=pengeluaran' + dateranges,
                    type: 'GET',
                },
                "fnDrawCallback": function () {
                    var api = this.api()
                    var json = api.ajax.json();
                    LoadTotalPengeluaranPembelian(json)
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: "td-height-img urut-bea"
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                        className: "td-height-img",
                        render: getIndoDate,
                    },
                    {
                        data: 'no_transaksi',
                        name: 'no_transaksi',
                        className: "td-height-img",
                    },
                    {
                        data: 'operator',
                        name: 'operator',
                        className: "td-height-img",
                    },
                    {
                        data: 'total_biaya',
                        name: 'total_biaya',
                        className: "td-height-img text-center total-bea-pengeluaran",
                        render: convertRupiah,
                        orderable: false
                    }
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
                                '<tr class="group pengeluaran text-center" style="color:#fff !important; background-color:#7d8084 !important;"><td colspan="5"><strong class="pull-left">' + getIndoDate(group) + '</strong><strong class="pull-right">Total: <span class="' + group + '-bea-totalan-pengeluaran text-bold">loading...</span></strong></td></tr>'
                            );

                            last = group;
                            TotalBeayaPengeluaran(group)
                            pencarianPengeluaran(dTablePengeluaran, group)
                        }
                        $(rows).eq(i).find('td.total-bea-pengeluaran').addClass(group);
                    });
                }
            });

            $("select[name=data-table-view_length]").on('change', function () {
                dTablePengeluaran.ajax.reload();
            });

            var dTablePembelian = $('#data-table-view-pembelian').DataTable({
                dom: "Bfrtip",
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: base_url + "/incomes/json?load=" + load + '&data=pembelian' + dateranges,
                    type: 'GET',
                },
                "fnDrawCallback": function () {
                    var api = this.api()
                    var json = api.ajax.json();
                    LoadTotalPengeluaranPembelian(json)
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: "td-height-img urut-bea"
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                        className: "td-height-img",
                        render: getIndoDate,
                    },
                    {
                        data: 'no_transaksi',
                        name: 'no_transaksi',
                        className: "td-height-img",
                    },
                    {
                        data: 'operator',
                        name: 'operator',
                        className: "td-height-img",
                    },
                    {
                        data: 'supplier',
                        name: 'supplier',
                        className: "td-height-img",
                    },
                    {
                        data: 'total_biaya',
                        name: 'total_biaya',
                        className: "td-height-img text-center total-bea-pembelian",
                        render: convertRupiah,
                        orderable: false
                    }
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
                                '<tr class="group pembelian text-center" style="color:#fff !important; background-color:#7d8084 !important;"><td colspan="6"><strong class="pull-left">' + getIndoDate(group) + '</strong><strong class="pull-right">Total: <span class="' + group + '-bea-totalan-pembelian text-bold">loading...</span></strong></td></tr>'
                            );

                            last = group;
                            TotalBeayaPembelian(group)
                            pencarianPembelian(dTablePembelian, group)
                        }
                        $(rows).eq(i).find('td.total-bea-pembelian').addClass(group);
                    });
                }
            });

            $("select[name=data-table-view_length]").on('change', function () {
                dTablePembelian.ajax.reload();
            });
            break;
        case 'modal':
            var groupColumn = 1;
            var dTableModal = $('#data-table-view-' + load).DataTable({
                dom: "Bfrtip",
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: base_url + "/incomes/json?load=" + load + dateranges,
                    type: 'GET',
                },
                "fnDrawCallback": function () {
                    var api = this.api()
                    var json = api.ajax.json();
                    LoadTotalBeayaModalGroup(json)
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: "td-height-img urut-bea"
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal',
                        className: "td-height-img",
                        render: getIndoDate,
                    },
                    {
                        data: 'operator',
                        name: 'operator',
                        className: "td-height-img",
                    },
                    {
                        data: 'shift',
                        name: 'shift',
                        className: "td-height-img",
                    },
                    {
                        data: 'total_biaya',
                        name: 'total_biaya',
                        className: "td-height-img text-center total-bea-modale",
                        render: convertRupiah,
                        orderable: false
                    }
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
                                '<tr class="group modale text-center" style="color:#fff !important; background-color:#7d8084 !important;"><td colspan="5"><strong class="pull-left"><em class="fa fa-calendar"></em> ' + getIndoDate(group) + '</strong><strong class="pull-right">Total: <span class="' + group + '-bea-modale-totalan text-bold">loading...</span></strong></td></tr>'
                            );

                            last = group;
                            TotalBeayaModalGroup(group)
                            pencarianModal(dTableModal, group)
                        }
                        $(rows).eq(i).find('td.total-bea-modale').addClass(group);
                    });
                }
            });

            $("select[name=data-table-view_length]").on('change', function () {
                dTableModal.ajax.reload();
            });
            break;
    }
    setTimeout(function () {
        loadTotalPendapatan();
    }, 800);
}

// start pemasukan

function pencarianPemasukan(dTablePemasukan, grp) {
    $("input[type=search]").on('input', function () {
        dTablePemasukan.ajax.reload();
        TotalBeayaGroup(grp)
    });
}

function TotalBeayaGroup(grp) {
    setTimeout(() => {
        var komisi = 0;
        for (var i = 0; i < $("td.total-bea." + grp).length; i++) {
            komisi += parseInt($("td.total-bea." + grp)[i].innerHTML.split('.').join(''));
        }
        $("." + grp + "-bea-totalan").text(convertRupiah(komisi));
    }, 0);
}

function LoadTotalBeayaGroup(json) {
    if (json) {
        $(".t-pemasukan").html('<em class="fa fa-spin fa-spinner"></em>');
    }
    setTimeout(() => {
        $(".t-pemasukan").html(convertRupiah(json.total_pemasukan));
    }, 500);
}

// end pemasukan
// pengeluaran
function pencarianPengeluaran(dTablePengeluaran, grp) {
    $("input[type=search]").on('input', function () {
        dTablePengeluaran.ajax.reload();
        TotalBeayaPengeluaran(grp)
    });
}

function TotalBeayaPengeluaran(grp) {
    setTimeout(() => {
        var komisi = 0;
        for (var i = 0; i < $("td.total-bea-pengeluaran." + grp).length; i++) {
            komisi += parseInt($("td.total-bea-pengeluaran." + grp)[i].innerHTML.split('.').join(''));
        }
        $("." + grp + "-bea-totalan-pengeluaran").text(convertRupiah(komisi));
    }, 0);
}

function pencarianPembelian(dTablePembelian, grp) {
    $("input[type=search]").on('input', function () {
        dTablePembelian.ajax.reload();
        TotalBeayaPembelian(grp)
    });
}

function TotalBeayaPembelian(grp) {
    setTimeout(() => {
        var komisi = 0;
        for (var i = 0; i < $("td.total-bea-pembelian." + grp).length; i++) {
            komisi += parseInt($("td.total-bea-pembelian." + grp)[i].innerHTML.split('.').join(''));
        }
        $("." + grp + "-bea-totalan-pembelian").text(convertRupiah(komisi));
    }, 0);
}

function LoadTotalPengeluaranPembelian(ttalBeaya) {
    var PengPem = 0
    if (ttalBeaya) {
        $(".t-pengeluaran").html('<em class="fa fa-spin fa-spinner"></em>');
    }
    $.each(ttalBeaya, function (e, f) {
        if (e == 'total_pengeluaran') {
            $(".get-total-pengeluaran").attr('data-pengeluaran', f)
            PengPem += parseInt(f)
        }
        if (e == 'total_pembelian') {
            $(".get-total-pengeluaran").attr('data-pembelian', f)
            PengPem += parseInt(f)
        }
    });

    setTimeout(() => {
        $(".t-pengeluaran").html(convertRupiah(PengPem));
    }, 1500);
}

// end pengeluaran
// start modal

function pencarianModal(dTableModal, grp) {
    $("input[type=search]").on('input', function () {
        dTableModal.ajax.reload();
        TotalBeayaModalGroup(grp)
    });
}

function TotalBeayaModalGroup(grp) {
    setTimeout(() => {
        var komisi = 0;
        for (var i = 0; i < $("td.total-bea-modale." + grp).length; i++) {
            komisi += parseInt($("td.total-bea-modale." + grp)[i].innerHTML.split('.').join(''));
        }
        $("." + grp + "-bea-modale-totalan").text(convertRupiah(komisi));
    }, 0);
}

function LoadTotalBeayaModalGroup(json) {
    if (json) {
        $(".t-modale").html('<em class="fa fa-spin fa-spinner"></em>');
    }
    setTimeout(() => {
        $(".t-modale").html(convertRupiah(json.total_modale));
    }, 1000);
}

// end modal

function data_attribut() {
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
}

function loadPengeluaranTotal() {
    setTimeout(() => {
        var t_pengeluaran = $(".get-total-pengeluaran").attr('data-pengeluaran');
        var t_pembelian = $(".get-total-pengeluaran").attr('data-pembelian');
        var total = parseInt(t_pengeluaran) + parseInt(t_pembelian);
        $('.t-pengeluaran').html(convertRupiah(total))
    }, 500);
}

function fill_field_daterange(picker) {
    $("input[name='berlaku_dari']").val(picker.startDate.format('DD-MM-YYYY'));
    $("input[name='berlaku_sampai']").val(picker.endDate.format('DD-MM-YYYY'));

    $('#data-table-view-pemasukan').DataTable().ajax.url(base_url +
        "/incomes/json?load=pemasukan&starts=" + picker.startDate.format('YYYY-MM-DD') +
        "&ends=" + picker.endDate.format('YYYY-MM-DD')).load();

    $('#data-table-view-pengeluaran').DataTable().ajax.url(base_url +
        "/incomes/json?load=pengeluaran&data=pengeluaran&starts=" + picker.startDate.format('YYYY-MM-DD') +
        "&ends=" + picker.endDate.format('YYYY-MM-DD')).load();

    $('#data-table-view-pembelian').DataTable().ajax.url(base_url +
        "/incomes/json?load=pengeluaran&data=pembelian&starts=" + picker.startDate.format('YYYY-MM-DD') +
        "&ends=" + picker.endDate.format('YYYY-MM-DD')).load();

    $('#data-table-view-modal').DataTable().ajax.url(base_url +
        "/incomes/json?load=modal&starts=" + picker.startDate.format('YYYY-MM-DD') +
        "&ends=" + picker.endDate.format('YYYY-MM-DD')).load();

    setTimeout(() => {
        loadPengeluaranTotal()
        setTimeout(function () {
            loadTotalPendapatan()
        }, 1500);
    }, 900);

}

function remove_field_daterange() {
    $("input[name='berlaku_dari']").val('');
    $("input[name='berlaku_sampai']").val('');

    $('#data-table-view-pemasukan').DataTable().ajax.url(base_url +
        "/incomes/json?load=pemasukan").load();

    $('#data-table-view-pengeluaran').DataTable().ajax.url(base_url +
        "/incomes/json?load=pengeluaran&data=pengeluaran").load();

    $('#data-table-view-pembelian').DataTable().ajax.url(base_url +
        "/incomes/json?load=pengeluaran&data=pembelian").load();

    $('#data-table-view-modal').DataTable().ajax.url(base_url +
        "/incomes/json?load=modal").load();

    setTimeout(() => {
        loadPengeluaranTotal()
        setTimeout(function () {
            loadTotalPendapatan()
        }, 1500);
    }, 900);
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

function loadTotalPendapatan() {
    $(".t-pendapatan").html('<em class="fa fa-spin fa-spinner"></em>');
    var pemasukan = $('.t-pemasukan').text().split('.').join('');
    var pengeluaran = $('.t-pengeluaran').text().split('.').join('');
    var modal = $('.t-modale').text().split('.').join('');

    var pendapatan = parseInt(pemasukan - pengeluaran - modal);
    setTimeout(function () {
        $(".t-pendapatan").html(convertRupiah(pendapatan));
    }, 500);
}

$(document).ready(function () {
    $("input[name=berlaku_dari]").val(rangeDate(0))
    $("input[name=berlaku_sampai]").val(rangeDate(1))

    resumeIncomes();
    load_data('pemasukan');
    load_data('pengeluaran');
    load_data('modal');
});
