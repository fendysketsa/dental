function load_data() {
    var cont = $(".load-data");
    cont.load(base_url + "/cashiers/data", function (e, s, f) {
        if (s == "error") {
            var fls = "Gagal memuat data!";
            toastr.error(fls, "Oops!", {
                timeOut: 2000,
            });
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            data_attribut();
        }
    });
}

function getMemberInfo(id) {
    var token = $("meta[name=csrf-token]").attr("content");
    $.ajax({
        url: base_url + "/cashiers/member/explore",
        method: "POST",
        data: {
            id: id,
            _token: token,
        },
        dataType: "json",
        success: function (data) {
            $(".load-form-left-info-member").html(contInfoMember(data[0]));
        },
    });
}

function imgError(image) {
    image.onerror = "";
    image.src = "/images/brokenimage.jpg";
    image.alt = "Images corrupt!";
    image.title = "Images corrupt!";
    return true;
}

function getImg(data) {
    var img = !data
        ? "/images/noimage.jpg"
        : "/storage/master-data/member/uploads/" + data;
    return (
        '<img onerror="imgError(this);" style="border-radius:50%;" width="100" height="100" src="' +
        base_url +
        img +
        '">'
    );
}

function contInfoMember(idN) {
    var html =
        `<ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                ` +
        getImg(idN.foto) +
        `
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item"><em class="fa fa-fa fa-bookmark-o"></em>
                        <em class="font-weight-bold pull-right" style="font-weight:bolder;">` +
        idN.no_member +
        `</em></li>
                    <li class="list-group-item"><em class="fa fa-tag"></em>
                        <em class="font-weight-bold pull-right" style="font-weight:bolder;">` +
        idN.nama +
        `</em></li>
                    <li class="list-group-item"><em class="fa fa-child"></em>
                        <em class="font-weight-bold pull-right" style="font-weight:bolder;">` +
        idN.jenis_kelamin +
        `</em></li>
                    <li class="list-group-item"><em class="fa fa-map-signs"></em>
                        <em class="font-weight-bold pull-right" style="font-weight:bolder;">` +
        (idN.alamat ? idN.alamat : "-") +
        `</em></li>
                    <li class="list-group-item"><em class="fa fa-phone"></em>
                        <em class="font-weight-bold pull-right" style="font-weight:bolder;">` +
        idN.telepon +
        `</em></li>
                    <li class="list-group-item"><em class="fa fa-envelope"></em>
                        <em class="font-weight-bold pull-right" style="font-weight:bolder;">` +
        idN.email +
        `</em></li>`;
    html += `</ul>`;
    return html;
}

function contInfoTrans(idN) {
    var tglReservasi = idN.data("reservasi");
    var html =
        `<ul class="list-group list-group-flush">
                    <li class="list-group-item"><i class="fa fa-check"></i> No. Transaksi: <em class="font-weight-bold pull-right" style="font-weight:bolder;">` +
        idN.data("transaksi-no") +
        `</em></li>
                    <li class="list-group-item"><i class="fa fa-check"></i> Waktu Pendaftaran:
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fa fa-calendar"></i>
                                Tanggal: <em class="font-weight-bold pull-right" style="font-weight:bolder;">` +
        getIndoDate(idN.data("tanggal-transaksi")) +
        `</em>
                                    </li>
                            <li class="list-group-item"><i class="fa fa-clock-o"></i>
                                Jam: <em class="font-weight-bold pull-right" style="font-weight:bolder;">` +
        idN.data("jam-transaksi") +
        `</em>
                                    </li>
                        </ul>
                    </li>`;
    if (tglReservasi) {
        html +=
            `<li class="list-group-item"><i class="fa fa-check"></i> Waktu Reservasi:
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fa fa-calendar"></i>
                                Tanggal: <em class="font-weight-bold pull-right" style="font-weight:bolder;">` +
            getIndoDate(idN.data("tanggal-reservasi")) +
            `</em>
                                    </li>
                            <li class="list-group-item"><i class="fa fa-clock-o"></i>
                                Jam: <em class="font-weight-bold pull-right" style="font-weight:bolder;">` +
            idN.data("jam-reservasi") +
            `</em>
                                    </li>
                        </ul>
                </li>`;
    }
    html += `</ul>`;
    return html;
}

function load_formPembayaran() {
    $("tbody").delegate(".bayar", "click", function () {
        var event = $(this);

        $(".load-form-pembayaran").html("");

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            type: "PUT",
            url: event.data("route"),
            success: function (result) {
                $(".load-form-pembayaran").html(result);
                form_attribut_modal(event);

                $(".btn-box-tool").find("em.fa-minus").html("");
                $("button[type=submit]").attr("form", "formKasir");

                var inputId = $("input[name=id]");
                setTimeout(function () {
                    $(".load-form-left-info-transaksi").find("form").remove();
                    $(".load-form-left-info-transaksi").html(
                        contInfoTrans(inputId)
                    );
                    $(".load-form-left-info-member").find("form").remove();
                    getMemberInfo(inputId.data("member"));
                }, 1500);

                setTimeout(() => {
                    $(".load-row-layanan").html("");
                    $(".load-row-layanan-tambahan").html("");
                    $(".load-row-paket").html("");

                    for (
                        var rL = 1;
                        rL <= inputId.data("layanan").length;
                        rL++
                    ) {
                        $(".load-row-layanan").append(load_row_layanan(rL));
                    }

                    for (
                        var rL = 1;
                        rL <= inputId.data("layanan-tambahan").length;
                        rL++
                    ) {
                        $(".load-row-layanan-tambahan").append(
                            load_row_layanan_tambahan(rL)
                        );
                    }

                    for (var rP = 1; rP <= inputId.data("paket").length; rP++) {
                        $(".load-row-paket").append(load_row_paket(rP));
                    }

                    for (
                        var rPd = 1;
                        rPd <= inputId.data("produk").length;
                        rPd++
                    ) {
                        $(".data-product").append(load_row_produk(rPd));
                    }

                    $(".data-product").append(dt_row);
                    $(".loading-data-produk").html("");

                    setTimeout(() => {
                        loadTotal();
                    }, 500);

                    $(".load-form-pembayaran").append(
                        '<div class="price-layanan-unique"></div>'
                    );
                }, 2000);
            },
            error: function () {
                toastr.error("Gagal mengambil data", "Oops!", {
                    timeOut: 2000,
                });
            },
        });
    });
}

function form_attribut_modal(ev) {
    $(".modal-title").html(
        '<em class="fa fa-pencil-square-o"></em> Form Pembayaran'
    );
    load_formLeft();
    load_formRightOrder();
    load_formRight(ev);
}

function getIndoDate(date) {
    var _hari = ["Ming", "Sen", "Sel", "Rab", "Kam", "Jum", "Sabt"];
    var _bulan = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "Mei",
        "Jun",
        "Jul",
        "Agust",
        "Sept",
        "Okt",
        "Nov",
        "Des",
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

    return hari + ", " + tanggal + " " + bulan + " " + tahun;
}

function toPrint(event, onsave) {
    var target = base_url + "/monitoring/order/det";
    var idCetak = !onsave ? event.data("id-cetak") : event.data("id-cetak-on");
    $.ajax({
        url: target + "/" + idCetak,
        type: "GET",
        success: function (data) {
            rePrintManual(idCetak);
            // rePrint(event, data, onsave);
        },
    });
}

function rePrintManual(id) {
    var target = base_url + "/monitoring/print";

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $.ajax({
        type: "POST",
        url: target,
        data: {
            id: id,
        },
        success: function (result) {
            var myWidth = 650;
            var myHeight = 750;
            var left = (screen.width - myWidth) / 2;
            var top = (screen.height - myHeight) / 4;

            w = window.open(
                "",
                "_blank",
                "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=" +
                    myWidth +
                    ", height=" +
                    myHeight +
                    ", top=" +
                    top +
                    ", left=" +
                    left
            );
            w.document.open();
            w.document.write(result);
            w.document.close();
            // w.window.print();
        },
        error: function () {
            toastr.error("Gagal mengambil data", "Oops!", {
                timeOut: 2000,
            });
        },
    });
}

function convertRupiah(bilangan_) {
    var bilangan = bilangan_;

    var number_string = bilangan.toString(),
        sisa = number_string.length % 3,
        rupiah = number_string.substr(0, sisa),
        ribuan = number_string.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
        separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }
    return !bilangan ? 0 : rupiah;
}

function currentDate() {
    var today = new Date();
    var date =
        today.getFullYear() +
        "-" +
        (today.getMonth() + 1) +
        "-" +
        today.getDate();
    var time =
        today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    return date + " " + time;
}

function rePrint(events, dataTrans, onsave) {
    var jumPaket = 0;
    var onSave = onsave;
    localStorage.setItem("error-print", "");
    var kasire = $(".user-panel").find("p").text().trim() || "";
    var operatorCbang = $(".user-panel").find("p").data("branch-id") || "";
    var operatorCbangCode =
        $(".user-panel").find("p").data("branch-code") || "";
    var printer = new Recta("7963354012", "1811");
    printer
        .open()
        .then(function () {
            printer.align("center").text("Medina Dental - Make Up");

            if (operatorCbang == 2 || !operatorCbang) {
                printer
                    .align("center")
                    .text("Jl. Gejayan No. 11")
                    .text("Yogyakarta")
                    .text("(0274)-589946");
            }

            if (operatorCbang == 3) {
                printer
                    .align("center")
                    .text("Jl. Jeruk Timur II No. 7")
                    .text("Magelang Utara")
                    .text("081 6677 225");
            }

            if (operatorCbang == 4) {
                printer
                    .align("center")
                    .text("Jl. Tambakbayan 9 No. 1")
                    .text("Yogyakarta");
            }

            if (operatorCbang == 5) {
                printer
                    .align("center")
                    .text("Jl. Jambon No.95, Trihanggo")
                    .text("Yogyakarta")
                    .text("(0274) -589954");
            }

            printer
                .align("center")
                .text("--------------------------------")
                .text("PRINT")
                .feed(1)
                .align("left")
                .text(
                    "No. Trans" +
                        sprintf("%3s", ": ") +
                        sprintf(
                            "%20s",
                            "MD-" +
                                operatorCbangCode +
                                "-" +
                                dataTrans.data.no_transaksi
                        )
                )
                .align("left")
                .text(
                    "Tanggal" +
                        sprintf("%5s", ": ") +
                        sprintf("%20s", getIndoDate(dataTrans.data.created_at))
                )
                .align("left")
                .text(
                    "Cetak" +
                        sprintf("%7s", ": ") +
                        sprintf("%20s", currentDate())
                )
                .align("left")
                .text(
                    "No. Member" +
                        sprintf("%2s", ": ") +
                        sprintf("%20s", dataTrans.data.no_member)
                )
                .align("left")
                .text(
                    "Member" +
                        sprintf("%6s", ": ") +
                        sprintf("%20s", dataTrans.data.member)
                )
                .align("left")
                .text("Kasir" + sprintf("%7s", ": ") + sprintf("%20s", kasire))
                .feed(1)
                .align("center")
                .text("--------------------------------");

            if (dataTrans.layanan.length > 0) {
                for (var lyn = 0; lyn < dataTrans.layanan.length; lyn++) {
                    printer
                        .align("left")
                        .text(dataTrans.layanan[lyn].kategori.substring(0, 25))
                        .align("left")
                        .text(
                            sprintf(
                                "%s",
                                " 1 X " +
                                    dataTrans.layanan[lyn].layanan.substring(
                                        0,
                                        13
                                    )
                            ) +
                                sprintf(
                                    "%" +
                                        (dataTrans.layanan[
                                            lyn
                                        ].layanan.substring(0, 13).length == 13
                                            ? 14
                                            : dataTrans.layanan[
                                                  lyn
                                              ].layanan.substring(0, 13)
                                                  .length == 12
                                            ? 15
                                            : dataTrans.layanan[
                                                  lyn
                                              ].layanan.substring(0, 13)
                                                  .length == 11
                                            ? 16
                                            : dataTrans.layanan[
                                                  lyn
                                              ].layanan.substring(0, 13)
                                                  .length == 10
                                            ? 17
                                            : dataTrans.layanan[
                                                  lyn
                                              ].layanan.substring(0, 13)
                                                  .length == 9
                                            ? 18
                                            : dataTrans.layanan[
                                                  lyn
                                              ].layanan.substring(0, 13)
                                                  .length == 8
                                            ? 19
                                            : dataTrans.layanan[
                                                  lyn
                                              ].layanan.substring(0, 13)
                                                  .length == 7
                                            ? 20
                                            : dataTrans.layanan[
                                                  lyn
                                              ].layanan.substring(0, 13)
                                                  .length == 6
                                            ? 21
                                            : dataTrans.layanan[
                                                  lyn
                                              ].layanan.substring(0, 13)
                                                  .length == 5
                                            ? 22
                                            : 23) +
                                        "s",
                                    convertRupiah(dataTrans.layanan[lyn].harga)
                                )
                        );
                }
            }

            if (dataTrans.paket.length > 0) {
                for (var pktl = 0; pktl < dataTrans.paket.length; pktl++) {
                    if (dataTrans.paket[pktl].paket) {
                        jumPaket += 1;
                        printer
                            .align("left")
                            .text(dataTrans.paket[pktl].paket.substring(0, 25));
                        printer
                            .align("left")
                            .text(
                                " 1 X " +
                                    "               " +
                                    sprintf(
                                        "%12s",
                                        convertRupiah(
                                            dataTrans.paket[pktl].harga_paket
                                        )
                                    )
                            );
                    }
                }
            }

            if (dataTrans.produk.length > 0) {
                for (var lyn = 0; lyn < dataTrans.produk.length; lyn++) {
                    printer
                        .align("left")
                        .text(dataTrans.produk[lyn].produk.substring(0, 25));
                    var texts =
                        sprintf(
                            "%s",
                            " " +
                                convertRupiah(dataTrans.produk[lyn].kuantitas) +
                                " X " +
                                convertRupiah(dataTrans.produk[lyn].harga)
                        ) +
                        sprintf(
                            "%" +
                                (convertRupiah(
                                    parseInt(
                                        dataTrans.produk[lyn].kuantitas *
                                            dataTrans.produk[lyn].harga
                                    )
                                ).length > 6
                                    ? convertRupiah(
                                          parseInt(
                                              dataTrans.produk[lyn].kuantitas *
                                                  dataTrans.produk[lyn].harga
                                          )
                                      ).length > 8
                                        ? 18
                                        : 20
                                    : 21) +
                                "s",
                            convertRupiah(
                                parseInt(
                                    dataTrans.produk[lyn].kuantitas *
                                        dataTrans.produk[lyn].harga
                                )
                            )
                        );
                    printer.align("left").text(texts);
                }
            }

            printer.align("center").text("--------------------------------");

            printer
                .align("left")
                .text(
                    "Total Harga" +
                        sprintf("%3s", ":") +
                        sprintf(
                            "%18s",
                            convertRupiah(dataTrans.data.total_biaya)
                        )
                );

            var Bayare = dataTrans.data.cara_bayar_kasir == 1 ? "Cash" : "Card";

            printer
                .align("left")
                .text(
                    Bayare +
                        sprintf("%10s", ":") +
                        sprintf(
                            "%18s",
                            dataTrans.data.nominal_bayar
                                ? convertRupiah(dataTrans.data.nominal_bayar)
                                : "0"
                        )
                );

            printer
                .align("left")
                .text(
                    "Diskon Total" +
                        sprintf("%2s", ":") +
                        sprintf(
                            "%18s",
                            dataTrans.data.diskon
                                ? convertRupiah(dataTrans.data.diskon)
                                : "0"
                        )
                )
                .align("left")
                .text(
                    "Grand Total" +
                        sprintf("%3s", ":") +
                        sprintf(
                            "%18s",
                            dataTrans.data.total_biaya
                                ? dataTrans.data.diskon
                                    ? convertRupiah(
                                          parseInt(
                                              dataTrans.data.total_biaya -
                                                  dataTrans.data.diskon
                                          )
                                      )
                                    : convertRupiah(dataTrans.data.total_biaya)
                                : "0"
                        )
                );

            if (dataTrans.data.cara_bayar_kasir == 1) {
                printer
                    .align("left")
                    .text(
                        "Kembalian" +
                            sprintf("%5s", ":") +
                            sprintf(
                                "%18s",
                                convertRupiah(dataTrans.data.kembalian)
                            )
                    );
            }

            var totalItems = parseInt(
                dataTrans.layanan.length + jumPaket + dataTrans.produk.length
            );

            printer
                .align("center")
                .text("--------------------------------")
                .align("center")
                .text(
                    "*****  " +
                        totalItems +
                        " item" +
                        (totalItems > 1 ? "(s)" : "") +
                        "  *****"
                )
                .align("center")
                .text("================================")
                .text("Terimakasih atas kunjungan Anda")
                .text("Mohon periksa uang kembalian")
                .align("center")
                .text("")
                .cut()
                .print();
        })
        .catch(function (e) {
            if (e) {
                localStorage.setItem("error-print", "tunda-print");
                toastr.error("Gagal koneksi ke printer!", "Error!", {
                    timeOut: 5000,
                });
                return;
            }
            Pace.stop();
        })
        .finally(function () {
            var Err = localStorage.getItem("error-print");
            if (!Err) {
                toastr.success("Cetak Pembayaran sukses!", "Yeaay!", {
                    timeOut: 2000,
                    onHidden: function () {
                        toSendInfoPembayaran(events, onSave);
                    },
                });
            }
        });

    var timer = 5; // timer in seconds
    (function customSwal() {
        swal({
            title: "Proses",
            text:
                "Sedang mencetak, menutup otomatis pada " + timer + " detik !",
            timer: timer * 1000,
            button: false,
            icon: base_url + "/images/icons/loader.gif",
            closeOnClickOutside: false,
            closeOnEsc: false,
        }).then(() => {
            Pace.stop();
            swal.close();
        });

        if (timer) {
            timer--;
            if (timer > 0) {
                setTimeout(customSwal, 500);
            }
        }
    })();
}

function toSendInfoPembayaran(event, onsave) {
    var target = !onsave ? event.data("route") : event.data("route-on");
    var idCetak = !onsave ? event.data("id-cetak") : event.data("id-cetak-on");
    var tables = event.closest("table");

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $.ajax({
        url: target,
        type: "POST",
        data: {
            order_id: idCetak,
        },
        dataType: "JSON",
        success: function (data) {
            switch (data.cd) {
                case 200:
                    tables.DataTable().ajax.reload();
                    toastr.success(data.msg, "Success!", {
                        timeOut: 2000,
                        onHidden: function () {
                            localStorage.setItem("error-print", "");
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        },
                    });
                    break;
                default:
                    toastr.warning(data.msg, "Peringatan!", {
                        timeOut: 2000,
                        onHidden: function () {
                            localStorage.setItem("error-print", "");
                        },
                    });
                    break;
            }
        },
        error: function () {
            toastr.error("Kesalahan system!", "Error!", {
                timeOut: 2000,
                onHidden: function () {
                    localStorage.setItem("error-print", "");
                },
            });
        },
    });
}

function load_printCase() {
    $("tbody").delegate(".print", "click", function () {
        var event = $(this);
        toPrint(event, "");
    });

    $("tbody").delegate(".print-a", "click", function () {
        var event = $(this);
        var target = base_url + "/monitoring/print";

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            type: "POST",
            url: target,
            data: {
                id: event.data("id-cetak"),
            },
            success: function (result) {
                var myWidth = 650;
                var myHeight = 750;
                var left = (screen.width - myWidth) / 2;
                var top = (screen.height - myHeight) / 4;

                w = window.open(
                    "",
                    "_blank",
                    "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=" +
                        myWidth +
                        ", height=" +
                        myHeight +
                        ", top=" +
                        top +
                        ", left=" +
                        left
                );
                w.document.open();
                w.document.write(result);
                w.document.close();
                // w.window.print();
            },
            error: function () {
                toastr.error("Gagal mengambil data", "Oops!", {
                    timeOut: 2000,
                });
            },
        });
    });
}

function data_attribut() {
    var ElemtStart = rangeDate(0, "back");
    var ElemtEnd = rangeDate(1, "back");

    var dateranges =
        (ElemtStart ? "?starts=" + ElemtStart : "") +
        (ElemtEnd ? "&ends=" + ElemtEnd : "");

    var groupColumn = 1;
    var dTable = $("#data-table-view").DataTable({
        scrollX: true,
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/cashiers/json" + dateranges,
            type: "GET",
        },
        columns: [
            {
                data: "DT_RowIndex",
                name: "DT_RowIndex",
                orderable: false,
                searchable: false,
            },
            {
                data: "no_member",
                name: "no_member",
            },
            {
                data: "nama_member",
                name: "nama_member",
            },
            {
                data: "no_transaksi",
                name: "no_transaksi",
                className: "text-center",
            },
            {
                data: "waktu_reservasi",
                name: "waktu_reservasi",
                className: "text-center",
                render: function (data, type, row) {
                    return data ? getIndoDate(data) : "";
                },
            },
            {
                data: "created_at",
                name: "created_at",
                className: "text-center",
                render: function (data, type, row) {
                    return data ? getIndoDate(data) : "";
                },
            },
            {
                data: "hutang_biaya",
                name: "hutang_biaya",
                render: convertRupiah,
            },
            {
                data: "total_biaya",
                name: "total_biaya",
                render: convertRupiah,
            },
            {
                data: "agent",
                name: "agent",
                orderable: false,
                className: "text-center",
            },
            {
                data: "action",
                name: "action",
                orderable: false,
                className: "text-center",
            },
        ],
        order: [[0, "desc"]],
        drawCallback: function (settings) {
            var api = this.api();
            var rows = api
                .rows({
                    page: "current",
                })
                .nodes();
            var last = null;

            api.column(groupColumn, {
                page: "current",
            })
                .data()
                .each(function (group, i) {
                    if (last !== group) {
                        $(rows)
                            .eq(i)
                            .before(
                                '<tr class="group text-center" style="color:#fff !important; background-color:#7d8084 !important;"><td colspan="10"><strong>' +
                                    group +
                                    " ( " +
                                    api.column(2).data()[i] +
                                    " ) " +
                                    "</strong></td></tr>"
                            );

                        last = group;
                    }
                });
        },
    });
    dTable.ajax.reload();
    $("select[name=data-table-view_length]").on("change", function () {
        dTable.ajax.reload();
    });
    $("input[type=search]").on("input", function (e) {
        dTable.ajax.reload();
    });
    load_printCase();
    load_formPembayaran();

    $(".add-on-daterpicker").on("apply.daterangepicker", function (ev, picker) {
        fill_field_daterange(picker);
    });

    $(".add-on-daterpicker").on(
        "cancel.daterangepicker",
        function (ev, picker) {
            remove_field_daterange();
        }
    );

    $(".group-date-range").delegate(
        ".remove-on-daterpicker",
        "click",
        function () {
            remove_field_daterange();
        }
    );

    $(".add-on-daterpicker").daterangepicker({
        alwaysShowCalendars: true,
        showCustomRangeLabel: true,
        startDate: !$("input[name=id]").val()
            ? moment().add(0, "d").toDate()
            : $("input[name=berlaku_dari]").val(),
        endDate: !$("input[name=id]").val()
            ? moment().add(1, "d").toDate()
            : $("input[name=berlaku_sampai]").val(),
        singleDatePicker: false,
        showDropdowns: false,
        autoUpdateInput: true,
        locale: {
            cancelLabel: "Clear",
            format: "DD-MM-YYYY",
        },
    });
    $(".add-on-daterpicker").on("apply.daterangepicker", function (ev, picker) {
        fill_field_daterange(picker);
    });

    $(".add-on-daterpicker").on(
        "cancel.daterangepicker",
        function (ev, picker) {
            remove_field_daterange();
        }
    );

    $(".group-date-range").delegate(
        ".remove-on-daterpicker",
        "click",
        function () {
            remove_field_daterange();
        }
    );
}

function fill_field_daterange(picker) {
    $("input[name='berlaku_dari']").val(picker.startDate.format("DD-MM-YYYY"));
    $("input[name='berlaku_sampai']").val(picker.endDate.format("DD-MM-YYYY"));

    $("#data-table-view")
        .DataTable()
        .ajax.url(
            base_url +
                "/cashiers/json?starts=" +
                picker.startDate.format("YYYY-MM-DD") +
                "&ends=" +
                picker.endDate.format("YYYY-MM-DD")
        )
        .load();
}

function remove_field_daterange() {
    $("input[name='berlaku_dari']").val("");
    $("input[name='berlaku_sampai']").val("");

    $("#data-table-view")
        .DataTable()
        .ajax.url(base_url + "/cashiers/json")
        .load();
}

function getHarga(table, el, i) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: base_url + "/registrations/option?harga=" + el,
        data: {
            table: table,
        },
        method: "POST",
        dataType: "json",
        success: function (data) {
            $(".price-layanan-unique").attr(
                "data-price-layanan-new-" + i,
                data.harga
            );
        },
    });
}

function loadTotal(idLayanan) {
    var total_harga_paket = 0;
    var total_harga_layanan = 0;
    var total_harga_layanan_tambahan = 0;
    var total_harga_produk = 0;

    var total_harga_ruangan = 0;

    var rHarga = $("input[name=id]").data("ruangan-harga");

    if (!isNaN(rHarga)) {
        total_harga_ruangan += rHarga;
    }

    $(".price-layanan-unique").remove();
    $(".load-form-pembayaran").append(
        '<div class="price-layanan-unique"></div>'
    );

    for (var vp = 1; vp <= $(".n-f-paket").length; vp++) {
        var vvp = $("select#on-select-paket-" + vp)
            .find("option:selected")
            .data("harga");
        if (
            !isNaN(vvp) &&
            $("select#on-select-paket-" + vp).val() != "undefined"
        ) {
            total_harga_paket += parseInt(vvp);
        }
    }

    // var IdLayananNew = [];
    for (var vl = 1; vl <= $(".n-f-layanan").length; vl++) {
        var vvl = $("input#on-select-price-custom-" + vl).val();
        var vvl_ = vvl.split(".").join("");

        // var vvl2 = $("select#on-select-layanan-" + (vl - 1)).val()
        // var vvl3 = $("select#on-select-layanan-" + vl).val()
        if (!isNaN(vvl_) && vvl != "undefined") {
            total_harga_layanan += parseInt(vvl_);

            // if (idLayanan) {
            //     if (vvl2 === idLayanan) {
            //         toastr.warning('Oops!, Layanan telah dipesan, harga akan dikalkulasi per layanan', 'Peringatan!');
            //         return false;
            //     }
            // }
        }
        // IdLayananNew.push(vvl3)
    }

    for (var vlt = 1; vlt <= $(".n-f-layanan-tambahan").length; vlt++) {
        var vvlt = $("input#on-input-harga-tambahan-" + vlt).val();
        var vvlt_ = vvlt.split(".").join("");

        // var vvl2 = $("select#on-select-layanan-" + (vl - 1)).val()
        // var vvl3 = $("select#on-select-layanan-" + vl).val()

        if (!isNaN(vvlt_) && vvlt != "undefined") {
            total_harga_layanan_tambahan += parseInt(vvlt_);
            // if (idLayanan) {
            //     if (vvl2 === idLayanan) {
            //         toastr.warning('Oops!, Layanan telah dipesan, harga akan dikalkulasi per layanan', 'Peringatan!');
            //         return false;
            //     }
            // }
        }
        // IdLayananNew.push(vvl3)
    }

    // var uniqueLayNames = [];
    // $.each(IdLayananNew, function (i, el) {
    //     $(".price-layanan-unique").removeAttr('data-price-layanan-new-' + i)
    //     if ($.inArray(el, uniqueLayNames) === -1) {
    //         uniqueLayNames.push(el)
    //     }
    // });

    // $.each(uniqueLayNames, function (i, el) {
    //     getHarga('layanan', el, i)
    // });

    setTimeout(() => {
        // for (var ji = 0; ji < uniqueLayNames.length; ji++) {
        //     vVls = $(".price-layanan-unique").data('price-layanan-new-' + ji)
        //     total_harga_layanan += parseInt(vVls)
        // }

        for (var vpr = 1; vpr <= $(".point-nom").length; vpr++) {
            var vvpr = $("td.subtotal")
                .find("em#subtotal-" + vpr)
                .text();
            if (
                !isNaN(vvpr) &&
                $("td.subtotal")
                    .find("em#subtotal-" + vpr)
                    .text() != "undefined"
            ) {
                total_harga_produk += parseInt(vvpr);
            }
        }

        var RealTagihan =
            total_harga_paket +
            total_harga_layanan +
            total_harga_layanan_tambahan +
            total_harga_produk +
            total_harga_ruangan;

        var Price = RealTagihan;

        var elemt = $("#diskon");
        var value = elemt.val();
        var diskon = elemt.find("option:selected");
        var param = diskon.data("param");
        var nominal = diskon.data("nominal");

        if (value) {
            var addOn =
                param == "%"
                    ? ` ( -` + nominal + `% ) `
                    : ` ( -Rp. ` + convertRupiah(nominal) + ` ) `;
            $("#with-nom-p").addClass("text-danger italic").text(addOn);

            if (param == "%") {
                NewPrice = parseInt(parseInt(RealTagihan * nominal) / 100);
                Price = RealTagihan - NewPrice;
            }

            if (param == "Rp") {
                NewPrice = parseInt(RealTagihan - nominal);
                Price = NewPrice;
            }

            $("input.g-t-bel").attr("name", "grand_total").val(NewPrice);
        } else {
            Price = RealTagihan;
            $("#with-nom-p").removeClass("text-danger").text("");
            $("input.g-t-bel").removeAttr("name").val("0");
        }

        setTimeout(function () {
            $(".total-belanja").text(Price);
        }, 0);
    }, 1000);
}

function rangeDate(range_, back) {
    Date.prototype.addDays = function (days) {
        var date = new Date(this.valueOf());
        date.setDate(date.getDate() + days);
        return date;
    };

    var date = new Date();
    var ranges = range_;
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

    var today_date = !back
        ? day + "-" + month + "-" + year
        : year + "-" + month + "-" + day;

    return today_date.toString();
}

$(document).ready(function () {
    $("input[name=berlaku_dari]").val(rangeDate(0));
    $("input[name=berlaku_sampai]").val(rangeDate(1));

    load_data();
});
