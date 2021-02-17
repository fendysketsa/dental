function load_data() {
    var cont = $(".load-data");
    cont.load(base_url + "/monitoring/order/data", function (e, s, f) {
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

function formatTime(time) {
    var t = time.split(":");
    return t[0] + ":" + t[1];
}

function formatDate(date) {
    var d = new Date(date),
        month = "" + (d.getMonth() + 1),
        day = "" + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = "0" + month;
    if (day.length < 2) day = "0" + day;

    return [day, month, year].join("-");
}

function addOn(date) {
    $(".on-date input").val(date.format("DD-MM-YYYY"));
}

function load_formEdit() {
    $("tbody").delegate(".edit", "click", function () {
        $(".load-form-modal").html("");
        var event = $(this);

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            type: "PUT",
            url: event.data("route"),
            success: function (result) {
                $(".load-form-modal").html(result);
                $(".modal-title").html(
                    '<em class="fa fa-pencil-square-o"></em> Form Pendaftaran'
                );
                load_formLeft();
                select_members();
                var inputId = $("input[name=id]");
                load_formRight(event);

                $(".right-to").addClass("hide");
                $(".to-reservasi").css("marginLeft", "0px");
                $("button[type=submit]").attr("form", "formRegistrasi");

                setTimeout(function () {
                    $(".load-form-left").find("form").remove();
                    $(".clean-sheet").removeClass("on-dutty-off");
                }, 1500);

                setTimeout(function () {
                    setTimeout(function () {
                        $(".f-new-member").html(
                            '<div class="row"><div class="text-center"><em class="fa fa-spin fa-spinner"></em> loading...</div></div>'
                        );

                        $(".f-codereferal").html(
                            '<div class="text-left"><em class="fa fa-spin fa-spinner"></em> code referal loading...</div>'
                        );

                        setTimeout(() => {
                            var token = $("input[name=_token]").val();
                            $.ajax({
                                url: base_url + "/registrations/member/explore",
                                method: "POST",
                                data: {
                                    id: inputId.data("member-id"),
                                    _token: token,
                                },
                                dataType: "json",
                                success: function (data) {
                                    setTimeout(() => {
                                        $("select[name=sno_member]").val(
                                            inputId.data("member-id")
                                        );
                                        $("select[name=sno_member]").trigger(
                                            "change.select2"
                                        );
                                        setTimeout(() => {
                                            $(".f-new-member").html(
                                                f_member(data[0])
                                            );

                                            var tgl_lahir = $(
                                                "input[name=tanggal_lahir]"
                                            );
                                            $(
                                                ".add-on-daterpicker"
                                            ).daterangepicker(
                                                {
                                                    singleDatePicker: true,
                                                    autoUpdateInput: true,
                                                    showDropdowns: true,
                                                    startDate: !tgl_lahir.val()
                                                        ? moment()
                                                        : moment()
                                                              .add()
                                                              .format(
                                                                  tgl_lahir.val()
                                                              ),
                                                    locale: {
                                                        format: "DD-MM-YYYY",
                                                    },
                                                },
                                                addOn
                                            );

                                            select_("agama");

                                            setTimeout(function () {
                                                $("select[name=agama]")
                                                    .val(data[0].agama)
                                                    .trigger("change");
                                            }, 1000);

                                            $(".f-codereferal").css({
                                                height: "40px",
                                            });

                                            $(".f-codereferal").html(
                                                f_codereferal(
                                                    data[0].referal_code
                                                )
                                            );
                                        }, 800);
                                    }, 500);
                                },
                            });
                        }, 2500);

                        $(".load-row-layanan").html("");
                        $(".load-row-layanan-tambahan").html("");

                        $(".load-row-paket").html("");

                        $(".load-form-left").append(
                            '<div class="price-layanan-unique"></div>'
                        );

                        for (
                            var rL = 1;
                            rL <= inputId.data("layanan").length;
                            rL++
                        ) {
                            $(".load-row-layanan").append(load_row_layanan(rL));
                        }
                        $(".load-row-layanan").append(load_row_layanan);

                        $(".load-row-layanan-tambahan").append(
                            load_row_layanan_tambahan
                        );

                        for (
                            var rP = 1;
                            rP <= inputId.data("paket").length;
                            rP++
                        ) {
                            $(".load-row-paket").append(load_row_paket(rP));
                        }
                        $(".load-row-paket").append(load_row_paket);

                        $("input[name=jumlah_orang]")
                            .val(inputId.data("jum_org"))
                            .trigger("change");

                        $("select[name=room]")
                            .val(inputId.data("ruang"))
                            .trigger("change");

                        $("input[name=dp]")
                            .val(inputId.data("dp"))
                            .trigger("change");
                        $(".price-full")
                            .html(inputId.data("total-biaya"))
                            .trigger("change");

                        if (inputId.data("reservasi") !== "") {
                            var attr_ = $("button#show");
                            attr_.attr("id", "hide");
                            attr_
                                .find("em")
                                .removeClass("fa-question-circle")
                                .trigger("change")
                                .addClass("fa-check-circle");
                            attr_
                                .removeClass("btn-default")
                                .trigger("change")
                                .addClass("btn-success");

                            $(".f-reservasi").html(f_reservasi());

                            $(".datepicker").datepicker({
                                format: "dd-mm-yyyy",
                                startDate: "today",
                            });
                            var waktu = inputId.data("reservasi").split(" ");
                            $("input[name=jam_reservasi]")
                                .timepicker({
                                    showInputs: false,
                                    showMeridian: false,
                                    timeFormat: "HH:mm",
                                    step: 15,
                                })
                                .val(formatTime(waktu[1]))
                                .trigger("change");
                            select_("lokasi");
                            $("select[name=lokasi_reservasi]").select2({
                                placeholder: "Please select!",
                                allowClear: true,
                                theme: "bootstrap",
                            });
                            $("input[name=tgl_reservasi]")
                                .val(formatDate(waktu[0]))
                                .trigger("change");
                            setTimeout(function () {
                                $("select[name=lokasi_reservasi]")
                                    .val(inputId.data("lokasi"))
                                    .trigger("change");
                            }, 700);
                        }
                    }, 500);
                }, 500);
            },
            error: function () {
                toastr.error("Gagal mengambil data", "Oops!", {
                    timeOut: 2000,
                });
            },
        });
    });

    $("tbody").delegate(".periksa", "click", function () {
        $(".load-form-modal-periksa").html("");
        var event = $(this);

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            type: "PUT",
            url: event.data("route"),
            success: function (result) {
                $(".load-form-modal-periksa").html(result);
                $(".modal-title").html(
                    '<em class="fa fa-pencil-square-o"></em> Form Periksa Rekam Medik'
                );
                load_formLeft();

                setTimeout(function () {
                    setTimeout(function () {
                        $(".f-new-member").html(
                            '<div class="row"><div class="text-center"><em class="fa fa-spin fa-spinner"></em> loading...</div></div>'
                        );

                        $(".f-codereferal").html(
                            '<div class="text-left"><em class="fa fa-spin fa-spinner"></em> code referal loading...</div>'
                        );

                        setTimeout(() => {
                            var token = $("input[name=_token]").val();
                            $.ajax({
                                url: base_url + "/registrations/member/explore",
                                method: "POST",
                                data: {
                                    id: inputId.data("member-id"),
                                    _token: token,
                                },
                                dataType: "json",
                                success: function (data) {
                                    setTimeout(() => {
                                        //
                                    }, 500);
                                },
                            });
                        }, 2500);
                    }, 500);
                }, 500);
            },
            error: function () {
                toastr.error("Gagal mengambil data", "Oops!", {
                    timeOut: 2000,
                });
            },
        });
    });
}

function f_codereferal(code) {
    var html = "";

    return (html +=
        `<span class="text-info to-point-code">Code Referal: ` +
        code +
        `</span>`);
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
    return !bilangan_ ? 0 : rupiah;
}

function onInputRupiah() {
    $("input[type=rupiah]").on("input", function (e) {
        var inpValue = $(this)
            .val()
            .replace(/[^,\d]/g, "")
            .toString();

        if (isNaN(inpValue)) {
            $(this).val("0");
            return false;
        }

        if (inpValue < 1) {
            $(this).val("");
            toastr.warning(
                "Tidak diperkenankan input angka 0 di depan!",
                "Ooopps!",
                {
                    timeOut: 2000,
                }
            );
            return false;
        }

        var Inp = inpValue.replace(/^0/gi, "");
        $(this).val(convertRupiah(Inp));
    });
}

function rePrint(events, dataTrans, onsave) {
    var jumPaket = 0;
    var onSave = onsave;
    localStorage.setItem("error-print", "");
    var operator = $(".user-panel").find("p").text().trim() || "";
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
                .text("-------------------------------")
                .text("WORK ORDER")
                .feed(1)
                .align("left")
                .text(
                    "No. Trans" +
                        sprintf("%3s", ": ") +
                        sprintf(
                            "%20s",
                            "GW-" +
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
                .text(
                    "Operator" +
                        sprintf("%4s", ": ") +
                        sprintf("%20s", operator)
                )
                .align("center")
                .text("-------------------------------");

            if (dataTrans.layanan.length > 0) {
                for (var lyn = 0; lyn < dataTrans.layanan.length; lyn++) {
                    printer
                        .align("left")
                        .text(dataTrans.layanan[lyn].kategori.substring(0, 28))
                        .align("left")
                        .text(
                            sprintf(
                                "%s",
                                " 1 X " +
                                    dataTrans.layanan[lyn].layanan.substring(
                                        0,
                                        25
                                    ) +
                                    "/" +
                                    (dataTrans.layanan[lyn].terapis
                                        ? dataTrans.layanan[
                                              lyn
                                          ].terapis.substring(0, 8)
                                        : "-")
                            )
                        );
                }
            }

            if (dataTrans.paket.length > 0) {
                for (var pketl = 0; pketl < dataTrans.paket.length; pketl++) {
                    if (dataTrans.paket[pketl].paket) {
                        jumPaket += 1;
                        printer
                            .align("left")
                            .text(
                                dataTrans.paket[pketl].paket.substring(0, 28)
                            );
                    }
                    printer
                        .align("left")
                        .text(
                            " - " +
                                sprintf(
                                    "%s",
                                    dataTrans.paket[pketl].layanan.substring(
                                        0,
                                        25
                                    ) +
                                        "/" +
                                        (dataTrans.paket[pketl].terapis
                                            ? dataTrans.paket[
                                                  pketl
                                              ].terapis.substring(0, 8)
                                            : "-")
                                )
                        );
                }
            }

            printer
                .align("center")
                .text("-------------------------------")
                .text("Selamat bekerja, Semangat!")
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
                toastr.success("Cetak Order sukses!", "Yeaay!", {
                    timeOut: 2000,
                    onHidden: function () {
                        swal({
                            title: "Konfirmasi!",
                            text: "Lanjutkan untuk simpan ke pembayaran?",
                            icon: "warning",
                            buttons: ["tidak", "Lanjutkan!"],
                            dangerMode: false,
                        }).then((willConfirm) => {
                            if (willConfirm) {
                                toSendPembayaran(events, onSave);
                            } else {
                                var tables = events.closest("table");
                                tables.DataTable().ajax.reload();
                            }
                        });
                        return false;
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

function toVoidPembayaran(event) {
    var target = event.data("route-void");
    var idVoid = event.data("id-void");
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
            order_id: idVoid,
        },
        dataType: "JSON",
        success: function (data) {
            switch (data.cd) {
                case 200:
                    tables.DataTable().ajax.reload();
                    toastr.success(data.msg, "Success!", {
                        timeOut: 2000,
                    });
                    break;
                default:
                    toastr.warning(data.msg, "Peringatan!", {
                        timeOut: 2000,
                    });
                    break;
            }
        },
        error: function () {
            toastr.error("Kesalahan system!", "Error!", {
                timeOut: 2000,
            });
        },
    });
}

function toSendPembayaran(event, onsave) {
    var target = !onsave
        ? event.data("route")
        : onsave == "send-pembayaran"
        ? event.data("route-send-pembayaran")
        : event.data("route-on");
    var idCetak = !onsave
        ? event.data("id-cetak")
        : onsave == "send-pembayaran"
        ? event.data("id-send-pembayaran")
        : event.data("id-cetak-on");
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

function toPrint(event, onsave, printAct) {
    var target = base_url + "/monitoring/order/det";
    var idCetak = !onsave ? event.data("id-cetak") : event.data("id-cetak-on");

    $.ajax({
        url: target + "/" + idCetak + (printAct ? "?printact=yes" : ""),
        type: "GET",
        success: function (data) {
            rePrint(event, data, onsave);
        },
    });
}

function load_printCase() {
    $("tbody").delegate(".print", "click", function () {
        var event = $(this);
        toPrint(event, "", "print-act");
    });
}

function load_sendPembayaranCase() {
    $("tbody").delegate(".send-pembayaran", "click", function () {
        var event = $(this);

        swal({
            title: "Lanjutkan ke Pembayaran ?",
            text: "Data akan disimpan ke pembayaran.",
            icon: "warning",
            buttons: ["Batal", "Ok"],
            dangerMode: true,
        }).then(function (willExec) {
            if (willExec) {
                toSendPembayaran(event, "send-pembayaran");
            } else {
                swal.close();
            }
        });
    });
}

function load_voidPembayaranCase() {
    $("tbody").delegate(".void-pembayaran", "click", function () {
        var event = $(this);

        swal({
            title: "Membatalkan order ?",
            text: "Data akan dibatalkan.",
            icon: "warning",
            buttons: ["Batal", "Ok"],
            dangerMode: true,
        }).then(function (willExec) {
            if (willExec) {
                toVoidPembayaran(event, "void-pembayaran");
            } else {
                swal.close();
            }
        });
    });
}

function load_Activation() {
    $("tbody").delegate(".activation", "click", function () {
        var event = $(this);
        var tables = event.closest("table");

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            type: "GET",
            url: event.data("route"),
            success: function (data) {
                switch (data.code) {
                    case 200:
                        tables.DataTable().ajax.reload();
                        toastr.success(data.message, "Success!", {
                            timeOut: 2000,
                        });
                        break;
                    default:
                        toastr.warning(data.message, "Peringatan!", {
                            timeOut: 2000,
                        });
                        break;
                }
            },
            error: function () {
                toastr.error("Gagal mengambil data", "Oops!", {
                    timeOut: 2000,
                });
            },
        });
    });
}

function count_select(cs) {
    var cs_ = cs.toString().split(",").length;
    return cs_;
}

function f_reservasi() {
    var html = "";
    html += `<div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal: <em class="text-danger">*</em></label>
                        <div class="input-group input-group-sm date">
                            <input readonly type="text" name="tgl_reservasi" class="form-control datepicker" placeholder="Tanggal..." form="formRegistrasi">
                            <div class="input-group-addon bg-gray">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 bootstrap-timepicker">
                    <div class="form-group">
                        <label>Jam: <em class="text-danger">*</em></label>
                        <div class="input-group input-group-sm date">
                            <input readonly type="text" name="jam_reservasi" class="form-control timepicker" placeholder="Jam..." form="formRegistrasi">
                            <div class="input-group-addon bg-gray">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Lokasi: <em class="text-danger">*</em></label>
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon bg-gray">
                            <i class="fa fa-home"></i>
                        </div>
                        <select name="lokasi_reservasi" class="select2 form-control f-lokasi" style="width: 100%;"
                            form="formRegistrasi" tabindex="-1" aria-hidden="true">
                            <option></option>
                        </select>
                    </div>
                </div>`;
    return html;
}

function select_members() {
    return `<select name="sno_member" class="select2 input-group-sm form-control f-member" style="width: 100%;" form="formRegistrasi"></select>`;
}

function input_member() {
    var html = "";
    var DatAuto = $(".noMember").data("auto-nom");
    html +=
        `<input type="text" name="ino_member" value="` +
        DatAuto +
        `" class="form-control input-sm" placeholder="No Member..." form="formRegistrasi">`;
    return html;
}

function f_member(data) {
    var html = "";
    html +=
        `<div class="form-group">
                <label>Nama: <em class="text-danger">*</em></label>
                <div class="input-group input-group-sm date">
                    <div class="input-group-addon">
                        <i class="fa fa-tag"></i>
                    </div>
                    <input type="text" name="nama" value="` +
        (data.nama ? data.nama : "") +
        `" class="form-control" placeholder="Nama..." form="formRegistrasi">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email:</label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </div>
                        <input type="email" name="email" value="` +
        (data.email ? data.email : "") +
        `" class="form-control" placeholder="Email..." form="formRegistrasi">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Telepon: <em class="text-danger">*</em></label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <input type="text" name="telepon" value="` +
        (data.telepon ? data.telepon : "") +
        `" class="form-control" placeholder="Telepon..." form="formRegistrasi">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-xs-6 col-sm-6">
                    <div class="form-group input-group-sm">
                        <label>Jenis Kelamin: <em class="text-danger">*</em></label>
                            <label class="container-radio"> Laki-laki
                                <input type="radio" name="jenis_kelamin" ` +
        (data
            ? data.jenis_kelamin && data.jenis_kelamin == "Laki-laki"
                ? 'checked="checked"'
                : ""
            : "") +
        ` value="Laki-laki" form="formRegistrasi">
                                <span class="checkmark-radio"></span>
                            </label>
                        </div>
                    </div>
                <div class="col-md-6 col-xs-6 col-sm-6">
                    <div class="form-group input-group-sm">
                        <label>&nbsp;</label>
                        <label class="container-radio"> Perempuan
                            <input type="radio" name="jenis_kelamin" ` +
        (data
            ? data.jenis_kelamin && data.jenis_kelamin == "Perempuan"
                ? 'checked="checked"'
                : ""
            : 'checked="checked"') +
        `
                            value="Perempuan" form="formRegistrasi">
                            <span class="checkmark-radio"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tanggal Lahir:</label>
                        <div class="input-group input-group-sm date on-date">
                            <div class="input-group-addon add-on-daterpicker">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" name="tanggal_lahir" value="` +
        (data ? (data.tgl_lahir ? data.tgl_lahir : "") : "") +
        `" class="form-control" placeholder="Tanggal Lahir..." form="formRegistrasi" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>NIK:</label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-tag"></i>
                            </div>
                            <input type="text" name="nik" maxlength="16" value="` +
        (data ? (data.nik ? data.nik : "") : "") +
        `" class="form-control" placeholder="NIK..." form="formRegistrasi">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Agama:</label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-heart"></i>
                            </div>
                            <select name="agama" class="select2 input-group-sm form-control f-agama" style="width: 100%;"
                            form="formRegistrasi"></select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Profesi:</label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-briefcase"></i>
                            </div>
                            <input type="text" name="profesi" value="` +
        (data ? (data.profesi ? data.profesi : "") : "") +
        `" class="form-control" placeholder="Profesi..." form="formRegistrasi">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Instansi:</label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-bank"></i>
                            </div>
                            <input type="text" name="instansi" value="` +
        (data ? (data.instansi ? data.instansi : "") : "") +
        `" class="form-control" placeholder="Instansi..." form="formRegistrasi">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-xs-6 col-sm-6">
                    <div class="form-group input-group-sm">
                        <label>Member Status ? <em class="text-danger">*</em></label>
                            <label class="container-radio"> Umum
                                <input type="radio" name="status_member" ` +
        (data
            ? data.status_member && data.status_member == "Umum"
                ? 'checked="checked"'
                : ""
            : 'checked="checked"') +
        ` value="Umum" form="formRegistrasi">
                                <span class="checkmark-radio"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-6 col-sm-6">
                        <div class="form-group input-group-sm">
                            <label>&nbsp;</label>
                            <label class="container-radio"> Cooperation
                                <input type="radio" name="status_member" ` +
        (data
            ? data.status_member && data.status_member == "Cooperation"
                ? 'checked="checked"'
                : ""
            : "") +
        `
                                value="Cooperation" form="formRegistrasi">
                                <span class="checkmark-radio"></span>
                            </label>
                        </div>
                    </div>
            </div>
            <div class="form-group input-group-sm">
                <label>Alamat:</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-home"></i>
                        </div>
                    <textarea name="alamat" cols="15" rows="4" class="form-control input-sm add-style" form="formRegistrasi" placeholder="Alamat...">` +
        (data.alamat ? data.alamat : "") +
        `</textarea>
                </div>
            </div>`;
    return html;
}

function f_paket() {
    var html = ``;
    html += `<div class="row">
                    <div class="col-md-12">
                        <div class="form-group input-group-sm">
                            <label>Paket:</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-gift"></i>
                                </div>
                                <select name="paket" class="select2 f-paket form-control" style="width: 100%;" form="formRegistrasi"></select>
                            </div>
                        </div>
                    </div>
                </div>`;
    return $("#paket").html(html);
}

function f_nonpaket() {
    var html = ``;
    html += `<div class="form-group input-group-sm">
                <label>Layanan:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-share"></i>
                    </div>
                    <select name="layanan[]" class="select2 f-layanan form-control" multiple="multiple" style="width: 100%;" form="formRegistrasi"></select>
                </div>
            </div>`;
    return $("#non-paket").html(html);
}

function loadTotal(idLayanan) {
    var total_harga_paket = 0;
    var total_harga_layanan = 0;

    $(".price-layanan-unique").remove();
    $(".load-form-left").append('<div class="price-layanan-unique"></div>');

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
        var vvl = $("select#on-select-layanan-" + vl)
            .find("option:selected")
            .data("harga");
        // var vvl2 = $("select#on-select-layanan-" + (vl - 1)).val()
        // var vvl3 = $("select#on-select-layanan-" + vl).val()
        if (
            !isNaN(vvl) &&
            $("select#on-select-layanan-" + vl).val() != "undefined"
        ) {
            total_harga_layanan += parseInt(vvl);
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
    //     if ($.inArray(el, uniqueLayNames) === -1) {
    //         uniqueLayNames.push(el)
    //     }
    // });

    // $.each(uniqueLayNames, function (i, el) {
    //     getHarga('layanan', el, i)
    // });

    // setTimeout(() => {
    //     for (var ji = 0; ji < uniqueLayNames.length; ji++) {
    //         vVls = $(".price-layanan-unique").data('price-layanan-new-' + ji)
    //         total_harga_layanan += parseInt(vVls)
    //     }

    //     var fullTotal = total_harga_paket + total_harga_layanan;
    //     $(".price-full").text(convertRupiah(fullTotal));
    // }, 2500);

    var fullTotal = total_harga_paket + total_harga_layanan;
    $(".price-full").text(convertRupiah(fullTotal));
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

function form_attribut_right() {
    $("button.collap-paket").on("click", function (e) {
        var exp = $(this).attr("aria-expanded");
        if (exp == "true") {
            $(this)
                .find("em")
                .removeClass("fa-minus")
                .addClass("fa-plus")
                .trigger("change");
        } else {
            $(this)
                .find("em")
                .removeClass("fa-plus")
                .addClass("fa-minus")
                .trigger("change");
            $(".select2-container").css({
                width: "100%",
            });
        }
    });

    $("input[name=dp]").on("keyup", function () {
        var total = parseInt($(".price-full").text());
        var dp = parseInt($(this).val());

        if (total > 0) {
            if (dp > total) {
                $("button[type=submit]").attr("disabled", true);
                toastr.warning("Maaf, DP Anda berlebihan!", "Peringatan!");
            } else {
                $("button[type=submit]").removeAttr("disabled");
            }
        }
    });

    $(".add-row-layanan").on("click", function () {
        $(".load-row-layanan").append(load_row_layanan);
    });

    $(".add-row-layanan-tambahan").on("click", function () {
        $(".load-row-layanan-tambahan").append(load_row_layanan_tambahan);
    });

    $(".add-row-paket").on("click", function () {
        $(".load-row-paket").append(load_row_paket);
    });

    $("table tbody.load-row-layanan").delegate(
        "tr > td > em.remove-row-layanan",
        "click",
        function (e) {
            $(this)
                .parents("tr")
                .html(
                    '<td colspan="4" class="text-center"><em class="fa fa-spinner fa-spin"></em> Loading...</td>'
                )
                .fadeOut("slow", function (e) {
                    $(this).remove();

                    var trs = $(".load-row-layanan tr.n-f-layanan");
                    trs.each(function (e, f) {
                        $(trs[e])
                            .find("td.nom-layanan")
                            .text(e + 1);
                        $(trs[e])
                            .find("td.select-layanan")
                            .find("select")
                            .removeAttr("id")
                            .attr("id", "on-select-layanan-" + (e + 1));
                        // $(trs[e])
                        //     .find("td.select-terapis")
                        //     .find("select")
                        //     .removeAttr("id")
                        //     .attr("id", "on-select-terapis-" + (e + 1));
                    });

                    loadTotal();
                });
        }
    );

    $("table tbody.load-row-layanan-tambahan").delegate(
        "tr > td > em.remove-row-layanan-tambahan",
        "click",
        function (e) {
            $(this)
                .parents("tr")
                .html(
                    '<td colspan="4" class="text-center"><em class="fa fa-spinner fa-spin"></em> Loading...</td>'
                )
                .fadeOut("slow", function (e) {
                    $(this).remove();

                    var trs = $(
                        ".load-row-layanan-tambahan tr.n-f-layanan-tambahan"
                    );
                    trs.each(function (e, f) {
                        $(trs[e])
                            .find("td.nom-layanan-tambahan")
                            .text(e + 1);
                        $(trs[e])
                            .find("td.input-layanan-tambahan")
                            .find("input")
                            .removeAttr("id")
                            .attr("id", "on-input-layanan-tambahan-" + (e + 1));
                        $(trs[e])
                            .find("td.input-layanan-harga")
                            .find("input")
                            .removeAttr("id")
                            .attr("id", "on-input-harga-tambahan-" + (e + 1));
                    });

                    loadTotal();
                });
        }
    );

    $("table tbody.load-row-paket").delegate(
        "tr > td > em.remove-row-paket",
        "click",
        function (e) {
            $(this)
                .parents("tr")
                .html(
                    '<td colspan="3" class="text-center"><em class="fa fa-spinner fa-spin"></em> Loading...</td>'
                )
                .fadeOut("slow", function (e) {
                    $(this).remove();

                    var trs = $(".load-row-paket tr.n-f-paket");
                    trs.each(function (e, f) {
                        $(trs[e])
                            .find("td.nom-paket")
                            .text(e + 1);
                        $(trs[e])
                            .find("td.select-paket")
                            .find("select")
                            .removeAttr("id")
                            .attr("id", "on-select-paket-" + (e + 1));
                        $(trs[e])
                            .find("td.select-paket")
                            .find("div#table-th")
                            .removeAttr("class")
                            .attr(
                                "class",
                                "row hide load-more-paket-" + (e + 1)
                            );
                        $(trs[e])
                            .find("td.select-paket")
                            .find("tbody#table-td")
                            .removeAttr("class")
                            .attr("class", "load-row-paketlayanan-" + (e + 1));
                        $(trs[e])
                            .find("td.select-paket")
                            .find("tbody#table-td")
                            .find("select")
                            .removeAttr("name")
                            .attr(
                                "name",
                                "pkt_layanan_terapis[" + (e + 1) + "][]"
                            );

                        if (
                            $(trs[e])
                                .find("td.select-paket")
                                .find("select#on-select-paket-" + (e + 1))
                                .val() != ""
                        ) {
                            $(trs[e])
                                .find("td.select-paket")
                                .find("div.load-more-paket-" + (e + 1))
                                .removeClass("hide");
                            load_avail_layanan_on_paket(
                                "layanan",
                                e + 1,
                                $(trs[e])
                                    .find("td.select-paket")
                                    .find("select#on-select-paket-" + (e + 1))
                                    .val()
                            );
                            loadTotal();

                            setTimeout(function () {
                                $(trs[e])
                                    .find("td.select-paket")
                                    .delegate(
                                        "#on-select-paket-" + (e + 1),
                                        "change",
                                        function (ev) {
                                            var PktlayId = $(this).val();
                                            loadTotal();

                                            if (PktlayId) {
                                                load_avail_layanan_on_paket(
                                                    "layanan",
                                                    e + 1,
                                                    PktlayId
                                                );
                                            } else {
                                                var trps = $(
                                                    "#on-select-onpkt-terapis-" +
                                                        (e + 1)
                                                );
                                                trps.attr("disabled", true);
                                                trps.val("").trigger("change");
                                            }
                                        }
                                    );
                            }, 500);
                        } else {
                            $(trs[e])
                                .find("td.select-paket")
                                .find("div.load-more-paket-" + (e + 1))
                                .addClass("hide");
                        }
                    });

                    loadTotal();
                });
        }
    );

    select_("room");
}

function load_row_paket(idPket) {
    var thisElem = $(".n-f-paket");
    var numb = thisElem.length + 1;

    var html = `<tr class="n-f-paket">`;
    html += `<td class="nom-paket text-center">` + numb + `</td>`;
    html +=
        `<td class="select-paket td-height-img">
                <div id="block" class="blocking-loading-row"><em class="fa fa-spinner fa-spin"></em> Loading...</div>
                <div class="input-group-sm">
                    <select name="paket[]" form="formRegistrasi" class="select2 form-control input-group-sm" disabled id="on-select-paket-` +
        numb +
        `"></select>
                </div>`;
    html +=
        `<div id="table-th" class="row load-more-paket-` +
        numb +
        ` hide" style="margin-top:10px !important;">
                <div class="col-md-12">
                    <table class="table hover" width="100%" cellspacing="0" style="margin-bottom: 0px !important;">
                        <thead class="bg-navy disabled color-palette">
                            <tr>
                                <th class="text-center" style="width:5%;">No</th>
                                <th class="text-left" style="width:55%;">Layanan</th>
                                <th class="text-left" style="width:40%">Terapis</th>
                            </tr>
                        </thead>
                        <tbody id="table-td" class="load-row-paketlayanan-` +
        numb +
        `">
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
    html += `<td class="text-center"><em class="fa fa-times remove-row-paket text-danger"></em></td>`;
    html += `</tr>`;

    setTimeout(() => {
        if (idPket) {
            var exp = $("button.collap-paket").attr("aria-expanded");
            if (exp == "true") {
                $("#cont-paket")
                    .removeClass("in")
                    .removeAttr("aria-expanded", false);
                $("button.collap-paket")
                    .find("em")
                    .removeClass("fa-minus")
                    .addClass("fa-plus")
                    .trigger("change");
            } else {
                $("#cont-paket").addClass("in").attr("aria-expanded", true);
                $("button.collap-paket")
                    .find("em")
                    .removeClass("fa-plus")
                    .addClass("fa-minus")
                    .trigger("change");
            }

            load_avail_layanan(
                "paket",
                numb,
                $("input[name=id]").data("paket")[idPket - 1]
            );
            $(".load-more-paket-" + numb).removeClass("hide");
            load_avail_layanan_on_paket(
                "layanan",
                numb,
                $("input[name=id]").data("paket")[idPket - 1],
                $("input[name=id]").data("paket-terapis-" + numb).length
            );
        } else {
            load_avail_layanan("paket", numb);
        }

        $(".input-group-sm").delegate(
            "#on-select-paket-" + numb,
            "change",
            function (e) {
                var PktlayId = $(this).val();
                loadTotal();

                if (PktlayId) {
                    $(".load-more-paket-" + numb).removeClass("hide");
                    load_avail_layanan_on_paket("layanan", numb, PktlayId);
                } else {
                    $(".load-more-paket-" + numb).addClass("hide");
                    var trps = $("#on-select-onpkt-terapis-" + numb);
                    trps.attr("disabled", true);
                    trps.val("").trigger("change");
                }
            }
        );
    }, 500);

    return html;
}

function load_row_layanan(idLayanan, idTerapis) {
    var thisElem = $(".n-f-layanan");
    var numb = thisElem.length + 1;

    var html = `<tr class="n-f-layanan">`;
    html +=
        `<td class="nom-layanan td-height-img text-center">` + numb + `</td>`;
    html +=
        `<td class="select-layanan td-height-img">
                <div id="block" class="blocking-loading-row"><em class="fa fa-spinner fa-spin"></em> Loading...</div>
                <div class="input-group-sm">
                    <select ` +
        (!idLayanan ? ` name="layanan[]" ` : "") +
        ` form="formRegistrasi" class="select2 form-control input-group-sm" disabled id="on-select-layanan-` +
        numb +
        `"></select>
                    ` +
        (idLayanan
            ? `<input id="inpt-select-` +
              numb +
              `" name="layanan[]" form="formRegistrasi" type="hidden">`
            : "") +
        `
                </div>
            </td>`;
    // html +=
    //     `<td class="select-terapis td-height-img">
    //             <div class="input-group-sm">
    //                 <select name="terapis[]" form="formRegistrasi" class="select2 form-control input-group-sm" disabled id="on-select-terapis-` +
    //     numb +
    //     `"></select>
    //             </div>
    //         </td>`;

    html += `<td class="td-height-img text-center"><em class="fa fa-times remove-row-layanan text-danger"></em></td>`;
    html += `</tr>`;

    setTimeout(() => {
        if (idLayanan) {
            load_avail_layanan(
                "layanan",
                numb,
                $("input[name=id]").data("layanan")[idLayanan - 1]
            );
            load_avail_layanan(
                "terapis",
                numb,
                $("input[name=id]").data("layanan")[idLayanan - 1],
                $("input[name=id]").data("terapis")[idLayanan - 1]
            );
            setTimeout(() => {
                $("#on-select-layanan-" + idLayanan).attr("disabled", true);
                $("#inpt-select-" + numb).val(
                    $("#on-select-layanan-" + idLayanan).val()
                );
            }, 2000);
        } else {
            load_avail_layanan("layanan", numb);
        }

        $("#on-select-layanan-" + numb).on("change", function (e) {
            var layId = $(this).val();
            loadTotal(layId);
            if (layId) {
                // $("#on-select-terapis-" + numb).removeAttr("disabled");
                // load_avail_layanan("terapis", numb, layId);
            } else {
                // var trps = $("#on-select-terapis-" + numb);
                // trps.attr("disabled", true);
                // trps.val("").trigger("change");
            }
        });
    }, 500);

    return html;
}

function load_row_layanan_tambahan(idLayanan, idTerapis) {
    var thisElem = $(".n-f-layanan-tambahan");
    var numb = thisElem.length + 1;

    var html = `<tr class="n-f-layanan-tambahan">`;
    html +=
        `<td class="nom-layanan-tambahan td-height-img text-center">` +
        numb +
        `</td>`;
    html +=
        `<td class="input-layanan-tambahan td-height-img">
                <div id="block" class="blocking-loading-row"><em class="fa fa-spinner fa-spin"></em> Loading...</div>
                <div class="input-group-sm">
                    <input type="text" ` +
        (!idLayanan ? ` name="layanan_tambahan[]" ` : "") +
        ` form="formRegistrasi" placeholder="layanan tambahan..." disabled class="form-control input-group-sm" id="on-input-layanan-tambahan-` +
        numb +
        `">` +
        `
                </div>
            </td>`;

    html +=
        `<td class="input-layanan-harga td-height-img">
                <div class="input-group-sm">
                    <input type="rupiah" placeholder="harga..." name="harga_tambahan[]" disabled form="formRegistrasi" class="form-control input-group-sm" id="on-input-harga-tambahan-` +
        numb +
        `">
                </div>
            </td>`;

    html += `<td class="td-height-img text-center"><em class="fa fa-times remove-row-layanan-tambahan text-danger"></em></td>`;
    html += `</tr>`;

    setTimeout(() => {
        $(".input-layanan-tambahan")
            .find("div#block")
            .removeClass("blocking-loading-row")
            .addClass("hide");

        $(".n-f-layanan-tambahan").find("input").removeAttr("disabled");

        onInputRupiah();

        if (idLayanan) {
            // load_avail_layanan(
            //     "layanan",
            //     numb,
            //     $("input[name=id]").data("layanan")[idLayanan - 1]
            // );
            // load_avail_layanan(
            //     "terapis",
            //     numb,
            //     $("input[name=id]").data("layanan")[idLayanan - 1],
            //     $("input[name=id]").data("terapis")[idLayanan - 1]
            // );
            // setTimeout(() => {
            //     $("#on-select-layanan-" + idLayanan).attr("disabled", true);
            //     $("#inpt-select-" + numb).val(
            //         $("#on-select-layanan-" + idLayanan).val()
            //     );
            // }, 2000);
        } else {
            // load_avail_layanan("layanan", numb);
        }

        $("#on-select-layanan-" + numb).on("change", function (e) {
            var layId = $(this).val();
            // loadTotal(layId);
            if (layId) {
                // $("#on-select-terapis-" + numb).removeAttr("disabled");
                // load_avail_layanan("terapis", numb, layId);
            } else {
                // var trps = $("#on-select-terapis-" + numb);
                // trps.attr("disabled", true);
                // trps.val("").trigger("change");
            }
        });
    }, 500);

    return html;
}

function load_avail_playanant(table, numb, layanan, p_servs) {
    var pID = layanan || 0;
    var pSIDs = p_servs || 0;

    $.ajax({
        url:
            base_url +
            "/registrations/opt-terapis/" +
            table +
            "?layanan=" +
            pID,
        method: "GET",
        dataType: "json",
        success: function (data) {
            if (data.length === 0) {
                $(
                    "select#on-select-paketlayananterapis-" +
                        numb +
                        "-lay-" +
                        pID
                )
                    .attr("disabled", true)
                    .removeAttr("name,form");
                $(
                    "div#on-input-paketlayananterapis-" + numb + "-lay-" + pID
                ).html(
                    `<input type="hidden" form="formRegistrasi" name="pkt_layanan_terapis[` +
                        numb +
                        `][]" readonly value="0">`
                );
            } else {
                $(
                    "select#on-select-paketlayananterapis-" +
                        numb +
                        "-lay-" +
                        pID
                ).removeAttr("disabled");
            }

            var html = [];
            html += `<option></option>`;

            for (var i = 0; i < data.length; i++) {
                var selectedTrps =
                    table == "pegawai"
                        ? $("input[name=id]").data("paket-terapis-" + numb)
                            ? data[i].id ==
                              $("input[name=id]").data("paket-terapis-" + numb)[
                                  pSIDs
                              ]
                                ? "selected"
                                : ""
                            : ""
                        : "";
                html +=
                    `<option ` +
                    selectedTrps +
                    ` value='` +
                    data[i].id +
                    `'>` +
                    data[i].nama +
                    `</option>`;
                $(
                    "select#on-select-paketlayananterapis-" +
                        numb +
                        "-lay-" +
                        pID
                ).html(html);
            }

            $(
                "select#on-select-paketlayananterapis-" + numb + "-lay-" + pID
            ).select2({
                placeholder: "Please select!",
                allowClear: true,
                theme: "bootstrap",
            });
        },
    });
}

function load_avail_layanan(table, numb, p_id, p_terps) {
    var pID = p_id || 0;
    var pTrID = p_terps || 0;

    $.ajax({
        url:
            base_url +
            "/registrations/opt/" +
            table +
            (table == "terapis" ? "?layanan=" + pID : ""),
        method: "GET",
        dataType: "json",
        success: function (data) {
            var html = `<option></option>`;
            var i;
            for (i = 0; i < data.length; i++) {
                if (table == "layanan") {
                    html +=
                        `<optgroup selected id="` +
                        data[i].id +
                        `" label="` +
                        data[i].nama +
                        `">`;
                    for (var ii = 0; ii < data[i].data.length; ii++) {
                        var harga_ =
                            `data-harga='` + data[i].data[ii].harga + `'`;
                        var selectedd_ =
                            data[i].data[ii].id === pID ? "selected" : "";

                        html +=
                            `<option ` +
                            harga_ +
                            ` alt="` +
                            data[i].nama +
                            `" value='` +
                            data[i].data[ii].id +
                            `' ` +
                            selectedd_ +
                            `>` +
                            data[i].data[ii].nama +
                            `</option>`;
                    }
                    html += `</optgroup>`;
                } else if (table != "layanan") {
                    var harga =
                        table == "paket"
                            ? `data-harga='` + data[i].harga + `'`
                            : "";
                    var selectedTrps =
                        table == "terapis"
                            ? data[i].id == pTrID
                                ? "selected"
                                : ""
                            : "";
                    var selectedPkt =
                        table == "paket"
                            ? data[i].id == pID
                                ? "selected"
                                : ""
                            : "";

                    html +=
                        `<option ` +
                        harga +
                        selectedTrps +
                        selectedPkt +
                        ` value='` +
                        data[i].id +
                        `'>` +
                        data[i].nama +
                        `</option>`;
                }
            }
            $("select#on-select-" + table + "-" + numb).html(html);
        },
        complete: function () {
            $(".select-" + table)
                .find("div#block")
                .removeClass("blocking-loading-row")
                .addClass("hide");
            $("#on-select-" + table + "-" + numb).removeAttr("disabled");
            if (table == "layanan" && pID) {
                // $("#on-select-terapis-" + numb).removeAttr("disabled");
            }

            $("select#on-select-" + table + "-" + numb).select2({
                placeholder: "Please select!",
                allowClear: true,
                theme: "bootstrap",
            });
        },
    });
}

function load_avail_layanan_on_paket(table, numb, p_id, p_serv) {
    var pID = p_id || 0;
    var pSID = p_serv || 0;
    var html = "";

    $.ajax({
        url: base_url + "/registrations/opts/" + table + "?paket=" + pID,
        method: "GET",
        dataType: "json",
        success: function (data) {
            for (var i = 0; i < data.length; i++) {
                html += `<tr>`;
                html += `<td class="text-center">` + (i + 1) + `</td>`;
                html += `<td>` + data[i].nama + `</td>`;
                html +=
                    `<td>
                            <div id="on-input-paketlayananterapis-` +
                    numb +
                    `-lay-` +
                    data[i].id +
                    `"></div>
                            <div class="input-group-sm">
                                <select name="pkt_layanan_terapis[` +
                    numb +
                    `][]" form="formRegistrasi" class="select2 form-control input-group-sm" disabled
                                    id="on-select-paketlayananterapis-` +
                    numb +
                    `-lay-` +
                    data[i].id +
                    `"></select>
                            </div>
                        </td>`;
                html += `</tr>`;

                load_avail_playanant("pegawai", numb, data[i].id, i);
            }
            $(".load-row-paketlayanan-" + numb).html(html);
        },
        always: function () {
            setTimeout(() => {
                $(".select-layanan")
                    .find("div#block")
                    .removeClass("blocking-loading-row")
                    .addClass("hide");
            }, 1000);
        },
    });
}

function form_attribut() {
    select_("member");
    $("select[name=sno_member]").select2({
        placeholder: "Please select!",
        allowClear: true,
        theme: "bootstrap",
    });

    $("em.f-input-an").delegate(
        "select[name=sno_member]",
        "change",
        function (e) {
            $(".f-new-member").html(
                '<div class="row"><div class="text-center"><em class="fa fa-spin fa-spinner"></em> loading...</div></div>'
            );
            var id = $(this).val();
            if (!id) {
                $(".f-new-member").html("");
                return false;
            }

            var token = $("input[name=_token]").val();
            $.ajax({
                url: base_url + "/registrations/member/explore",
                method: "POST",
                data: {
                    id: id,
                    _token: token,
                },
                dataType: "json",
                success: function (data) {
                    $(".f-new-member").html(f_member(data[0]));

                    var tgl_lahir = $("input[name=tanggal_lahir]");
                    $(".add-on-daterpicker").daterangepicker(
                        {
                            singleDatePicker: true,
                            autoUpdateInput: true,
                            showDropdowns: true,
                            startDate: !tgl_lahir.val()
                                ? moment()
                                : moment().add().format(tgl_lahir.val()),
                            locale: {
                                format: "DD-MM-YYYY",
                            },
                        },
                        addOn
                    );

                    select_("agama");

                    setTimeout(function () {
                        $("select[name=agama]")
                            .val(data[0].agama)
                            .trigger("change");
                    }, 1000);
                },
            });
        }
    );

    $("div.change-to-field").delegate("div.add", "click", function (e) {
        var attr_ = $(".f-input-an");
        var attr_m = $(".f-new-member");
        var chg_ = $(this);
        chg_.removeClass("bg-green add").addClass("bg-blue use");
        chg_.find("i").removeClass("fa-plus").addClass("fa-search");
        attr_.html(input_member());
        attr_m.html(f_member());
        $(".auto-nom").removeClass("hide");
        $(".auto-nom").on("click", function () {
            var token = $("input[name=_token]").val();
            $.ajax({
                url: base_url + "/registrations/member/generate",
                method: "POST",
                data: {
                    id: "new",
                    _token: token,
                },
                dataType: "json",
                success: function (data) {
                    $("input[name=ino_member]").val(data.auto);
                },
            });
        });
    });

    $("div.change-to-field").delegate("div.use", "click", function (e) {
        var attr_ = $(".f-input-an");
        var attr_m = $(".f-new-member");
        var chg_ = $(this);
        chg_.removeClass("bg-blue use").addClass("bg-green add");
        chg_.find("i").removeClass("fa-search").addClass("fa-plus");
        attr_.html(select_members());
        select_("member");
        attr_m.html("");
        $("select[name=sno_member]").select2({
            placeholder: "Please select!",
            allowClear: true,
            theme: "bootstrap",
        });
        $(".auto-nom").addClass("hide");
    });

    $("div.to-reservasi").delegate("button#show", "click", function (e) {
        var attr_ = $(this);
        attr_.attr("id", "hide");
        attr_
            .removeClass("btn-default")
            .trigger("change")
            .addClass("btn-success");
        attr_
            .find("em")
            .removeClass("fa-question-circle")
            .trigger("change")
            .addClass("fa-check-circle");
        $(".f-reservasi").html(f_reservasi());
        $(".datepicker").datepicker({
            format: "dd-mm-yyyy",
            startDate: "today",
        });
        $(".timepicker").timepicker({
            showInputs: false,
            showMeridian: false,
            timeFormat: "HH:mm:ss",
        });
        select_("lokasi");
        $("select[name=lokasi_reservasi]").select2({
            placeholder: "Please select!",
            allowClear: true,
            theme: "bootstrap",
        });
    });

    $("div.to-reservasi").delegate("button#hide", "click", function (e) {
        var attr_ = $(this);
        attr_.attr("id", "show");
        attr_
            .removeClass("btn-success")
            .trigger("change")
            .addClass("btn-default");
        attr_
            .find("em")
            .removeClass("fa-check-circle")
            .trigger("change")
            .addClass("fa-question-circle");
        $(".f-reservasi").html("");
    });
}

function select_(table, pkt) {
    var pkt_ = pkt || "";
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $.ajax({
        url: base_url + "/registrations/option",
        data: {
            table: table,
            paket_id: pkt_,
        },
        method: "POST",
        dataType: "json",
        success: function (data) {
            var html = `<option></option>`;
            var i;
            for (i = 0; i < data.length; i++) {
                var pegawai =
                    table == "member" ? data[i].no_member + ` | ` : "";
                var layanan_ =
                    table == "layanan" || table == "paket"
                        ? `data-harga="` + data[i].harga + `"`
                        : "";
                var id_ = table == "lokasi" ? data[i].cabang_id : data[i].id;
                html +=
                    `<option ` +
                    layanan_ +
                    `value='` +
                    id_ +
                    `'>` +
                    pegawai +
                    (table == "agama" || table == "room"
                        ? data[i].name
                        : data[i].nama) +
                    `</option>`;
            }
            var table_ = table == "pegawai" ? "terapis" : table;
            $("select.f-" + table_).html(html);

            if (table == "room") {
                $(".f-room").select2({
                    placeholder: "Please select!",
                    theme: "bootstrap",
                });
            }

            if (table == "agama") {
                $(".f-agama").select2({
                    placeholder: "Please select!",
                    theme: "bootstrap",
                });
            }
        },
    });
}

function load_formLeft() {
    var cont = $(".load-form-left");
    $(".display-future").addClass("blocking-content");
    cont.load(base_url + "/registrations/create?form=left", function (e, s, f) {
        if (s == "error") {
            var fls = "Gagal memuat form!";
            toastr.error(fls, "Oops!", {
                timeOut: 2000,
            });
            cont.html(fls);
        } else {
            $(".display-future").removeClass("blocking-content");
            $(".button-action").removeClass("hide");
            form_attribut();
        }
    });
}

function load_formRight(evv) {
    var cont = $(".load-form-right");
    $(".display-future").addClass("blocking-content");
    cont.load(
        base_url + "/registrations/create?form=right",
        function (e, s, f) {
            if (s == "error") {
                var fls = "Gagal memuat form!";
                toastr.error(fls, "Oops!", {
                    timeOut: 2000,
                });
                cont.html(fls);
            } else {
                $(".display-future").removeClass("blocking-content");
                $(".button-action").removeClass("hide");

                $(".f-layanan-tambahan")
                    .removeClass("hide")
                    .removeAttr("style");

                form_attribut_right();
                submit(evv);
            }
        }
    );
}

function saveIt() {
    var event = $("form#formRegistrasi")[0];

    $(".preloader").fadeIn();
    $(".display-future").addClass("blocking-content");

    var data = new FormData(event);
    var url = event.action;

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('input[name="_token"]').val(),
        },
    });

    $.ajax({
        url: url,
        data: data,
        contentType: false,
        processData: false,
        type: "POST",
        dataType: "JSON",
        success: function (data) {
            switch (data.cd) {
                case 200:
                    toastr.success(data.msg, "Success!", {
                        timeOut: 2000,
                        onHidden: function () {
                            location.reload();
                        },
                    });
                    break;
                default:
                    $(".preloader").fadeOut();
                    $(".display-future").removeClass("blocking-content");
                    toastr.warning(data.msg, "Peringatan!", {
                        timeOut: 2000,
                    });
                    break;
            }
        },
        error: function () {
            var timer = 5; // timer in seconds
            (function customSwal() {
                swal({
                    title: "Kesalahan sistem!",
                    text:
                        "Sistem error, menutup otomatis pada " +
                        timer +
                        " detik !",
                    timer: timer * 1000,
                    button: false,
                    icon: base_url + "/images/icons/loader.gif",
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                }).then(() => {
                    setTimeout(function () {
                        $(".preloader").fadeOut();
                        $(".display-future").removeClass("blocking-content");
                        swal.close();
                    }, 1000);
                });

                if (timer) {
                    timer--;
                    if (timer > 0) {
                        setTimeout(customSwal, 1000);
                    }
                }
            })();
        },
    });
}

function savePrint(evv) {
    var event = $("form#formRegistrasi")[0];

    $(".preloader").fadeIn();
    $(".display-future").addClass("blocking-content");

    var data = new FormData(event);
    var url = event.action;

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('input[name="_token"]').val(),
        },
    });

    $.ajax({
        url: url,
        data: data,
        contentType: false,
        processData: false,
        type: "POST",
        dataType: "JSON",
        success: function (data) {
            switch (data.cd) {
                case 200:
                    toastr.success(data.msg, "Success!", {
                        timeOut: 2000,
                        onHidden: function () {
                            toPrint(evv, "on-save", "print-act");
                            setTimeout(() => {
                                $(".modal").modal("hide");
                                $(".preloader").fadeOut();
                                $(".display-future").removeClass(
                                    "blocking-content"
                                );
                            }, 1000);
                        },
                    });
                    break;
                default:
                    $(".preloader").fadeOut();
                    $(".display-future").removeClass("blocking-content");
                    toastr.warning(data.msg, "Peringatan!", {
                        timeOut: 2000,
                    });
                    break;
            }
        },
        error: function () {
            var timer = 5; // timer in seconds
            (function customSwal() {
                swal({
                    title: "Kesalahan sistem!",
                    text:
                        "Sistem error, menutup otomatis pada " +
                        timer +
                        " detik !",
                    timer: timer * 1000,
                    button: false,
                    icon: base_url + "/images/icons/loader.gif",
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                }).then(() => {
                    setTimeout(function () {
                        $(".preloader").fadeOut();
                        $(".display-future").removeClass("blocking-content");
                        swal.close();
                    }, 1000);
                });

                if (timer) {
                    timer--;
                    if (timer > 0) {
                        setTimeout(customSwal, 1000);
                    }
                }
            })();
        },
    });
}

function submit(evv) {
    var saveIts = $("form#formRegistrasi").closest("form")[0].saveit;
    $(saveIts).on("click", function (e) {
        saveIt();
        return false;
    });

    var savePrints = $("form#formRegistrasi").closest("form")[0].saveprint;
    $(savePrints).on("click", function (e) {
        savePrint(evv);
        return false;
    });
}

var mousetimeout;
var reload_data_active = false;
var idletime = 5;

function getIndoDate(date, hours, row, this_) {
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

    var indTime = new Date(date).toLocaleString("en-US", {
        timeZone: "Asia/Jakarta",
    });

    var indTimeNow = new Date().toLocaleString("en-US", {
        timeZone: "Asia/Jakarta",
    });

    var elemnt = new Date(indTime);
    var hari_ = elemnt.getDay();
    var tanggal_ = elemnt.getDate();
    var bulan_ = elemnt.getMonth();
    var tahun_ = elemnt.getFullYear();

    var jam_ = elemnt.getHours();
    var menit_ = elemnt.getMinutes();

    var hari = _hari[hari_];
    var tanggal = tanggal_;
    var bulan = _bulan[bulan_];
    var tahun = tahun_;

    var hourss = new Date(elemnt.getTime() + 1 * 60000);
    var jam__ = hourss.getHours();
    var menit__ = hourss.getMinutes();
    var hours__ =
        (jam__.toString().length == 1 ? "0" + jam__ : jam__) +
        ":" +
        (menit__.toString().length == 1 ? "0" + menit__ : menit__);

    var hourss_ = new Date(indTimeNow);
    var jamm__ = hourss_.getHours();
    var menitt__ = hourss_.getMinutes();
    var hourss__ =
        (jamm__.toString().length == 1 ? "0" + jamm__ : jamm__) +
        ":" +
        (menitt__.toString().length == 1 ? "0" + menitt__ : menitt__);

    var kaleNow = new Date().toJSON().slice(0, 10);
    var addJam = hours
        ? ' <em class="btn btn-' +
          (elemnt.toJSON().slice(0, 10) == kaleNow && hours__ > hourss__
              ? "info"
              : elemnt.toJSON().slice(0, 10) < kaleNow
              ? "default"
              : elemnt.toJSON().slice(0, 10) > kaleNow
              ? "info"
              : row.print_act == 1
              ? "warning"
              : "danger") +
          ' btn-xs text-bold">' +
          (jam_.toString().length == 1 ? "0" + jam_ : jam_) +
          ":" +
          (menit_.toString().length == 1 ? "0" + menit_ : menit_) +
          "</em>"
        : "";

    setInterval(function () {
        autoRefreshPage(elemnt, row, this_);
    }, 5000);

    return hari + ", " + tanggal + " " + bulan + " " + tahun + addJam;
}

function autoRefreshPage(el, row, this_) {
    var indTime = new Date().toLocaleString("en-US", {
        timeZone: "Asia/Jakarta",
    });

    var elemnt_ = new Date(indTime);

    var tanggal_ = elemnt_.getDate();
    var bulan_ = elemnt_.getMonth() + 1;
    var tahun_ = elemnt_.getFullYear();
    var date_ = tahun_ + "-" + bulan_ + "-" + tanggal_;

    var jam_ = elemnt_.getHours();
    var menit_ = elemnt_.getMinutes();
    var hours_ =
        (jam_.toString().length == 1 ? "0" + jam_ : jam_) +
        ":" +
        (menit_.toString().length == 1 ? "0" + menit_ : menit_);

    var tanggal__ = el.getDate();
    var bulan__ = el.getMonth() + 1;
    var tahun__ = el.getFullYear();
    var date__ = tahun__ + "-" + bulan__ + "-" + tanggal__;

    var hourss = new Date(el.getTime() + 1 * 60000);
    var jam__ = hourss.getHours();
    var menit__ = hourss.getMinutes();
    var hours__ =
        (jam__.toString().length == 1 ? "0" + jam__ : jam__) +
        ":" +
        (menit__.toString().length == 1 ? "0" + menit__ : menit__);

    // var btnAktf = (row.button_aktif == 'undefined' ? 'kosong' : 'ada');
    if (date_ == date__) {
        if (hours__ < hours_) {
            // if (btnAktf == 'kosong') {
            loadSu(this_);
            // }
        }
    }
}

function loadSu(this_) {
    $(document).mousemove(function () {
        clearTimeout(mousetimeout);
        if (reload_data_active) {
            stop_automatic();
        }
        mousetimeout = setTimeout(function () {
            show_data_active(this_);
        }, 1000 * idletime); // 5 secs
    });
}

function data_attribut() {
    var ElemtStart = rangeDate(0, "back");
    var ElemtEnd = rangeDate(1, "back");

    var dateranges =
        (ElemtStart ? "?starts=" + ElemtStart : "") +
        (ElemtEnd ? "&ends=" + ElemtEnd : "");

    var dTable = $("#data-table-view").DataTable({
        scrollCollapse: true,
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url:
                base_url +
                "/monitoring/order/json" +
                dateranges +
                (dateranges ? "&" : "?") +
                "statuses=2",
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
                data: "nama",
                name: "nama",
                searchable: false,
                className: "td-height-img",
            },
            {
                data: "no_transaksi",
                name: "no_transaksi",
                className: "td-height-img",
            },
            {
                data: "waktu_reservasi",
                name: "waktu_reservasi",
                className: "text-right",
                searchable: false,
                render: function (data, type, row) {
                    return data
                        ? getIndoDate(
                              data,
                              "jam",
                              row,
                              $("#data-table-view").DataTable()
                          )
                        : "-";
                },
            },
            {
                data: "total_biaya",
                name: "total_biaya",
                className: "td-height-img text-right",
                searchable: false,
                render: convertRupiah,
            },
            {
                data: "hutang_biaya",
                name: "hutang_biaya",
                className: "td-height-img text-right",
                searchable: false,
                render: convertRupiah,
            },
            {
                data: "status_text",
                name: "status_text",
                searchable: true,
                orderable: false,
                className: "text-center",
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
        columnDefs: [
            {
                searchable: true,
                targets: 2,
            },
        ],
    });

    $("select[name=data-table-view_length]").on("change", function () {
        dTable.ajax.reload();
    });

    $("input[type=search]").on("input", function (e) {
        $(this).prop("placeholder", "Cari Nomor Transaksi...");
        dTable.ajax.reload();
    });

    $("input[type=search]").prop("placeholder", "Cari Nomor Transaksi...");

    var html_ = $(
        '<select id="newFilterStatusOrder" class="form-control input-sm bg-change-select"></select>'
    );
    html_.append('<option value="">Semua</option>');
    html_.append('<option value="2" selected>Aktif</option>');
    html_.append('<option value="4">Non Aktif</option>');
    html_.append('<option value="1">Batal</option>');

    $("#data-table-view_filter").append(html_);

    load_formEdit();
    load_Activation();
    load_printCase();
    load_sendPembayaranCase();
    load_voidPembayaranCase();

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

    $("#newFilterStatusOrder").on("change", function () {
        getStatus_field_daterange();
    });
}

function fill_field_daterange(picker) {
    $("input[name='berlaku_dari']")
        .val(picker.startDate.format("DD-MM-YYYY"))
        .attr("data-date-start", picker.startDate.format("YYYY-MM-DD"))
        .trigger("change");
    $("input[name='berlaku_sampai']")
        .val(picker.endDate.format("DD-MM-YYYY"))
        .attr("data-date-end", picker.endDate.format("YYYY-MM-DD"))
        .trigger("change");
    var statusOrder = !$("#newFilterStatusOrder").val()
        ? ""
        : $("#newFilterStatusOrder").val();

    $("#data-table-view")
        .DataTable()
        .ajax.url(
            base_url +
                "/monitoring/order/json?starts=" +
                picker.startDate.format("YYYY-MM-DD") +
                "&ends=" +
                picker.endDate.format("YYYY-MM-DD") +
                "&statuses=" +
                statusOrder
        )
        .load();
}

function getStatus_field_daterange() {
    var dateStart = $(".tinggi-filter-range-date").find(
        "input[name=berlaku_dari]"
    )[0].dataset.dateStart;
    var dateEnd = $(".tinggi-filter-range-date").find(
        "input[name=berlaku_sampai]"
    )[0].dataset.dateEnd;
    var statusOrder = !$("#newFilterStatusOrder").val()
        ? ""
        : $("#newFilterStatusOrder").val();

    $("#data-table-view")
        .DataTable()
        .ajax.url(
            base_url +
                "/monitoring/order/json?starts=" +
                (dateStart ? dateStart : "") +
                "&ends=" +
                (dateEnd ? dateEnd : "") +
                "&statuses=" +
                statusOrder
        )
        .load();
}

function remove_field_daterange() {
    $("input[name='berlaku_dari']").val("").removeAttr("data-date-start");
    $("input[name='berlaku_sampai']").val("").removeAttr("data-date-end");
    var statusOrder = !$("#newFilterStatusOrder").val()
        ? ""
        : $("#newFilterStatusOrder").val();

    $("#data-table-view")
        .DataTable()
        .ajax.url(base_url + "/monitoring/order/json?statuses=" + statusOrder)
        .load();
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

function show_data_active(this_) {
    reload_data_active = true;
    load_automatic(this_);
}

function stop_automatic() {
    reload_data_active = false;
}

function load_automatic(this_) {
    if (reload_data_active) {
        var target = base_url + "/monitoring/order/reload?data=after";
        $.ajax({
            url: target,
            type: "GET",
            success: function (data) {
                toastr.success(data.msg, "Updated!", {
                    timeOut: 2000,
                });
            },
            complete: function () {
                var pages = this_.page.info();
                this_.ajax.reload().page(pages.page).draw("page");
            },
        });
    }
}

$(document).ready(function () {
    $("input[name=berlaku_dari]")
        .val(rangeDate(0))
        .attr("data-date-start", rangeDate(0, "back"));
    $("input[name=berlaku_sampai]")
        .val(rangeDate(1))
        .attr("data-date-end", rangeDate(1, "back"));

    load_data();
});
