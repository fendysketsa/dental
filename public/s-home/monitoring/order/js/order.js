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

function load_formPeriksa() {
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
                $(".load-form-modal-periksa")
                    .closest(".modal-dialog")
                    .find("form")
                    .remove();

                $(".load-form-modal-periksa").html(result);
                $(".modal-title").html(
                    '<em class="fa fa-pencil-square-o"></em> Form Periksa Rekam Medik'
                );

                savePeriksa(event);

                var inputId = $("input[name=id]");

                load_formLeftPeriksa(inputId);

                $("#f-load-rekam-medik-periksa-gigi").html(
                    '<div class="text-left"><em class="fa fa-spin fa-spinner"></em> loading...</div>'
                );

                $("#f-load-ubah").html(
                    '<div class="text-left"><em class="fa fa-spin fa-spinner"></em> loading...</div>'
                );

                setTimeout(function () {
                    loadRekamMedikPeriksa("form-load");
                }, 1500);

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
                                $(".load-informasi-right-periksa").html(
                                    content_info_member(data[0])
                                );

                                $(".load-informasi-right-periksa")
                                    .find(".next-one-info")
                                    .prop("type", "button");

                                $(".load-informasi-right-periksa").delegate(
                                    ".next-one-info",
                                    "click",
                                    function () {
                                        $("#f-load-rekam-medik-periksa")
                                            .removeClass("show")
                                            .addClass("hide");

                                        $("#f-load-rekam-medik-periksa-gigi")
                                            .removeClass("hide")
                                            .addClass("show");

                                        $("#f-load-ubah")
                                            .removeClass("show")
                                            .addClass("hide");

                                        $("li.st-2").addClass("active_order");

                                        $(this)
                                            .removeClass("next-one-info")
                                            .addClass("next-two-info");

                                        setTimeout(function () {
                                            $(".load-informasi-right-periksa")
                                                .find(".next-two-info")
                                                .prop("type", "button");
                                        }, 500);
                                    }
                                );

                                $(".load-informasi-right-periksa").delegate(
                                    ".next-two-info",
                                    "click",
                                    function () {
                                        $("#f-load-rekam-medik-periksa")
                                            .removeClass("show")
                                            .addClass("hide");

                                        $("#f-load-rekam-medik-periksa-gigi")
                                            .removeClass("show")
                                            .addClass("hide");

                                        $("#f-load-ubah")
                                            .removeClass("hide")
                                            .addClass("show");

                                        $("li.st-3").addClass("active_order");

                                        $(this)
                                            .removeClass("next-two-info")
                                            .addClass("next-three-info");

                                        $(this).html(
                                            `<em class="fa fa-envelope"></em> Simpan`
                                        );

                                        setTimeout(function () {
                                            $(".load-informasi-right-periksa")
                                                .find(".next-three-info")
                                                .prop("type", "submit");
                                        }, 500);
                                    }
                                );

                                $("#f-load-rekam-medik-periksa").delegate(
                                    ".on-label-click",
                                    "click",
                                    function () {
                                        var num = $(this).data("ck");
                                        var pos = $(this).data("ck-on");
                                        var desc = $(this).data("ck-desc");

                                        $(".info-small-" + pos).html(
                                            `<em class="fa fa-info-circle"></em> ` +
                                                desc.split("\n")[num]
                                        );
                                    }
                                );
                            }, 500);
                        },
                    });
                }, 2500);
            },
            error: function () {
                toastr.error("Gagal mengambil data", "Oops!", {
                    timeOut: 2000,
                });
            },
        });
    });
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
                            rL <= inputId.data("category").length;
                            rL++
                        ) {
                            $(".load-row-layanan").append(load_row_layanan(rL));
                        }
                        $(".load-row-layanan").append(load_row_layanan);

                        for (
                            var rL = 1;
                            rL <= inputId.data("layanan-tambahan").length;
                            rL++
                        ) {
                            $(".load-row-layanan-tambahan").append(
                                load_row_layanan_tambahan(rL)
                            );
                        }
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

                        $("input[name=tanggal_next]")
                            .val(inputId.data("nexdate"))
                            .trigger("change");

                        setTimeout(function () {
                            $(
                                ".add-on-daterpicker-next-treatment"
                            ).daterangepicker(
                                {
                                    drops: "up",
                                    singleDatePicker: true,
                                    autoUpdateInput: true,
                                    showDropdowns: true,
                                    startDate: !$(
                                        "input[name=tanggal_next]"
                                    ).val()
                                        ? moment()
                                        : moment()
                                              .add()
                                              .format(
                                                  $(
                                                      "input[name=tanggal_next]"
                                                  ).val()
                                              ),
                                    minDate: moment(),
                                    locale: {
                                        format: "DD-MM-YYYY",
                                    },
                                },
                                addOnNex
                            );
                        }, 500);

                        $("input[name=jumlah_orang]")
                            .val(inputId.data("jum_org"))
                            .trigger("change");

                        $("select[name=dokter]")
                            .val(inputId.data("dokter"))
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
                            $(".datepicker-reservation").datepicker({
                                minDate: "0",
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
}

function content_rekam_medik_periksa(data) {
    var html = ``;
    var newHtml = ``;

    var ccK = $("input[name=id]").data("rekam-medik");

    if (data) {
        $.each(data, function (e, i) {
            var typeInput = !i.set_input ? "checkbox" : "radio";
            var plcInput = !i.more_input_placeholder
                ? ""
                : i.more_input_placeholder;
            var Option = "";

            var labelInput = !i.more_input_label
                ? ""
                : `<small id="emailHelp" class="form-text info-small-` +
                  i.id +
                  ` text-info"><em class="fa fa-info-circle"></em> ` +
                  i.more_input_label.split("\n")[0] +
                  `</small>`;

            var moreInput = !i.more_input
                ? ""
                : "<input type='text' class='form-control mt-5 rek-more-name-" +
                  i.id +
                  "' form='formPeriksa' placeholder='" +
                  plcInput +
                  "' name='rekam_more[" +
                  i.id +
                  "]'>";

            if (i.option) {
                $.each(i.option.split("\n"), function (f, g) {
                    var selctTd =
                        typeInput == "radio" && f == 0 ? "checked" : "";

                    Option +=
                        `<input ` +
                        (typeInput == "radio"
                            ? "class='on-label-click rek-name-" +
                              i.id +
                              "-" +
                              g.split(" ").join("-").trim() +
                              "'"
                            : "class='rek-name-" +
                              i.id +
                              "-" +
                              g.split(" ").join("-").trim() +
                              "'") +
                        ` style="margin-right:10px;" ` +
                        selctTd +
                        ` type="` +
                        typeInput +
                        (typeInput == "radio"
                            ? `" name="rekam[` + i.id + `]" `
                            : `" name="rekam[` + i.id + `][` + f + `]" `) +
                        `value="` +
                        g +
                        `" data-ck="` +
                        f +
                        `" data-ck-on="` +
                        i.id +
                        `" data-ck-desc="` +
                        (!i.more_input_label ? "" : i.more_input_label) +
                        `" form="formPeriksa"> <label style="margin-right:20px;" for="` +
                        e +
                        `">` +
                        g +
                        `</label>`;

                    setTimeout(function () {
                        $("input.rek-more-name-" + i.id).val(ccK[i.id].more);

                        if (ccK[i.id].name.split("\n").length > 1) {
                            $.each(ccK[i.id].name.split("\n"), function (s, b) {
                                if (
                                    i.id == ccK[i.id].position &&
                                    b.split(" ").join("-").trim() ==
                                        g.split(" ").join("-").trim()
                                ) {
                                    $(
                                        "input.rek-name-" +
                                            i.id +
                                            "-" +
                                            b.split(" ").join("-").trim()
                                    ).prop("checked", true);
                                }
                            });
                        } else if (
                            i.id == ccK[i.id].position &&
                            ccK[i.id].name.split(" ").join("-").trim() ==
                                g.split(" ").join("-").trim()
                        ) {
                            $(
                                "input.rek-name-" +
                                    i.id +
                                    "-" +
                                    g.split(" ").join("-").trim()
                            ).prop("checked", true);

                            var num = $(
                                "input.rek-name-" + i.id + "-" + g.trim()
                            ).data("ck");
                            var pos = $(
                                "input.rek-name-" + i.id + "-" + g.trim()
                            ).data("ck-on");
                            var desc = $(
                                "input.rek-name-" + i.id + "-" + g.trim()
                            ).data("ck-desc");

                            $(".info-small-" + pos).html(
                                `<em class="fa fa-info-circle"></em> ` +
                                    (!desc ? "" : desc.split("\n")[num])
                            );
                        }
                    }, 500);
                });
            }

            newHtml +=
                `<div class="form-group">
                            <label>` +
                i.nama +
                `</label>
                            <div class="input-group input-group-sm date">` +
                Option +
                `
                            </div>
` +
                labelInput +
                moreInput +
                `
                        </div>`;
        });
    }

    html +=
        `<div class="col-md-12">
                    <div class="row box box-header with-border bg-default display-future">
                        <h3 class="box-title"><em class="fa fa-pencil"></em> Data Rekam Medik</h3>
                    </div>
                    <div class="box-body clean-sheet on-dutty-off">
                        ` +
        newHtml +
        `
                    </div>
                </div>`;

    return html;
}

function content_rekam_medik(data) {
    var html = ``;
    var newHtml = ``;

    var ccK = $("input[name=id]").data("rekam-medik");

    if (data) {
        $.each(data, function (e, i) {
            var typeInput = !i.set_input ? "checkbox" : "radio";
            var plcInput = !i.more_input_placeholder
                ? ""
                : i.more_input_placeholder;
            var Option = "";

            var labelInput = !i.more_input_label
                ? ""
                : `<small id="emailHelp" class="form-text info-small-` +
                  i.id +
                  ` text-info"><em class="fa fa-info-circle"></em> ` +
                  i.more_input_label.split("\n")[0] +
                  `</small>`;

            var moreInput = !i.more_input
                ? ""
                : "<input type='text' class='form-control mt-5 rek-more-name-" +
                  i.id +
                  "' form='formRegistrasi' placeholder='" +
                  plcInput +
                  "' name='rekam_more[" +
                  i.id +
                  "]'>";

            if (i.option) {
                $.each(i.option.split("\n"), function (f, g) {
                    var selctTd =
                        typeInput == "radio" && f == 0 ? "checked" : "";

                    Option +=
                        `<input ` +
                        (typeInput == "radio"
                            ? "class='on-label-click rek-name-" +
                              i.id +
                              "-" +
                              g.split(" ").join("-").trim() +
                              "'"
                            : "class='rek-name-" +
                              i.id +
                              "-" +
                              g.split(" ").join("-").trim() +
                              "'") +
                        ` style="margin-right:10px;" ` +
                        selctTd +
                        ` type="` +
                        typeInput +
                        (typeInput == "radio"
                            ? `" name="rekam[` + i.id + `]" `
                            : `" name="rekam[` + i.id + `][` + f + `]" `) +
                        `value="` +
                        g +
                        `" data-ck="` +
                        f +
                        `" data-ck-on="` +
                        i.id +
                        `" data-ck-desc="` +
                        (!i.more_input_label ? "" : i.more_input_label) +
                        `" form="formRegistrasi"> <label style="margin-right:20px;" for="` +
                        e +
                        `">` +
                        g +
                        `</label>`;

                    setTimeout(function () {
                        $("input.rek-more-name-" + i.id).val(ccK[i.id].more);

                        if (ccK[i.id].name.split("\n").length > 1) {
                            $.each(ccK[i.id].name.split("\n"), function (s, b) {
                                if (
                                    i.id == ccK[i.id].position &&
                                    b.split(" ").join("-").trim() ==
                                        g.split(" ").join("-").trim()
                                ) {
                                    $(
                                        "input.rek-name-" +
                                            i.id +
                                            "-" +
                                            b.split(" ").join("-").trim()
                                    ).prop("checked", true);
                                }
                            });
                        } else if (
                            i.id == ccK[i.id].position &&
                            ccK[i.id].name.split(" ").join("-").trim() ==
                                g.split(" ").join("-").trim()
                        ) {
                            $(
                                "input.rek-name-" +
                                    i.id +
                                    "-" +
                                    g.split(" ").join("-").trim()
                            ).prop("checked", true);

                            var num = $(
                                "input.rek-name-" + i.id + "-" + g.trim()
                            ).data("ck");
                            var pos = $(
                                "input.rek-name-" + i.id + "-" + g.trim()
                            ).data("ck-on");
                            var desc = $(
                                "input.rek-name-" + i.id + "-" + g.trim()
                            ).data("ck-desc");

                            $(".info-small-" + pos).html(
                                `<em class="fa fa-info-circle"></em> ` +
                                    (!desc ? "" : desc.split("\n")[num])
                            );
                        }
                    }, 500);
                });
            }

            newHtml +=
                `<div class="form-group">
                            <label>` +
                i.nama +
                `</label>
                            <div class="input-group input-group-sm date">` +
                Option +
                `
                            </div>
` +
                labelInput +
                moreInput +
                `
                        </div>`;
        });
    }

    html +=
        `<div class="col-md-12">
                    <div class="row box box-header with-border bg-default display-future">
                        <h3 class="box-title"><em class="fa fa-pencil"></em> Data Rekam Medik</h3>
                    </div>
                    <div class="box-body clean-sheet on-dutty-off">
                        ` +
        newHtml +
        `
                    </div>
                </div>`;

    return html;
}

function loadRekamMedikPeriksa(ck) {
    $.ajax({
        url: base_url + "/registrations/rekam-medik/explore",
        method: "GET",
        dataType: "json",
        success: function (data) {
            setTimeout(() => {
                if (ck && ck == "form-load") {
                    setTimeout(function () {
                        $("#f-load-rekam-medik-periksa").removeClass("m-b-2");
                        $("#f-load-rekam-medik-periksa").html(
                            content_rekam_medik_periksa(data)
                        );

                        if (ck && ck == "form-load") {
                            $(".clean-sheet").removeClass("on-dutty-off");
                        }
                    }, 1500);
                } else {
                    $("#f-load-rekam-medik-periksa").html(
                        content_rekam_medik_periksa(data)
                    );
                }
            }, 500);
        },
    });
}

function loadRekamMedik(ck) {
    $.ajax({
        url: base_url + "/registrations/rekam-medik/explore",
        method: "GET",
        dataType: "json",
        success: function (data) {
            setTimeout(() => {
                if (ck && ck == "form-load") {
                    $(".load-form-right").prepend(
                        '<div id="f-load-rekam-medik" class="m-b-2"><em class="fa fa-spin fa-spinner"></em> Loading...</div>'
                    );

                    setTimeout(function () {
                        $("#f-load-rekam-medik").removeClass("m-b-2");
                        $("#f-load-rekam-medik").html(
                            content_rekam_medik(data)
                        );

                        if (ck && ck == "form-load") {
                            $(".clean-sheet").removeClass("on-dutty-off");
                        }
                    }, 1500);
                } else {
                    $("#f-load-rekam-medik").html(content_rekam_medik(data));
                }

                $("#f-load-rekam-medik").delegate(
                    ".on-label-click",
                    "click",
                    function () {
                        var num = $(this).data("ck");
                        var pos = $(this).data("ck-on");
                        var desc = $(this).data("ck-desc");

                        $(".info-small-" + pos).html(
                            `<em class="fa fa-info-circle"></em> ` +
                                desc.split("\n")[num]
                        );
                    }
                );
            }, 500);
        },
    });
}

function content_info_member(data) {
    var html = ``;

    html +=
        `<div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                    ` +
        getImg(data.foto) +
        `</div>
                </div>
                <div class="col-md-12">
                    <h4 class="text-center">
                    ` +
        data.nama +
        `
                    </h4>
                </div>
                <div class="col-md-12 mt-10">
                    <p><em class="fa fa-envelope mr-2"></em> ` +
        data.email +
        `</p>
                    <p><em class="fa fa-phone mr-2"></em> ` +
        data.telepon +
        `</p>
                    <hr class="bdr-infor">
                </div>

                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header with-border bg-info">
                            <i class="fa fa-info"></i>
                            <h3 class="box-title">Data Pribadi</h3>
                        </div>
                        <div class="box-body">
                            <div>
                                <p class="text-muted">Tempat Lahir</p>
                                <p class="text-bold">` +
        data.tempat_lahir +
        `</p>
                            </div>
                            <div>
                                <p class="text-muted">Tanggal Lahir</p>
                                <p class="text-bold">` +
        data.tgl_lahir +
        `</p>
                            </div>
                            <div>
                                <p class="text-muted">Kepala Keluarga (KK)</p>
                                <p class="text-bold">` +
        data.nik +
        `</p>
                            </div>
                            <div>
                                <p class="text-muted">Alamat / Domisili</p>
                                <p class="text-bold">` +
        data.alamat +
        "<br>" +
        data.domisili +
        `</p>
                            </div>
                            <div>
                                <p class="text-muted">Agama</p>
                                <p class="text-bold">` +
        convertAgama(data.agama) +
        `</p>
                            </div>
                            <div>
                                <p class="text-muted">Status</p>
                                <p class="text-bold">` +
        data.status_member +
        `</p>
                            </div>
                        </div>
                        <div class="button-action box-footer bg-gray-light">
                            <div class="row">
                                <div class="col-md-6 col-xs-6 col-sm-6">
                                    <a role="button" data-dismiss="modal"
                                        class="btn btn-warning col-md-12 col-xs-12 col-sm-12 cancel-form"><em
                                            class="fa fa-undo"></em>
                                        Batal</a>
                                </div>
                                <div class="col-md-6 col-xs-6 col-sm-6">
                                    <button name="next_one" type="button"
                                        class="btn btn-info col-md-12 col-xs-12 col-sm-12 next-one-info" form="formPeriksa"><em
                                            class="fa fa-arrow-right"></em> Lanjutkan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

    return html;
}

function convertAgama(agama) {
    var hearth;

    switch (agama) {
        case "1":
            hearth = "Islam";
            break;
        case "2":
            hearth = "Kristen";
            break;
        case "3":
            hearth = "Katholik";
            break;
        case "4":
            hearth = "Hindu";
            break;
        case "5":
            hearth = "Budha";
            break;
        default:
            hearth = "Nothing!";
    }

    return hearth;
}

function imgError(image) {
    image.onerror = "";
    image.src = "/images/brokenimage.jpg";
    image.alt = "Images corrupt!";
    image.title = "Images corrupt!";
    return true;
}

function getImg(data, type, full, meta) {
    var img = !data
        ? "/images/noimage.jpg"
        : "/storage/master-data/member/uploads/" + data;
    return (
        '<img onerror="imgError(this);" style="border-radius:50%;" width="90" height="90" src="' +
        base_url +
        img +
        '">'
    );
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
            printer.align("center").text("C-MORE - Make Up");

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
                            <input readonly type="text" name="tgl_reservasi" class="form-control datepicker-reservation" placeholder="Tanggal..." form="formRegistrasi">
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
        `" class="form-control"  ` +
        (data.email ? "readonly" : "") +
        ` placeholder="Email..." form="formRegistrasi">
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

    $(".add-row-layanan-periksa").on("click", function () {
        $(".load-row-layanan-periksa").append(load_row_layanan_periksa);
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

                        $(trs[e])
                            .find("td.input-price-custom")
                            .find("input")
                            .removeAttr("id")
                            .attr("id", "on-select-price-custom-" + (e + 1));
                    });

                    loadTotal();
                });
        }
    );

    $("table tbody.load-row-layanan-periksa").delegate(
        "tr > td > em.remove-row-layanan-periksa",
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
                        ".load-row-layanan-periksa tr.n-f-layanan-periksa"
                    );
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

                        $(trs[e])
                            .find("td.input-price-custom")
                            .find("input")
                            .removeAttr("id")
                            .attr("id", "on-select-price-custom-" + (e + 1));
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

    $("table tbody.load-row-layanan-tambahan-periksa").delegate(
        "tr > td > em.remove-row-layanan-tambahan-periksa",
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
                        ".load-row-layanan-tambahan-periksa tr.n-f-layanan-tambahan-periksa"
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
    select_("dokter");
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

    var cekTh = thisElem.closest(".load-form-table-layanan").find(".opt-harga");

    var html = `<tr class="n-f-layanan">`;
    html +=
        `<td class="nom-layanan td-height-img text-center">` + numb + `</td>`;

    html +=
        `<td class="select-categorys td-height-img">
                <div class="input-group-sm">
                    <select name="category[]" required form="formRegistrasi" class="select2 form-control input-group-sm" disabled id="on-select-category-` +
        numb +
        `"></select>
                </div>
            </td>`;

    html +=
        `<td class="select-layanan td-height-img">
                <div id="block" class="blocking-loading-row"><em class="fa fa-spinner fa-spin"></em> Loading...</div>
                <div class="input-group-sm">
                    <select name="layanan[]" required form="formRegistrasi" class="select2 form-control input-group-sm" disabled id="on-select-layanan-` +
        numb +
        `"></select>
                </div>
            </td>`;

    if (cekTh) {
        html +=
            `<td class="select-terapis input-price-custom td-height-img">
                    <div class="input-group-sm">
                        <input name="harga_custom[]" placeholder="Harga" type="rupiah" form="formRegistrasi" class="select2 form-control input-group-sm" disabled id="on-select-price-custom-` +
            numb +
            `"></select>
                    </div>
                </td>`;
    }

    html += `<td class="td-height-img text-center"><em class="fa fa-times remove-row-layanan text-danger"></em></td>`;
    html += `</tr>`;

    setTimeout(() => {
        if (idLayanan) {
            load_avail_layanan(
                "layanan",
                numb,
                $("input[name=id]").data("layanan")[idLayanan - 1]
            );

            load_avail_category(
                "category",
                numb,
                $("input[name=id]").data("category")[idLayanan - 1]
            );

            load_avail_layanan(
                "terapis",
                numb,
                $("input[name=id]").data("layanan")[idLayanan - 1],
                $("input[name=id]").data("terapis")[idLayanan - 1]
            );

            var DataPrcCus = $("input[name=id]").data("price-layanan")[
                idLayanan - 1
            ];

            $("#on-select-price-custom-" + numb).val(convertRupiah(DataPrcCus));

            onInputRupiah();

            setTimeout(() => {
                // $("#on-select-layanan-" + idLayanan).attr("disabled", true);

                $("#on-select-price-custom-" + numb).removeAttr("disabled");
                $("#inpt-select-" + numb).val(
                    $("#on-select-layanan-" + idLayanan).val()
                );
                $("#inpt-select-cat-" + numb).val(
                    $("#on-select-category-" + idLayanan).val()
                );
            }, 2000);
        } else {
            load_avail_layanan("layanan", numb);
            load_avail_category("category", numb);
        }

        //ganti delegate
        $(".load-row-layanan").delegate(
            "#on-select-layanan-" + numb,
            "change",
            function (e) {
                var layId = $(this).val();
                loadTotal(layId);
                if (layId) {
                    // $("#on-select-terapis-" + numb).removeAttr("disabled");

                    var prcs = $(
                        "#on-select-layanan-" + numb + " option:selected"
                    ).data("harga");

                    $("#on-select-price-custom-" + numb).removeAttr("disabled");
                    $("#on-select-price-custom-" + numb).val(
                        convertRupiah(prcs)
                    );
                    // load_avail_layanan("terapis", numb, layId);
                } else {
                    // var trps = $("#on-select-terapis-" + numb);
                    // trps.attr("disabled", true);
                    // trps.val("").trigger("change");

                    var prc_cus = $("#on-select-price-custom-" + numb);
                    prc_cus.attr("disabled", true);
                    prc_cus.val("").trigger("change");
                }
            }
        );
    }, 500);

    return html;
}

function load_row_layanan_periksa(idLayanan, idTerapis) {
    var thisElem = $(".n-f-layanan-periksa");
    var numb = thisElem.length + 1;

    var cekTh = thisElem
        .closest(".load-form-table-layanan-periksa")
        .find(".opt-harga");

    var idKat = $("input[name=id]").data("category");

    var html = `<tr class="n-f-layanan-periksa">`;
    html +=
        `<td class="nom-layanan td-height-img text-center">` + numb + `</td>`;

    html +=
        `<td class="select-categorys td-height-img">
                <div class="input-group-sm">
                    <select name="category[]" form="formPeriksa" class="select2 form-control input-group-sm" required disabled id="on-select-category-` +
        numb +
        `"></select>

                </div>
            </td>`;

    html +=
        `<td class="select-layanan td-height-img">
                <div id="block" class="blocking-loading-row"><em class="fa fa-spinner fa-spin"></em> Loading...</div>
                <div class="input-group-sm">
                    <select name="layanan[]" form="formPeriksa" class="select2 form-control input-group-sm" required disabled id="on-select-layanan-` +
        numb +
        `"></select>
                </div>
            </td>`;

    if (cekTh) {
        html +=
            `<td class="select-terapis input-price-custom td-height-img">
                    <div class="input-group-sm">
                        <input name="harga_custom[]" placeholder="Harga" type="rupiah" form="formPeriksa" class="select2 form-control input-group-sm" disabled id="on-select-price-custom-` +
            numb +
            `"></select>
                    </div>
                </td>`;
    }

    html += `<td class="td-height-img text-center"><em class="fa fa-times remove-row-layanan-periksa text-danger"></em></td>`;
    html += `</tr>`;

    setTimeout(() => {
        if (idLayanan) {
            load_avail_layanan(
                "layanan",
                numb,
                $("input[name=id]").data("layanan")[idLayanan - 1]
            );

            load_avail_category(
                "category",
                numb,
                $("input[name=id]").data("category")[idLayanan - 1]
            );

            load_avail_layanan(
                "terapis",
                numb,
                $("input[name=id]").data("layanan")[idLayanan - 1],
                $("input[name=id]").data("terapis")[idLayanan - 1]
            );

            var DataPrcCus = $("input[name=id]").data("price-layanan")[
                idLayanan - 1
            ];

            $("#on-select-price-custom-" + numb).val(convertRupiah(DataPrcCus));

            onInputRupiah();

            setTimeout(() => {
                // $("#on-select-layanan-" + idLayanan).attr("disabled", true);
                $("#on-select-price-custom-" + numb).removeAttr("disabled");
                $("#inpt-select-" + numb).val(
                    $("#on-select-layanan-" + idLayanan).val()
                );
                $("#inpt-select-cat-" + numb).val(
                    $("#on-select-category-" + idLayanan).val()
                );

                var tindIndx = $("input[name=id]").data("from-index")[
                    idLayanan - 1
                ];

                if (tindIndx == numb) {
                    var cekCat = $("#on-select-category-" + idLayanan);

                    cekCat
                        .closest(".n-f-layanan-periksa")
                        .attr("id", "load-tindakan-ke-" + tindIndx);

                    cekCat
                        .closest(".n-f-layanan-periksa")
                        .find(".select-categorys")
                        .prepend(
                            "<input form='formPeriksa' type='hidden' name='from[" +
                                tindIndx +
                                "]' value='" +
                                tindIndx +
                                "'>"
                        );
                }
            }, 2000);
        } else {
            load_avail_layanan("layanan", numb);
            load_avail_category("category", numb);
        }

        $(".load-row-layanan-periksa").delegate(
            "#on-select-layanan-" + numb,
            "change",
            function (e) {
                onInputRupiah();

                var layId = $(this).val();
                loadTotal(layId);
                if (layId) {
                    // $("#on-select-terapis-" + numb).removeAttr("disabled");

                    var prcs = $(
                        "#on-select-layanan-" + numb + " option:selected"
                    ).data("harga");

                    $("#on-select-price-custom-" + numb).removeAttr("disabled");
                    $("#on-select-price-custom-" + numb).val(
                        convertRupiah(prcs)
                    );
                    // load_avail_layanan("terapis", numb, layId);
                } else {
                    // var trps = $("#on-select-terapis-" + numb);
                    // trps.attr("disabled", true);
                    // trps.val("").trigger("change");

                    var prc_cus = $("#on-select-price-custom-" + numb);
                    prc_cus.attr("disabled", true);
                    prc_cus.val("").trigger("change");
                }
            }
        );
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
                    <input type="text" name="layanan_tambahan[]" form="formRegistrasi" placeholder="treatment tambahan..." disabled class="form-control input-group-sm" id="on-input-layanan-tambahan-` +
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

            $(".n-f-layanan-tambahan").find("input").removeAttr("disabled");

            var DataTambahan = $("input[name=id]").data("layanan-tambahan")[
                idLayanan - 1
            ];

            $("#on-input-layanan-tambahan-" + numb).val(DataTambahan.name);
            $("#on-input-harga-tambahan-" + numb).val(
                convertRupiah(DataTambahan.price)
            );
        } else {
            // load_avail_layanan("layanan", numb);
        }

        $(".load-row-layanan").delegate(
            "#on-select-layanan-" + numb,
            "change",
            function (e) {
                var layId = $(this).val();
                // loadTotal(layId);
                if (layId) {
                    // $("#on-select-terapis-" + numb).removeAttr("disabled");

                    var prcs = $(
                        "#on-select-layanan-" + numb + " option:selected"
                    ).data("harga");

                    $("#on-select-price-custom-" + numb).removeAttr("disabled");
                    $("#on-select-price-custom-" + numb).val(
                        convertRupiah(prcs)
                    );
                    // load_avail_layanan("terapis", numb, layId);
                } else {
                    // var trps = $("#on-select-terapis-" + numb);
                    // trps.attr("disabled", true);
                    // trps.val("").trigger("change");

                    var prc_cus = $("#on-select-price-custom-" + numb);
                    prc_cus.attr("disabled", true);
                    prc_cus.val("").trigger("change");
                }
            }
        );
    }, 500);

    return html;
}

function load_row_layanan_tambahan_periksa(idLayanan, idTerapis) {
    var thisElem = $(".n-f-layanan-tambahan-periksa");
    var numb = thisElem.length + 1;

    var html = `<tr class="n-f-layanan-tambahan-periksa">`;
    html +=
        `<td class="nom-layanan-tambahan-periksa td-height-img text-center">` +
        numb +
        `</td>`;
    html +=
        `<td class="input-layanan-tambahan td-height-img">
                <div id="block" class="blocking-loading-row"><em class="fa fa-spinner fa-spin"></em> Loading...</div>
                <div class="input-group-sm">
                    <input type="text" name="layanan_tambahan[]" form="formPeriksa" placeholder="layanan tambahan..." disabled class="form-control input-group-sm" id="on-input-layanan-tambahan-` +
        numb +
        `">` +
        `
                </div>
            </td>`;

    html +=
        `<td class="input-layanan-harga td-height-img">
                <div class="input-group-sm">
                    <input type="rupiah" placeholder="harga..." name="harga_tambahan[]" disabled form="formPeriksa" class="form-control input-group-sm" id="on-input-harga-tambahan-` +
        numb +
        `">
                </div>
            </td>`;

    html += `<td class="td-height-img text-center"><em class="fa fa-times remove-row-layanan-tambahan-periksa text-danger"></em></td>`;
    html += `</tr>`;

    setTimeout(() => {
        $(".input-layanan-tambahan")
            .find("div#block")
            .removeClass("blocking-loading-row")
            .addClass("hide");

        $(".n-f-layanan-tambahan-periksa").find("input").removeAttr("disabled");

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

            $(".n-f-layanan-tambahan").find("input").removeAttr("disabled");

            var DataTambahan = $("input[name=id]").data("layanan-tambahan")[
                idLayanan - 1
            ];

            $("#on-input-layanan-tambahan-" + numb).val(DataTambahan.name);
            $("#on-input-harga-tambahan-" + numb).val(
                convertRupiah(DataTambahan.price)
            );
        } else {
            // load_avail_layanan("layanan", numb);
        }

        $(".load-row-layanan").delegate(
            "#on-select-layanan-" + numb,
            "change",
            function (e) {
                var layId = $(this).val();
                // loadTotal(layId);
                if (layId) {
                    // $("#on-select-terapis-" + numb).removeAttr("disabled");
                    var prcs = $(
                        "#on-select-layanan-" + numb + " option:selected"
                    ).data("harga");

                    $("#on-select-price-custom-" + numb).removeAttr("disabled");
                    $("#on-select-price-custom-" + numb).val(
                        convertRupiah(prcs)
                    );
                    // load_avail_layanan("terapis", numb, layId);
                } else {
                    // var trps = $("#on-select-terapis-" + numb);
                    // trps.attr("disabled", true);
                    // trps.val("").trigger("change");

                    var prc_cus = $("#on-select-price-custom-" + numb);
                    prc_cus.attr("disabled", true);
                    prc_cus.val("").trigger("change");
                }
            }
        );
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

function load_avail_category(table, numb, p_id) {
    var pID = p_id || 0;

    $.ajax({
        url: base_url + "/registrations/opt/" + table,
        method: "GET",
        dataType: "json",
        success: function (data) {
            var html = `<option></option>`;
            var i;
            for (i = 0; i < data.length; i++) {
                if (table == "category") {
                    var selectedd_ = data[i].id === pID ? "selected" : "";

                    html +=
                        `<option value='` +
                        data[i].id +
                        `' ` +
                        selectedd_ +
                        `>` +
                        data[i].nama +
                        `</option>`;
                }
            }
            $("select#on-select-" + table + "-" + numb).html(html);
        },
        complete: function () {
            $("#on-select-" + table + "-" + numb).removeAttr("disabled");

            $("select#on-select-" + table + "-" + numb).select2({
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
        $(".datepicker-reservation").datepicker({
            minDate: "0",
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
                var id_ =
                    table == "lokasi"
                        ? data[i].cabang_id
                        : table == "member"
                        ? data[i].user_id
                        : data[i].id;
                html +=
                    `<option ` +
                    (table == "tindakan"
                        ? `data-id-category="` +
                          data[i].id_cat +
                          `" data-category="` +
                          data[i].category +
                          `"`
                        : "") +
                    layanan_ +
                    `value='` +
                    id_ +
                    `'>` +
                    pegawai +
                    (table == "agama" ||
                    table == "room" ||
                    table == "dokter" ||
                    table == "diagnosis" ||
                    table == "tindakan"
                        ? data[i].name
                        : data[i].nama) +
                    `</option>`;
            }
            var table_ = table == "pegawai" ? "terapis" : table;

            if (table == "diagnosis" || table == "tindakan") {
                $("select[name=" + table_ + "]").html(html);
            } else {
                $("select.f-" + table_).html(html);
            }

            if (table == "dokter") {
                $(".f-dokter").select2({
                    placeholder: "Please select!",
                    theme: "bootstrap",
                });
            }

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

function load_formLeftPeriksa(input) {
    var cont = $(".load-form-left-periksa");
    $(".display-future").addClass("blocking-content");

    cont.load(
        base_url + "/registrations/create?form=left_periksa",
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

                setTimeout(function () {
                    $(".progressbar_order").delegate(
                        "li",
                        "click",
                        function (e) {
                            var li = $(this).data("step");

                            if (li == 1) {
                                $("#f-load-rekam-medik-periksa")
                                    .removeClass("hide")
                                    .addClass("show");
                                $("#f-load-rekam-medik-periksa-gigi")
                                    .removeClass("show")
                                    .addClass("hide");
                                $("#f-load-ubah")
                                    .removeClass("show")
                                    .addClass("hide");

                                $("button[name=next_one]")
                                    .removeClass(
                                        "next-two-info next-three-info"
                                    )
                                    .addClass("next-one-info");

                                $("button[name=next_one]").html(
                                    `<em class="fa fa-arrow-right"></em> Lanjutkan`
                                );

                                $(".to-ch-title").text("Form Rekam Medik Umum");

                                setTimeout(function () {
                                    $(".load-informasi-right-periksa")
                                        .find(".next-one-info")
                                        .prop("type", "button");
                                }, 500);
                            } else if (li == 2) {
                                $("#f-load-rekam-medik-periksa")
                                    .removeClass("show")
                                    .addClass("hide");
                                $("#f-load-rekam-medik-periksa-gigi")
                                    .removeClass("hide")
                                    .addClass("show");
                                $("#f-load-ubah")
                                    .removeClass("show")
                                    .addClass("hide");

                                $("button[name=next_one]")
                                    .removeClass(
                                        "next-one-info next-three-info"
                                    )
                                    .addClass("next-two-info");

                                $("button[name=next_one]").html(
                                    `<em class="fa fa-arrow-right"></em> Lanjutkan`
                                );

                                $(".to-ch-title").text("Form Kontrol");

                                setTimeout(function () {
                                    $(".load-informasi-right-periksa")
                                        .find(".next-two-info")
                                        .prop("type", "button");
                                }, 500);
                            } else if (li == 3) {
                                $("#f-load-rekam-medik-periksa")
                                    .removeClass("show")
                                    .addClass("hide");
                                $("#f-load-rekam-medik-periksa-gigi")
                                    .removeClass("show")
                                    .addClass("hide");
                                $("#f-load-ubah")
                                    .removeClass("hide")
                                    .addClass("show");

                                $("button[name=next_one]")
                                    .removeClass("next-one-info next-two-info")
                                    .addClass("next-three-info");

                                $("button[name=next_one]").html(
                                    `<em class="fa fa-envelope"></em> Simpan`
                                );

                                $(".load-informasi-right-periksa")
                                    .find(".next-three-info")
                                    .prop("type", "submit");

                                $(".to-ch-title").text("Form Update Harga");

                                setTimeout(function () {
                                    $(".load-informasi-right-periksa")
                                        .find(".next-three-info")
                                        .prop("type", "submit");
                                }, 500);
                            }

                            $(".progressbar_order")
                                .find("li")
                                .removeClass("active_order");

                            for (var i = 1; i <= li; i++) {
                                $("li.st-" + i).addClass("active_order");
                            }
                        }
                    );

                    load_formLeftPeriksaGigi();
                    load_formUbahStep(input);
                }, 500);

                $("#f-load-rekam-medik-periksa").html(
                    '<div class="text-left"><em class="fa fa-spin fa-spinner"></em> loading...</div>'
                );

                $("#f-load-rekam-medik-periksa-gigi").html(
                    '<div class="text-left"><em class="fa fa-spin fa-spinner"></em> loading...</div>'
                );

                $("#f-load-ubah").html(
                    '<div class="text-left"><em class="fa fa-spin fa-spinner"></em> loading...</div>'
                );

                form_attribut();
            }
        }
    );
}

function addOnNex(date) {
    $(".on-date-next input").val(date.format("DD-MM-YYYY"));
}

function load_formUbahStep(inputId) {
    var cont = $("#f-load-ubah");
    $(".display-future").addClass("blocking-content");
    cont.load(
        base_url + "/registrations/create?form=right_periksa&step=ya",
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

                $(".load-row-layanan-periksa").html("");
                $(".load-row-layanan-tambahan-periksa").html("");

                setTimeout(function () {
                    $(".load-form-left").find("form").remove();
                    $("#f-load-ubah").find("form").remove();
                    $(".clean-sheet").removeClass("on-dutty-off");
                }, 1500);

                $(".f-layanan-tambahan-periksa")
                    .removeClass("hide")
                    .removeAttr("style");

                setTimeout(function () {
                    //halo

                    $(".load-form-left").append(
                        '<div class="price-layanan-unique"></div>'
                    );

                    for (
                        var rL = 1;
                        rL <= inputId.data("layanan").length;
                        rL++
                    ) {
                        $(".load-row-layanan-periksa").append(
                            load_row_layanan_periksa(rL)
                        );
                    }
                    $(".load-row-layanan-periksa").append(
                        load_row_layanan_periksa
                    );

                    for (
                        var rL = 1;
                        rL <= inputId.data("layanan-tambahan").length;
                        rL++
                    ) {
                        $(".load-row-layanan-tambahan-periksa").append(
                            load_row_layanan_tambahan_periksa(rL)
                        );
                    }

                    $(".load-row-layanan-tambahan-periksa").append(
                        load_row_layanan_tambahan_periksa
                    );

                    $("input[name=tanggal_next]")
                        .val(inputId.data("nexdate"))
                        .trigger("change");

                    setTimeout(function () {
                        $(".add-on-daterpicker-next-treatment").daterangepicker(
                            {
                                drops: "up",
                                singleDatePicker: true,
                                autoUpdateInput: true,
                                showDropdowns: true,
                                startDate: !$("input[name=tanggal_next]").val()
                                    ? moment()
                                    : moment()
                                          .add()
                                          .format(
                                              $(
                                                  "input[name=tanggal_next]"
                                              ).val()
                                          ),
                                minDate: moment(),
                                locale: {
                                    format: "DD-MM-YYYY",
                                },
                            },
                            addOnNex
                        );
                    }, 500);

                    $("input[name=jumlah_orang]")
                        .val(inputId.data("jum_org"))
                        .trigger("change");

                    $("select[name=dokter]")
                        .val(inputId.data("dokter"))
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

                    setTimeout(function () {
                        $(".select2-container").css({
                            width: "100%",
                        });
                    }, 1500);

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

                        $(".datepicker-reservation").datepicker({
                            minDate: "0",
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
                }, 1000);

                form_attribut_right();
            }
        }
    );
}

function load_formLeftPeriksaGigi() {
    var cont = $("#f-load-rekam-medik-periksa-gigi");
    $(".display-future").addClass("blocking-content");
    cont.load(
        base_url + "/registrations/create?form=left_periksa_gigi",
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

                $("table").css({ "border-collapse": "unset !important" });

                var dataId = $("input[name=id]").data("rekam-medik-gigi");
                var dataTindakanId = $("input[name=id]").data("tindakan-gigi");
                var dataRekTindakanId = $("input[name=id]").data(
                    "rekam-tindakan-gigi"
                );

                setTimeout(function () {
                    if (!dataId) {
                        $(".opt-gigi-value").val("permanen");
                        load_gigi("permanen");
                        return false;
                    }

                    switch_gigi();
                    if (dataId) {
                        fEditGigi(dataId[0]);
                    }

                    $.each(dataTindakanId, function (e, i) {
                        fTindakanEdit(e, i);
                    });

                    $.each(dataRekTindakanId, function (e, i) {
                        fRekTindakanEdit(i);
                    });
                }, 1000);
            }
        }
    );
}

function fRekTindakanEdit(param, gigi) {
    setTimeout(function () {
        $(".g-" + param.gigi_no).addClass("sn-active");
        $(".area-" + param.gigi_no).removeClass("no-action-posisi");

        for (var i = 0; i < param.gigi_no_posisi.length; i++) {
            $(".area-" + param.gigi_no)
                .find('*[data-color-no="' + param.gigi_no_posisi[i] + '"]')
                .addClass("ar-gg-active-part");
        }

        var valGigi = $("input[name=gigi]").val();

        if (gigi && valGigi == gigi[0].gigi) {
            setTimeout(function () {
                loadGigiSelected(param.gigi_no, "created");
                loadSelectGigiTextEdit(param.gigi_no);
            }, 500);
        }

        if (!gigi) {
            loadGigiSelected(param.gigi_no, "created");
            loadSelectGigiTextEdit(param.gigi_no);
        }
    }, 500);
}

function fTindakanEdit(indx, param) {
    var html = ``;

    html += `<div class="row fc-tindakan" id="list-tind-` + indx + `">`;

    var img =
        param.image_show == "tidak"
            ? base_url + "/images/noimage.jpg"
            : param.image;
    var img_value = param.image_show == "tidak" ? "" : param.image;
    // : "/storage/master-data/upload/gigi/pasien/tindakan/" + param.image;

    html +=
        `<div class="col-md-8 data-tindakan-f mb-10">
        <input type="hidden" name="diagnosa_id[]" form="formPeriksa" value="` +
        param.diagnosa_id +
        `">
        <input type="hidden" name="tindakan_id[]" form="formPeriksa" value="` +
        param.tindakan_id +
        `">
        <textarea class="hide" name="catatan_tindakan[]" form="formPeriksa">` +
        param.catatan +
        `</textarea>
        <input type="hidden" name="tindakan_image[]" class="to-image-select" form="formPeriksa" value="` +
        img_value +
        `">
                    Diagnosa: <span class="t-diagnosa" data-id-dg="` +
        param.diagnosa_id +
        `">` +
        param.diagnosa_text +
        `</span><br>
                    Tindakan: <span class="t-tindakan" data-id-td="` +
        param.tindakan_id +
        `">` +
        param.tindakan_text +
        `</span><br>
                    <span class="t-catatan">` +
        param.catatan +
        `</span>
                    <span class="t-gambar" data-image="` +
        img +
        `"></span>
                </div>
                <div class="col-md-4">
                    <div class="btn-groups tind-pos" role="group">
                        <a class="btn btn-info text-success btn-xs btn-3d edit-tindakan e-icon-tindakan"
                            data-tindakan="">
                            <em class="fa fa-pencil-square-o"></em>
                        </a>
                        <a class="btn btn-warning text-danger btn-id-1 btn-xs btn-3d remove-tindakan e-icon-tindakan"
                            data-remove-id-tindakan="">
                            <em class="fa fa-trash"></em>
                        </a>
                    </div>
                </div>`;

    html += `</div>`;

    return $(".cont-tindakan").append(html);
}

function getImgGigi(data) {
    var img = !data
        ? "/images/noimage.jpg"
        : "/storage/master-data/upload/gigi/pasien/" + data;

    $("#preview_image_gigi").attr("src", base_url + img);
}

function fEditGigi(data) {
    $(".opt-gigi").removeClass("active-gigi");
    $(".opt-gigi-value").val(data.gigi);

    $("#f-load-rekam-medik-periksa-gigi")
        .find(`[data-gigi="` + data.gigi + `"]`)
        .addClass("active-gigi");

    load_gigi(data.gigi);

    $("textarea[name=ringkasan_gigi]").val(data.ringkasan);

    getImgGigi(data.foto);

    if (data.foto) {
        var flnme = $("#file_gigi_name");
        flnme.val(data.foto);
    }
}

function removeGigiSelected() {
    $(".load-selected-gigi").delegate(
        ".removed-selected-gigi",
        "click",
        function () {
            var Id = $(this).closest(".cont-selected-gigi").attr("id");

            loadGigiSelected(Id, "remove", "parent");
        }
    );
}

function loadGigiSelected(no, param, parent) {
    var html = ``;

    html +=
        `<div class="cont-sel-gigi sel-gigi-` +
        no +
        `">
                <div id="` +
        no +
        `" class="cont-selected-gigi">
        <input type="hidden" name="gigi_no[` +
        no +
        `]" form="formPeriksa" value="` +
        no +
        `">
                    <span class="t-selected">
                        Gigi ` +
        no +
        ` <span class="text-gigi-act"></span>
          <div class="text-gigi-act-inp"></div>
                    </span>
                    <div class="cont-selected-gigi-remove">
                        <em class="fa fa-times removed-selected-gigi"></em>
                    </div>
                </div>
            </div>`;

    if (param == "created") {
        $(".sel-gigi-" + no).remove();
        $(".load-selected-gigi").append(html);
    } else if (param == "remove") {
        $(".sel-gigi-" + no).remove();

        if (parent && parent == "parent") {
            $(".load-content-gigi-img")
                .find(`[data-number-gigi="` + no + `"]`)
                .removeClass("sn-active");

            $(".area-" + no).addClass("no-action-posisi");

            $(".area-" + no)
                .find("td")
                .removeClass("ar-gg-active")
                .removeClass("ar-gg-active-part");
        }
    }
}

function load_gigi(param) {
    var cont = $(".load-content-gigi-img");

    $(".display-future").addClass("blocking-content");

    cont.load(
        base_url + "/registrations/create?load=gigi_" + param,
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

                $("table").css({ "border-collapse": "unset !important" });

                $(".load-content-gigi-img").html(e);

                setTimeout(function () {
                    switch_gigi();

                    var OptGigi = $(".opt-gigi");
                    OptGigi.prop("disabled", false);

                    $(".load-content-gigi-img").delegate(
                        ".labeling-gigi",
                        "click",
                        function (e) {
                            var itm = $(this);
                            itm.addClass("sn-active");

                            var sn = itm.data("number-gigi");

                            $(".area-" + sn).removeClass("no-action-posisi");

                            loadGigiSelected(sn, "created");

                            removeGigiSelected();
                        }
                    );

                    $(".load-content-gigi-img").delegate(
                        ".sn-active",
                        "click",
                        function (e) {
                            var itm = $(this);
                            itm.removeClass("sn-active");

                            var sn = itm.data("number-gigi");

                            $(".area-" + sn).addClass("no-action-posisi");

                            $(".area-" + sn)
                                .find("td")
                                .removeClass("ar-gg-active")
                                .removeClass("ar-gg-active-part");

                            loadGigiSelected(sn, "remove");

                            removeGigiSelected();
                        }
                    );

                    $(".content-circle").delegate(
                        ".area-posisi-gigi",
                        "click",
                        function (e) {
                            var itm = $(this);
                            itm.addClass("ar-gg-active");

                            loadSelectGigiText(itm);
                        }
                    );

                    $(".content-circle").delegate(
                        ".ar-gg-active",
                        "click",
                        function (e) {
                            var itm = $(this);
                            itm.removeClass("ar-gg-active");

                            loadSelectGigiText(itm);
                        }
                    );

                    $(".content-circle").delegate(
                        ".area-posisi-gigi-part",
                        "click",
                        function (e) {
                            var itm = $(this);
                            itm.addClass("ar-gg-active-part");

                            loadSelectGigiText(itm);
                        }
                    );

                    $(".content-circle").delegate(
                        ".ar-gg-active-part",
                        "click",
                        function (e) {
                            var itm = $(this);
                            itm.removeClass("ar-gg-active-part");

                            loadSelectGigiText(itm);
                        }
                    );

                    $(".load-form-left-periksa").delegate(
                        ".load-f-per-gigi",
                        "click",
                        function () {
                            $("#formModalMontrgOrderPeriksaGigi").modal({
                                backdrop: "static",
                                keyboard: false,
                            });

                            load_formUbah();
                        }
                    );

                    $(".cont-tindakan").delegate(
                        ".edit-tindakan",
                        "click",
                        function () {
                            $("#formModalMontrgOrderPeriksaGigi").modal({
                                backdrop: "static",
                                keyboard: false,
                            });

                            var tImage = $(this)
                                .closest(".fc-tindakan")
                                .find(".t-gambar")
                                .data("image");

                            var tImageSelect = $(this)
                                .closest(".fc-tindakan")
                                .find(".to-image-select")
                                .val();

                            var tCatatan = $(this)
                                .closest(".fc-tindakan")
                                .find(".t-catatan")
                                .text();

                            var tTindakan = $(this)
                                .closest(".fc-tindakan")
                                .find(".t-tindakan");

                            var tDiagnosis = $(this)
                                .closest(".fc-tindakan")
                                .find(".t-diagnosa");

                            var tIdEdit = $(this)
                                .closest(".fc-tindakan")
                                .attr("id");

                            var dataTindakan = [
                                tDiagnosis,
                                tTindakan,
                                tCatatan,
                                tIdEdit,
                                tImage,
                                tImageSelect,
                            ];

                            load_formUbah(dataTindakan);
                        }
                    );

                    $(".cont-tindakan").delegate(
                        ".remove-tindakan",
                        "click",
                        function () {
                            $(this).closest(".fc-tindakan").remove();

                            loadSyncRowTindakan();
                            toastr.success("Data pemeriksaan dihapus!");
                        }
                    );

                    setTimeout(function () {
                        var dataRekTindakanId = $(
                            "input#form-periksa-gigi"
                        ).data("rekam-tindakan-gigi");

                        var dataGIGI = $("input#form-periksa-gigi").data(
                            "rekam-medik-gigi"
                        );

                        setTimeout(function () {
                            $.each(dataRekTindakanId, function (e, i) {
                                fRekTindakanEdit(i, dataGIGI);
                            });
                        }, 1000);
                    }, 500);
                }, 1000);
            }
        }
    );
}

function loadSyncRowTindakan() {
    var noUrut = $(".cont-tindakan").find(".fc-tindakan");

    // noUrut.removeClass(function (index, className) {
    //     return (className.match(/(^|\s)list-tind-\S+/g) || []).join(" ");
    // });

    $.each(noUrut, function (e, i) {
        $(i).attr("id", "list-tind-" + e);
    });
}

function loadSelectGigiText(itm) {
    var noGigi = itm.closest(".content-circle").data("no-gigi");

    var textGigi = "";

    var dataGigiSelect = $(".area-" + noGigi).find("td.ar-gg-active");
    var dataGigiSelectPart = $(".area-" + noGigi).find("td.ar-gg-active-part");

    var htmlInp = "";

    $.each(dataGigiSelect, function (e, f) {
        var classColorSelectedNo = $(this).data("color-no");
        var classColorSelectedNama = $(this).data("color-name");

        htmlInp +=
            '<input type="hidden" name="gigi_no_posisi[' +
            noGigi +
            '][]" form="formPeriksa" value="' +
            classColorSelectedNo +
            '">';

        var tit = parseInt(dataGigiSelect.length) - 1 == e ? "" : ", ";

        textGigi += classColorSelectedNama + tit;
        // +            (dataGigiSelectPart.length < 2 ? ", " : "");
    });

    textGigi +=
        parseInt(dataGigiSelect.length) > 0 &&
        parseInt(dataGigiSelectPart.length) > 0
            ? ", "
            : "";

    $.each(dataGigiSelectPart, function (e, f) {
        var classColorSelectedNoPart = $(this).data("color-no");
        var classColorSelectedNamaPart = $(this).data("color-name");

        htmlInp +=
            '<input type="hidden" name="gigi_no_posisi[' +
            noGigi +
            '][]" form="formPeriksa" value="' +
            classColorSelectedNoPart +
            '">';

        var titPart = parseInt(dataGigiSelectPart.length) - 1 == e ? "" : ", ";

        textGigi += classColorSelectedNamaPart + titPart;
    });

    $(".sel-gigi-" + noGigi)
        .find(".text-gigi-act")
        .html(textGigi);

    $(".sel-gigi-" + noGigi)
        .find(".text-gigi-act-inp")
        .html(htmlInp);
}

function loadSelectGigiTextEdit(noGigi) {
    var textGigi = "";

    var dataGigiSelect = $(".area-" + noGigi).find("td.ar-gg-active");
    var dataGigiSelectPart = $(".area-" + noGigi).find("td.ar-gg-active-part");

    var htmlInp = "";

    $.each(dataGigiSelect, function (e, f) {
        var classColorSelectedNo = $(this).data("color-no");
        var classColorSelectedNama = $(this).data("color-name");

        htmlInp +=
            '<input type="hidden" name="gigi_no_posisi[' +
            noGigi +
            '][]" form="formPeriksa" value="' +
            classColorSelectedNo +
            '">';

        var tit = parseInt(dataGigiSelect.length) - 1 == e ? "" : ", ";

        textGigi += classColorSelectedNama + tit;
        // +            (dataGigiSelectPart.length < 2 ? ", " : "");
    });

    textGigi +=
        parseInt(dataGigiSelect.length) > 0 &&
        parseInt(dataGigiSelectPart.length) > 0
            ? ", "
            : "";

    $.each(dataGigiSelectPart, function (e, f) {
        var classColorSelectedNoPart = $(this).data("color-no");
        var classColorSelectedNamaPart = $(this).data("color-name");

        htmlInp +=
            '<input type="hidden" name="gigi_no_posisi[' +
            noGigi +
            '][]" form="formPeriksa" value="' +
            classColorSelectedNoPart +
            '">';

        var titPart = parseInt(dataGigiSelectPart.length) - 1 == e ? "" : ", ";

        textGigi += classColorSelectedNamaPart + titPart;
    });

    $(".sel-gigi-" + noGigi)
        .find(".text-gigi-act")
        .html(textGigi);

    $(".sel-gigi-" + noGigi)
        .find(".text-gigi-act-inp")
        .html(htmlInp);
}

function load_formUbah(data) {
    var cont = $(".load-form-periksa-gigi");
    $(".display-future").addClass("blocking-content");

    cont.load(
        base_url + "/registrations/create?form=left_tindakan",
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

                setTimeout(function () {
                    select_("diagnosis");
                    select_("tindakan");

                    $(".select2-tindakan").select2({
                        placeholder: "Please select!",
                        allowClear: true,
                        theme: "bootstrap",
                    });

                    setTimeout(function () {
                        $(".select2-container").css({
                            width: "100%",
                        });
                    }, 1000);

                    $(".cancel-form-tindakan,.mod-vol-1").on(
                        "click",
                        function () {
                            $("#formModalMontrgOrderPeriksaGigi").modal("hide");

                            $(".modal").css({
                                "overflow-x": "hidden",
                                "overflow-y": "auto",
                            });
                        }
                    );

                    $(".save-form-tindakan").on("click", function () {
                        loadSaveTindakan();

                        $("#formModalMontrgOrderPeriksaGigi").modal("hide");

                        $(".modal").css({
                            "overflow-x": "hidden",
                            "overflow-y": "auto",
                        });
                    });

                    setTimeout(function () {
                        if (data) {
                            $(".fm-diagnosis")
                                .val(data[0].data("id-dg"))
                                .change();
                            $(".fm-tindakan")
                                .val(data[1].data("id-td"))
                                .change();
                            $("#more_catatan").html(data[2]);

                            $("#preview_image_tindakan").attr("src", data[4]);

                            var imgss = data[4] == data[5] ? data[5] : data[4];

                            $("input#file_name_tindakan").val(imgss);

                            $("#formModalMontrgOrderPeriksaGigi")
                                .find(".modal-body")
                                .prepend(
                                    '<input id="edit_div_tindakan" class="form-control" type="hidden" readonly value="' +
                                        data[3] +
                                        '">'
                                );
                        }
                    }, 500);
                }, 800);
            }
        }
    );
}

function fTindakan(val) {
    var html = ``;

    var noUrut = $(".cont-tindakan").find(".fc-tindakan").length;

    var IdEdit = $("#edit_div_tindakan").val();

    if (!IdEdit) {
        html += `<div class="row fc-tindakan" id="list-tind-` + noUrut + `">`;
    }

    var fileTind =
        $("input#file_tindakan").val() !=
        $("#preview_image_tindakan").attr("src")
            ? $("#preview_image_tindakan").attr("src")
            : val[3];

    var jumBarStp = $(".n-f-layanan-periksa").length;

    html +=
        `<div class="col-md-8 data-tindakan-f mb-10">
        <input type="hidden" name="diagnosa_id[]" form="formPeriksa" value="` +
        val[0].val() +
        `">
        <input type="hidden" name="indexing[]" form="formPeriksa" value="` +
        jumBarStp +
        `">
        <input type="hidden" name="tindakan_id[]" form="formPeriksa" value="` +
        val[1].val() +
        `">
        <textarea class="hide" name="catatan_tindakan[]" form="formPeriksa">` +
        val[2] +
        `</textarea>
        <input type="hidden" name="tindakan_image[]" class="to-image-select" form="formPeriksa" value="` +
        fileTind +
        `">
                    Diagnosa: <span class="t-diagnosa" data-id-dg="` +
        val[0].val() +
        `">` +
        val[0].text() +
        `</span><br>
                    Tindakan: <span class="t-tindakan" data-id-td="` +
        val[1].val() +
        `">` +
        val[1].text() +
        `</span><br>
                    <span class="t-catatan">` +
        val[2] +
        `</span>
                    <span class="t-gambar" data-image="` +
        val[3] +
        `"></span>
                    <span class="t-indexing" data-indexing="` +
        jumBarStp +
        `"></span>
                </div>
                <div class="col-md-4">
                    <div class="btn-groups tind-pos" role="group">
                        <a class="btn btn-info text-success btn-xs btn-3d edit-tindakan e-icon-tindakan"
                            data-tindakan="">
                            <em class="fa fa-pencil-square-o"></em>
                        </a>
                        <a class="btn btn-warning text-danger btn-id-1 btn-xs btn-3d remove-tindakan e-icon-tindakan"
                            data-remove-id-tindakan="">
                            <em class="fa fa-trash"></em>
                        </a>
                    </div>
                </div>`;

    if (!IdEdit) {
        html += `</div>`;
    }

    if (!IdEdit) {
        return $(".cont-tindakan").append(html);
    } else {
        return $("#" + IdEdit).html(html);
    }
}

function lTindakanRecord() {
    $("#formModalMontrgOrderPeriksaGigi").modal({
        backdrop: "static",
        keyboard: false,
    });

    load_formUbah();
}

function loadSaveTindakan() {
    var fDiag = $(".fm-diagnosis option:selected");
    var fTind = $(".fm-tindakan option:selected");

    if (!fDiag.val() && !fTind.val()) {
        toastr.warning(
            "Pilih diagnosis dan tindakan yang akan dilakukan!",
            "Oops!",
            {
                timeOut: 2000,
            }
        );

        return false;
    }

    var fCatatan = $("#more_catatan");
    var fImage = $("#preview_image_tindakan");

    var dataStind = new Array(fDiag, fTind, fCatatan.val(), fImage.attr("src"));

    fTindakan(dataStind);

    lTindakanRecord();

    loadToStep3(fTind);
}

function loadToStep3(tind) {
    var jmBar = $(".n-f-layanan-periksa").length;

    var cat_id = tind.data("id-category");
    var idSelc = tind.val();

    var cekCat = $("#on-select-category-" + jmBar);
    var cekLay = $("#on-select-layanan-" + jmBar);

    if (
        $("input#edit_div_tindakan").val() == "" ||
        $("input#edit_div_tindakan").val() == undefined
    ) {
        if (!cekCat.val()) {
            cekCat.val(cat_id).change();
        }

        if (!cekLay.val()) {
            cekLay.val(idSelc).change();
        }

        if (cekCat.val() && cekLay.val()) {
            cekCat
                .closest(".n-f-layanan-periksa")
                .attr("id", "load-tindakan-ke-" + jmBar);

            cekCat
                .closest(".n-f-layanan-periksa")
                .find(".select-categorys")
                .prepend(
                    "<input form='formPeriksa' type='hidden' name='from[" +
                        jmBar +
                        "]' value='" +
                        jmBar +
                        "'>"
                );

            $(".load-row-layanan-periksa").append(load_row_layanan_periksa);
        }

        var msg =
            "Dengan menyimpan data tindakan ini, data akan otomatis ditambahkan di step 3!";
        toastr.info(msg, "Informasi!", {
            timeOut: 3000,
        });
    }

    if ($("input#edit_div_tindakan").length == 1) {
        var numBar = $("#edit_div_tindakan").val().split("list-tind-")[1];

        var loadTindkTr = $("#load-tindakan-ke-" + (parseInt(numBar) + 1));

        if (loadTindkTr.length == 1) {
            var cekCats = $("#on-select-category-" + (parseInt(numBar) + 1));
            var cekLays = $("#on-select-layanan-" + (parseInt(numBar) + 1));

            if (!cekCats.val()) {
                cekCats.val(cat_id).change();
            }

            if (!cekLays.val()) {
                cekLays.val(idSelc).change();
            }
        }
    }
}

function switch_gigi() {
    $(".on-c-gigi-chg").delegate(".opt-gigi", "click", function (e) {
        var gigi = $(this).data("gigi");

        $(".opt-gigi-value").val(gigi);

        $(".opt-gigi").removeClass("active-gigi").prop("disabled", true);

        $(this).addClass("active-gigi");

        load_gigi(gigi);

        setTimeout(function () {
            var dataRekTindakanId = $("input#form-periksa-gigi").data(
                "rekam-tindakan-gigi"
            );

            var dataGIGI = $("input#form-periksa-gigi").data(
                "rekam-medik-gigi"
            );

            setTimeout(function () {
                $.each(dataRekTindakanId, function (e, i) {
                    fRekTindakanEdit(i, dataGIGI);
                });
            }, 1000);
        }, 500);
    });
}

function load_formRight(evv) {
    var cont = $(".load-form-right");
    $(".display-future").addClass("blocking-content");
    cont.load(
        base_url + "/registrations/create?form=right&step=ya",
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

                setTimeout(function () {
                    loadRekamMedik("form-load");
                }, 1500);

                setTimeout(function () {
                    $(".select2-container").css({
                        width: "100%",
                    });
                }, 1000);

                submit(evv);
            }
        }
    );
}

function savePeriksa(ev) {
    $("form#formPeriksa").submit(function (e) {
        e.preventDefault();

        var event = $(this)[0];

        $(".preloader").fadeIn();
        $(".display-future").addClass("blocking-content");

        var data = new FormData(event);
        var url = ev.data("routes");

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
                            $(".display-future").removeClass(
                                "blocking-content"
                            );
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
    });
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
              : row.print_act == 0
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
    load_formPeriksa();
    load_Activation();
    load_printCase();
    load_sendPembayaranCase();
    load_voidPembayaranCase();

    $(".load-data").delegate(
        ".detail-hstry",
        "click",
        function (e) {
            var event = $(this);
            load_detailHisTable(event);
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

    $("#newFilterStatusOrder").on("change", function () {
        getStatus_field_daterange();
    });

    $("tbody").delegate(".to-change-content-serv", "click", function () {
        $(".load-row-layanan").html("");
        $(".load-row-layanan-tambahan").html("");
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

$("#formModalMontrgOrderPeriksaGigi").click(function (e) {
    if (!$(e.target).closest(".modal").length) {
        alert("click outside!");
    }
});
