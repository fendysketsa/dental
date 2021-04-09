function load_detailHisTable(e) {
    var ths = $(e);

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $.ajax({
        type: "GET",
        url: ths.data("route"),
        success: function (result) {
            $(".modal-title").html(
                '<em class="fa fa-pencil-square-o"></em> History Rekam Medik'
            );
            load_detail_dataHis("layanan", ths.data("id-member"));
        },
        error: function () {
            toastr.error("Gagal mengambil data", "Oops!", {
                timeOut: 2000,
            });
        },
    });
}

function load_detail_dataHis(table, idm) {
    var cont = $(".load-detail-history-" + table);
    cont.load(
        base_url + "/members-history/data/history?table=" + table,
        function (e, s, f) {
            if (s == "error") {
                var fls = "Gagal memuat data!";
                toastr.error(fls, "Oops!", {
                    timeOut: 2000,
                });
                $(this).html('<em class="fa fa-warning"></em> ' + fls);
            } else {
                data_attribut_history("layanan", idm);
            }
        }
    );
}

function data_attribut_history(table, member) {
    var dTable_left = $("#data-table-view-" + table).DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url:
                base_url +
                "/members-history/json/history?table=" +
                table +
                "&member=" +
                member,
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
                data: "tanggal",
                name: "tanggal",
                className: "text-center",
                render: function (data, type, row) {
                    return data ? getIndoDate(data) : "";
                },
            },
            {
                data: "no_trans",
                name: "no_trans",
                className: "td-height-img",
            },
            {
                data: "dokter",
                name: "dokter",
                className: "td-height-img",
            },
            {
                data: "ruangan",
                name: "ruangan",
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
    dTable_left.ajax.reload();
    $("select[name=data-table-view_length]").on("change", function () {
        dTable_left.ajax.reload();
    });
    $("input[type=search]").on("input", function (e) {
        dTable_left.ajax.reload();
    });

    $(".load-detail-history-layanan").delegate(
        ".detail-history",
        "click",
        function (e) {
            var event = $(this);
            load_detail_history(event);
        }
    );
}

function load_detail_history(e) {
    var ths = $(e);

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $.ajax({
        type: "GET",
        url: ths.data("route"),
        success: function (result) {
            $(".modal-title").html(
                '<em class="fa fa-pencil-square-o"></em> History Rekam Medik'
            );
            load_detail_history_data(ths.data("id-trans"));
        },
        error: function () {
            toastr.error("Gagal mengambil data", "Oops!", {
                timeOut: 2000,
            });
        },
    });
}

function load_detail_history_data(idm) {
    var cont = $(".load-detail-history-rekam-medik");

    cont.load(
        base_url + "/members-history/data/history/detail",
        function (e, s, f) {
            if (s == "error") {
                var fls = "Gagal memuat data!";
                toastr.error(fls, "Oops!", {
                    timeOut: 2000,
                });
                $(this).html('<em class="fa fa-warning"></em> ' + fls);
            } else {
                $(".mod-vol-1").on("click", function () {
                    $("#detHisMember").modal("hide");

                    $(".modal").css({
                        "overflow-x": "hidden",
                        "overflow-y": "auto",
                    });
                });

                loadDetailHis(idm);
            }
        }
    );
}

function loadDetailHis(idm) {
    var RekamMedik = $(".load-data-rekamMedik");
    var Catatan = $(".load-data-Catatan");

    RekamMedik.load(
        base_url + "/members-history/data/history/detail?load=rekam&id=" + idm,
        function (e, s, f) {
            if (s == "error") {
                var fls = "Gagal memuat data!";
                toastr.error(fls, "Oops!", {
                    timeOut: 2000,
                });
                $(this).html('<em class="fa fa-warning"></em> ' + fls);
            } else {
                var html = "";

                var datas = JSON.parse(e);

                if (datas) {
                    html += `<table class="table">`;
                    html += `<tr class="bg-danger">`;
                    html += `<th>No</th><th>Pertanyaan</th><th>Jawaban</th><th>Keterangan</th>`;
                    html += `</tr>`;

                    $.each(datas.rekam, function (e_, i_) {
                        html += `<tr>`;
                        html += `<td>` + (e_ + 1) + `</td>`;
                        html += `<td>` + i_.nama + `</td>`;
                        html += `<td>` + (!i_.name ? "-" : i_.name) + `</td>`;
                        html +=
                            `<td>` +
                            (!i_.more_keterangan ? "-" : i_.more_keterangan) +
                            `</td>`;
                        html += `</tr>`;
                    });

                    html += `</table>`;
                }

                $(".load-data-rekamMedik").html(html);
            }
        }
    );

    Catatan.load(
        base_url +
            "/members-history/data/history/detail?load=catatan&id=" +
            idm,
        function (e, s, f) {
            if (s == "error") {
                var fls = "Gagal memuat data!";
                toastr.error(fls, "Oops!", {
                    timeOut: 2000,
                });
                $(this).html('<em class="fa fa-warning"></em> ' + fls);
            } else {
                var html = "";

                var datas = JSON.parse(e);

                if (datas) {
                    html += `<table class="table">`;
                    html += `<tr class="bg-danger">`;
                    html += `<th>No</th><th>Gigi</th><th>Catatan</th><th>Gambar</th>`;
                    html += `</tr>`;

                    $.each(datas.catatan, function (e_, i_) {
                        html += `<tr>`;
                        html += `<td>` + (e_ + 1) + `</td>`;
                        html += `<td>` + i_.gigi + `</td>`;
                        html +=
                            `<td>` +
                            (!i_.ringkasan ? "-" : i_.ringkasan) +
                            `</td>`;
                        html +=
                            `<td><img width='100' height='100' src="` +
                            getImgGigiHis(i_.foto) +
                            `"></td>`;
                        html += `</tr>`;
                    });

                    html += `</table>`;
                }

                $(".load-data-Catatan").html(html);
            }
        }
    );
}

function getImgGigiHis(data) {
    var img = !data
        ? "/images/noimage.jpg"
        : "/storage/master-data/upload/gigi/pasien/" + data;

    return base_url + img;
}
