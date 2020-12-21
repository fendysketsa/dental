function load_data(Year) {
    var Y = Year || ''
    var cont = $(".load-data-chart");
    cont.load(base_url + '/visits/data?y=' + Y, function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat data!';
            toastr.error(fls, 'Oops!', {
                timeOut: 2000
            })
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            charts_attribute(Y);
        }
    });
}

function load_data_pengunjung(Year, Month) {
    var Y = Year || ''
    var M = Month || ''
    var cont = $(".load-data-pengunjung");
    cont.load(base_url + '/visits/data-kunjungan', function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat data!';
            toastr.error(fls, 'Oops!', {
                timeOut: 2000
            })
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            data_attribute(Y, M);
        }
    });
}

function data_attribute(Y, M) {
    var dTable = $('#data-table-view-pengunjung').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + '/visits/json?y=' + Y + '&m=' + M,
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
                name: 'no_member'
            },
            {
                data: 'nama',
                name: 'nama'
            },
            {
                data: 'jenis_kelamin',
                name: 'jenis_kelamin'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'telepon',
                name: 'telepon'
            },
            {
                data: 'kunjungan',
                name: 'kunjungan',
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
        dTable.ajax.reload(response_load_dt);
    });
    $("input[type=search]").on('input', function (e) {
        dTable.ajax.reload(response_load_dt);
    });
}

function charts_attribute(Year) {
    var Y_ = Year || ''

    var ctx = document.getElementById('chart-kunjungan').getContext('2d');
    let data_chart = $("canvas#chart-kunjungan").data('chart')
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
            datasets: [{
                label: "Konsumen",
                backgroundColor: 'lightblue',
                borderColor: 'royalblue',
                data: data_chart,
            }]
        },

        options: {
            layout: {
                padding: 10,
            },
            legend: {
                position: 'bottom',
            },
            title: {
                display: true,
                text: 'Line Chart - Kunjugan Konsumen'
            },
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Jumlah Konsumen'
                    }
                }],
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Bulan dari Tahun'
                    }
                }]
            }
        }
    });

    if (Y_) {
        chart.update()
    }
}

function select_() {
    let Year = new Date();
    let html = ``;
    let YearBef = Year.getFullYear() - 1;

    for (var y = Year.getFullYear(); y >= YearBef; y--) {
        html += `<option value='` + y + `'>` + y + `</option>`;
    }
    return $("select#fil-y").html(html);
}

function select_month() {
    var labels = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];

    let html = ``;
    $.each(labels, function (numb, i) {
        html += `<option value='` + (numb + 1) + `'>` + i + `</option>`;
    });
    return $("select#fil-month").html(html);
}
$(document).ready(function () {
    select_();
    select_month();
    $("select#fil-y").on('change', function (e) {
        load_data($(this).val())
        load_data_pengunjung($(this).val(), $("select#fil-month").val());
    });
    $("select#fil-month").on('change', function (e) {
        load_data($("select#fil-y").val())
        load_data_pengunjung($("select#fil-y").val(), $(this).val());
    });
    load_data($("select#fil-y").val());
    load_data_pengunjung($("select#fil-y").val(), $("select#fil-month").val());
});
