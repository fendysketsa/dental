function loadChart() {

    'use strict';

    var configPerforma = {
        type: 'line',
        data: {
            labels: [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ],
            datasets: $("#revenue-chart").data('performa-pie'),
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Performa Semua Cabang'
            },
        }
    };

    var configServices = {
        labels: $("#services-chart").data('services-label'),
        datasets: $("#services-chart").data('services-set'),
    };

    var chartOptionsServices = {
        responsive: true,
        legend: {
            position: "top"
        },
        title: {
            display: true,
            text: "Best Favorit"
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }

    //

    var configVisit = {
        type: 'pie',
        data: {
            datasets: [{
                data: $("#visit-chart").data('visit-pie'),
                backgroundColor: [
                    '#70CEE4',
                    '#F8878F',
                    '#965786',
                    '#F8C765'
                ],
                label: 'Dataset 1'
            }],
            labels: $("#visit-chart").data('cabang-pie'),
        },
        options: {
            responsive: true
        }
    };

    var configMember = {
        type: 'pie',
        data: {
            datasets: [{
                data: $("#member-chart").data('member-pie'),
                backgroundColor: [
                    window.chartColors.blue,
                    window.chartColors.yellow,
                    window.chartColors.red
                ],
                label: 'Dataset 1'
            }],
            labels: [
                'Laki-laki',
                'Perempuan',
                'Tidak disebutkan'
            ]
        },
        options: {
            responsive: true
        }
    };

    loadChartPie(configPerforma, 'revenue-chart');
    loadChartPie(configServices, 'services-chart', '', chartOptionsServices);
    loadChartPie(configVisit, 'visit-chart');
    loadChartPie(configMember, 'member-chart');

    $('#fil-y').on('change', function (e) {
        $(".box-body").load(base_url + '/home/data?tahun=' + $(this).val(), function (f, g, h) {
            if (g == 'success') {
                loadChartPie(configPerforma, 'revenue-chart', 'reload');
                loadChartPie(configServices, 'services-chart', 'reload', chartOptionsServices);
                loadChartPie(configVisit, 'visit-chart', 'reload');
                loadChartPie(configMember, 'member-chart', 'reload');
            }
        });
    });
}

function ReloadDataChart(chart, data, datasetIndex) {
    chart.data.datasets[datasetIndex].data = data;
    chart.update();
}

function loadChartPie(loadConfig, id, load, load2) {

    if (id == 'revenue-chart') {
        var ctxPerforma = document.getElementById(id).getContext('2d');
        window.myLine = new Chart(ctxPerforma, loadConfig);

        if (load) {
            var dataPerforma = $("#" + id).data('performa-pie');
            $.each(dataPerforma, function (i) {
                ReloadDataChart(window.myLine, dataPerforma[i].data, i)
            })
        }
    }

    if (id == 'services-chart') {
        var ctxService = document.getElementById(id).getContext("2d");
        window.myBarServices = new Chart(ctxService, {
            type: "bar",
            data: loadConfig,
            options: load2
        });

        if (load) {
            var dataServ = $("#" + id).data('services-set');
            $.each(dataServ, function (i) {
                ReloadDataChart(window.myBarServices, dataServ[i].data, i)
            })
        }
    }

    if (id == 'visit-chart') {
        var ctxVisit = document.getElementById(id).getContext('2d');
        window.myPieVisit = new Chart(ctxVisit, loadConfig);

        if (load) {
            ReloadDataChart(window.myPieVisit, $("#" + id).data('visit-pie'), 0)
        }
    }

    if (id == 'member-chart') {
        var ctxMember = document.getElementById(id).getContext('2d');
        window.myPieMember = new Chart(ctxMember, loadConfig);

        if (load) {
            ReloadDataChart(window.myPieMember, $("#" + id).data('member-pie'), 0)
        }
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

function loadData() {
    $(".box-body").load(base_url + '/home/data', function (f, g, h) {
        if (g == 'success') {
            loadChart();
        }
    });
}

$(document).ready(function () {
    select_();
    loadData();
});
