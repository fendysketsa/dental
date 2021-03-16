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
                            <div class="input-group-addon bg-yellow to-change-it">
                                <i class="fa fa-edit" id="edit"></i>
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
        `<input type="text" name="ino_member" readonly value="` +
        DatAuto +
        `" class="form-control input-sm no-border" placeholder="No Member..." form="formRegistrasi">`;
    return html;
}

function addOn(date) {
    $(".on-date input").val(date.format("DD-MM-YYYY"));
}

function addOnNex(date) {
    $(".on-date-next input").val(date.format("DD-MM-YYYY"));
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
        (data ? data.nama : "") +
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
                        <input type="email" ` +
        (data ? (data.email ? "readonly" : "") : "") +
        ` name="email" value="` +
        (data ? (data.email ? data.email : "") : "") +
        `" class="form-control" placeholder="Email..." form="formRegistrasi">
                        <div class="input-group-addon bg-green on-edit-true">
                                <i class="fa fa-pencil"></i>
                            </div>
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
        (data ? data.telepon : "") +
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
        (data ? (data.alamat ? data.alamat : "") : "") +
        `</textarea>
                </div>
            </div>`;
    return html;
}

function f_paket() {
    var html = ``;
    html += `<div class="row">
                    <div class="col-md-12 col-xs-12">
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
                    <select name="layanan[]" class="select2 f-layanan form-control" style="width: 100%;" form="formRegistrasi"></select>
                </div>
            </div>`;
    return $("#non-paket").html(html);
}

function loadTotal(idLayanan) {
    var total_harga_paket = 0;
    var total_harga_layanan = 0;
    var total_harga_ruangan = 0;

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

        var vvl_ruangan = $("select#on-select-ruangan-" + vl)
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

        if (
            !isNaN(vvl_ruangan) &&
            $("select#on-select-ruangan-" + vl).val() != "undefined"
        ) {
            if (!vvl_ruangan) {
                vvl_ruangan = 0;

                total_harga_ruangan += parseInt(vvl_ruangan);
            } else {
                total_harga_ruangan += parseInt(vvl_ruangan);
            }
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
    // for (var ji = 0; ji < uniqueLayNames.length; ji++) {
    //     vVls = $(".price-layanan-unique").data('price-layanan-new-' + ji)
    //     total_harga_layanan += parseInt(vVls)
    // }

    //     var fullTotal = total_harga_paket + total_harga_layanan;
    //     $(".price-full").html(convertRupiah(fullTotal));
    // }, 2500);

    var fullTotal =
        total_harga_paket + total_harga_layanan + total_harga_ruangan;

    $(".price-full").html(convertRupiah(fullTotal));
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
    return rupiah;
}

function form_attribut_right() {
    f_paket();
    f_nonpaket();
    select_("layanan");
    select_("pegawai");
    select_("paket");

    select_("room");
    select_("dokter");

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
            $(".load-row-paket").html(load_row_paket);
        }
    });

    $(".cancel-form").on("click", function () {
        location.href = base_url + "/registrations";
    });

    $(".f-layanan").select2({
        placeholder: "Please select!",
        allowClear: true,
        theme: "bootstrap",
    });
    $(".f-paket").select2({
        placeholder: "Please select!",
        allowClear: true,
        theme: "bootstrap",
    });

    $(".f-ruangan").select2({
        placeholder: "Please select!",
        theme: "bootstrap",
    });

    $("select.f-paket").on("change", function (e) {
        var pket_id = $(this).val();
        var harga_paket = $(this).find("option:selected").data("harga");

        if (pket_id) {
            $(".price-full").text(harga_paket);
        } else {
            $(".price-full").text("0");
        }

        select_("layanan", pket_id);
    });

    $("select.f-layanan").on("change", function (e) {
        var total_harga_layanan = 0;
        var pket_id = $("select[name=paket]").val();
        var lyn_id = $(this).val().length;
        var harga_paket = $("select[name=paket]")
            .find("option:selected")
            .data("harga");

        if (pket_id) {
            for (var vl = 0; vl < lyn_id; vl++) {
                var ss = $(this).find("option:selected")[vl];
                total_harga_layanan += parseInt(ss.dataset.harga);
            }
            $(".price-full").text(parseInt(harga_paket + total_harga_layanan));
        } else {
            if (lyn_id) {
                for (var vl = 0; vl < lyn_id; vl++) {
                    var ss = $(this).find("option:selected")[vl];
                    total_harga_layanan += parseInt(ss.dataset.harga);
                }
                $(".price-full").text(total_harga_layanan);
            } else {
                $(".price-full").text("0");
            }
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

    $(".load-row-layanan").html(load_row_layanan);
    // $(".load-row-paket").html(load_row_paket);

    $(".add-row-layanan").on("click", function () {
        $(".load-row-layanan").append(load_row_layanan);
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
                        $(trs[e])
                            .find("td.select-ruangan")
                            .find("select")
                            .removeAttr("id")
                            .attr("id", "on-select-ruangan-" + (e + 1));
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

    setTimeout(function () {
        $(".noMember").addClass("on-dutty-off loading-member-reg");
    }, 500);
}

function load_row_paket() {
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
                                <th class="text-left" style="width:55%;">Layanan</th>`;
    // <th class="text-left" style="width:40%">Ruangan</th>
    html +=
        `</tr>
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
        load_avail_layanan("paket", numb);

        $("#on-select-paket-" + numb).on("change", function (e) {
            var PktlayId = $(this).val();
            loadTotal();

            if (PktlayId) {
                $(".load-more-paket-" + numb).removeClass("hide");
                load_avail_layanan_on_paket("layanan", numb, PktlayId);
            } else {
                $(".load-more-paket-" + numb).addClass("hide");
                var trps = $("#on-select-onpkt-ruangan-" + numb);
                trps.attr("disabled", true);
                trps.val("").trigger("change");
            }
        });
    }, 500);

    return html;
}

function load_row_layanan() {
    var thisElem = $(".n-f-layanan");
    var numb = thisElem.length + 1;

    var html = `<tr class="n-f-layanan">`;
    html +=
        `<td class="nom-layanan td-height-img text-center">` + numb + `</td>`;
    html +=
        `<td class="select-layanan td-height-img">
                <div id="block" class="blocking-loading-row"><em class="fa fa-spinner fa-spin"></em> Loading...</div>
                <div class="input-group-sm">
                    <select name="layanan[]" required form="formRegistrasi" class="select2 form-control input-group-sm" disabled id="on-select-layanan-` +
        numb +
        `"></select>
                </div>
            </td>`;
    // html +=
    //     `<td class="select-ruangan td-height-img">
    //             <div class="input-group-sm">
    //                 <select name="ruangan[]" form="formRegistrasi" class="select2 form-control input-group-sm" disabled id="on-select-ruangan-` +
    //     numb +
    //     `"></select>
    //             </div>
    //         </td>`;

    html += `<td class="td-height-img text-center"><em class="fa fa-times remove-row-layanan text-danger"></em></td>`;
    html += `</tr>`;

    setTimeout(() => {
        load_avail_layanan("layanan", numb);

        $("#on-select-layanan-" + numb).on("change", function (e) {
            var layId = $(this).val();

            loadTotal(layId);

            if (layId) {
                $("#on-select-ruangan-" + numb).removeAttr("disabled");
                load_avail_layanan("ruangan", numb, layId);

                $("#on-select-ruangan-" + numb).on("change", function (e) {
                    var ruangId = $(this).val();

                    loadTotal(ruangId);
                });
            } else {
                var trps = $("#on-select-ruangan-" + numb);
                trps.attr("disabled", true);
                trps.val("").trigger("change");
            }
        });
    }, 500);

    return html;
}

function load_avail_playanant(table, numb, layanan) {
    var pID = layanan || 0;

    $.ajax({
        url:
            base_url +
            "/registrations/opt-ruangan/" +
            table +
            "?layanan=" +
            pID,
        method: "GET",
        dataType: "json",
        success: function (data) {
            if (data.length === 0) {
                $(
                    "select#on-select-paketlayananruangan-" +
                        numb +
                        "-lay-" +
                        pID
                )
                    .attr("disabled", true)
                    .removeAttr("name,form");
                $(
                    "div#on-input-paketlayananruangan-" + numb + "-lay-" + pID
                ).html(
                    `<input type="hidden" form="formRegistrasi" name="pkt_layanan_ruangan[` +
                        (numb - 1) +
                        `][]" readonly value="0">`
                );
            } else {
                $(
                    "select#on-select-paketlayananruangan-" +
                        numb +
                        "-lay-" +
                        pID
                ).removeAttr("disabled");
            }

            var html = [];
            html += `<option></option>`;
            for (var i = 0; i < data.length; i++) {
                html +=
                    `<option value='` +
                    data[i].id +
                    `'>` +
                    data[i].nama +
                    `</option>`;
                $(
                    "select#on-select-paketlayananruangan-" +
                        numb +
                        "-lay-" +
                        pID
                ).html(html);
            }

            $(
                "select#on-select-paketlayananruangan-" + numb + "-lay-" + pID
            ).select2({
                placeholder: "Please select!",
                allowClear: true,
                theme: "bootstrap",
            });
        },
    });
}

function matchCustom(params, data) {
    if ($.trim(params.term) === "") {
        return data;
    }

    if (typeof data.children === "undefined") {
        return null;
    }

    var filteredChildren = [];
    $.each(data.children, function (idx, child) {
        if (child.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
            filteredChildren.push(child);
        }
    });

    if (filteredChildren.length) {
        var modifiedData_ = $.extend({}, data, true);
        modifiedData_.children = filteredChildren;
        return modifiedData_;
    }
    return null;
}

function load_avail_layanan(table, numb, p_id) {
    var pID = p_id || 0;

    var resTag = $("input[name=tgl_reservasi]").val();
    var resJam = $("input[name=jam_reservasi]").val();
    var resLoc = $("select[name=lokasi_reservasi]").val();

    var urlAdd =
        resTag && resJam && resLoc
            ? "&tgl_res=" + resTag + "&jam_res=" + resJam + "&loc_res=" + resLoc
            : "";

    $.ajax({
        url:
            base_url +
            "/registrations/opt/" +
            table +
            (table == "ruangan" ? "?layanan=" + pID + urlAdd : ""),
        method: "GET",
        dataType: "json",
        success: function (data) {
            var html = `<option></option>`;
            var i;
            for (i = 0; i < data.length; i++) {
                var harga =
                    table == "paket"
                        ? `data-harga='` + data[i].harga + `'`
                        : table == "ruangan"
                        ? `data-harga='` + data[i].harga + `'`
                        : "";

                if (table == "layanan") {
                    // html += `<optgroup label="` + data[i].nama + `">`;
                    // for (var ii = 0; ii < data[i].data.length; ii++) {
                    var harga_ =
                        table == "layanan_"
                            ? `data-harga='` + data[i].data[ii].harga + `'`
                            : "";
                    html +=
                        `<option ` +
                        harga_ +
                        ` alt="` +
                        data[i].nama +
                        `" value='` +
                        // data[i].data[ii].id +
                        data[i].id +
                        `'>` +
                        // data[i].data[ii].nama +
                        data[i].nama +
                        `</option>`;
                    // }
                    // html += `</optgroup>`;
                }

                if (table != "layanan") {
                    // var AvaliableTrps = table == 'paket' ? '' : (data[i].on_work == 'true' ? 'disabled' : (data[i].available == 'true' ? '' : 'disabled'));
                    var AvaliableTrps =
                        table == "paket"
                            ? ""
                            : data[i].on_work == "true"
                            ? "disabled"
                            : data[i].available == "true"
                            ? ""
                            : "";
                    html +=
                        `<option ` +
                        AvaliableTrps +
                        ` ` +
                        harga +
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

            $("select#on-select-" + table + "-" + numb).select2({
                placeholder: "Please select!",
                allowClear: true,
                theme: "bootstrap",
                matcher: matchCustom,
            });

            $("");
        },
    });
}

function load_avail_layanan_on_paket(table, numb, p_id) {
    var pID = p_id || 0;
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
                            <div id="on-input-paketlayananruangan-` +
                    numb +
                    `-lay-` +
                    data[i].id +
                    `"></div>
                            <div class="input-group-sm">
                                <select name="pkt_layanan_ruangan[` +
                    (numb - 1) +
                    `][]" form="formRegistrasi" class="select2 form-control input-group-sm" disabled
                                    id="on-select-paketlayananruangan-` +
                    numb +
                    `-lay-` +
                    data[i].id +
                    `"></select>
                            </div>
                        </td>`;
                html += `</tr>`;

                load_avail_playanant("pegawai", numb, data[i].id);
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
                $(".clean-sheet").addClass("on-dutty-off");
                $("button[type=submit]").attr("disabled", true);
                return false;
            }

            $(".clean-sheet").removeClass("on-dutty-off");
            $("button[type=submit]").removeAttr("disabled");
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

                    $("div.date").delegate(
                        "div.on-edit-true",
                        "click",
                        function (e) {
                            $(this)
                                .closest("div.date")
                                .find("input")
                                .removeAttr("readonly");
                        }
                    );
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

        var InMemb = $("input[name=ino_member]").val().length;
        if (InMemb <= 0) {
            $(".clean-sheet").addClass("on-dutty-off");
            $("button[type=submit]").attr("disabled", true);
            return false;
        }
        $(".clean-sheet").removeClass("on-dutty-off");
        $("button[type=submit]").removeAttr("disabled");

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

    $("div.change-to-field").delegate(
        "input[name=ino_member]",
        "input",
        function (e) {
            var InMemb = $(this).val().length;
            if (InMemb <= 0) {
                $(".clean-sheet").addClass("on-dutty-off");
                return false;
            }
            $(".clean-sheet").removeClass("on-dutty-off");
        }
    );

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
        var SelMemb = $("input[name=sno_member]").val();
        if (!SelMemb) {
            $(".clean-sheet").addClass("on-dutty-off");
            return false;
        }
        $(".clean-sheet").removeClass("on-dutty-off");
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
        $(".to-change-it").delegate("#edit", "click", function () {
            $("input[name=jam_reservasi]").removeAttr("readonly");
            $(this)
                .removeClass("fa-edit")
                .addClass("fa-clock-o")
                .attr("id", "no-edit");
        });
        $(".to-change-it").delegate("#no-edit", "click", function () {
            $("input[name=jam_reservasi]").attr("readonly", true);
            $(this)
                .removeClass("fa-clock-o")
                .addClass("fa-edit")
                .attr("id", "edit");
        });
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
        $(".noMember").removeClass("on-dutty-off loading-member-reg");
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

    setTimeout(function () {
        $(".add-on-daterpicker-next-treatment").daterangepicker(
            {
                drops: "up",
                singleDatePicker: true,
                autoUpdateInput: true,
                showDropdowns: true,
                startDate: moment(),
                minDate: moment(),
                locale: {
                    format: "DD-MM-YYYY",
                },
            },
            addOnNex
        );
    }, 500);
}

function select_(table, pkt) {
    var pkt_ = pkt || "";
    $(".noMember").addClass("on-dutty-off loading-member-reg");
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

                if (table == "room" || table == "agama" || table == "dokter") {
                    html +=
                        `<option value='` +
                        data[i].id +
                        `'>` +
                        data[i].name +
                        `</option>`;
                } else {
                    html +=
                        `<option ` +
                        layanan_ +
                        `value='` +
                        (table == "member" ? data[i].user_id : data[i].id) +
                        `'>` +
                        pegawai +
                        data[i].nama +
                        `</option>`;
                }
            }
            var table_ = table == "pegawai" ? "ruangan" : table;
            $("select.f-" + table_).html(html);
        },
        complete: function () {
            if (table == "member") {
                $(".noMember").removeClass("on-dutty-off loading-member-reg");
            }

            if (table == "room") {
                $(".f-room").select2({
                    placeholder: "Please select!",
                    theme: "bootstrap",
                });
            }

            if (table == "dokter") {
                $(".f-dokter").select2({
                    placeholder: "Please select!",
                    theme: "bootstrap",
                });
            }

            if (table == "agama") {
                $(".f-agama").select2({
                    placeholder: "Please select!",
                    theme: "bootstrap",
                });

                $(".noMember").removeClass("on-dutty-off loading-member-reg");
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
            $(".load-form-left").append(
                '<div class="price-layanan-unique"></div>'
            );
        }
    });
}

function content_rekam_medik(data) {
    var html = ``;
    var newHtml = ``;

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
                  e +
                  ` text-info"><em class="fa fa-info-circle"></em> ` +
                  i.more_input_label.split("\n")[0] +
                  `</small>`;

            var moreInput = !i.more_input
                ? ""
                : `<input type="text" class="form-control mt-5"  form="formRegistrasi" placeholder="` +
                  plcInput +
                  `" name="rekam_more[` +
                  i.id +
                  `]" >`;

            if (i.option) {
                $.each(i.option.split("\n"), function (f, g) {
                    Option +=
                        `<input ` +
                        (typeInput == "radio" ? "class='on-label-click'" : "") +
                        ` style="margin-right:10px;" ` +
                        (typeInput == "radio" && f == 0 ? "checked" : "") +
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
                        e +
                        `" data-ck-desc="` +
                        (!i.more_input_label ? "" : i.more_input_label) +
                        `" form="formRegistrasi"> <label style="margin-right:20px;" for="` +
                        e +
                        `">` +
                        g +
                        `</label>`;
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

function loadRekamMedik() {
    $.ajax({
        url: base_url + "/registrations/rekam-medik/explore",
        method: "GET",
        dataType: "json",
        success: function (data) {
            setTimeout(() => {
                $("#f-load-rekam-medik").removeClass("m-b-2");
                $("#f-load-rekam-medik").html(content_rekam_medik(data));

                $(".noMember").removeClass("on-dutty-off loading-member-reg");

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

function load_formRight() {
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
                form_attribut_right();

                cont.prepend(
                    '<div id="f-load-rekam-medik" class="m-b-2"><em class="fa fa-spin fa-spinner"></em> Loading...</div>'
                );

                setTimeout(() => {
                    loadRekamMedik();
                    submit();
                }, 500);
            }
        }
    );
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
                                    (dataTrans.layanan[lyn].ruangan
                                        ? dataTrans.layanan[
                                              lyn
                                          ].ruangan.substring(0, 8)
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
                                        (dataTrans.paket[pketl].ruangan
                                            ? dataTrans.paket[
                                                  pketl
                                              ].ruangan.substring(0, 8)
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
                    onHidden: function () {
                        location.reload();
                    },
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
                                window.location.reload();
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

function toSendPembayaran(event, onsave) {
    var target = base_url + "/monitoring/order/print";
    var idCetak = event;

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    //update ini sek
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
                    toastr.success(data.msg, "Success!", {
                        timeOut: 3000,
                        onHidden: function () {
                            localStorage.setItem("error-print", "");
                            window.location.reload();
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
    var idCetak = event;

    $.ajax({
        url: target + "/" + idCetak + (printAct ? "?printact=yes" : ""),
        type: "GET",
        success: function (data) {
            rePrint(event, data, onsave);
        },
    });
}

function submit() {
    var saveIts = $("form#formRegistrasi").closest("form")[0].saveit;
    $(saveIts).on("click", function (e) {
        saveIt();
        return false;
    });

    var savePrints = $("form#formRegistrasi").closest("form")[0].saveprint;
    $(savePrints).on("click", function (e) {
        savePrint();
        return false;
    });
}

function savePrint() {
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
                    toPrint(data.idTrans, "on-save", "print-act");
                    toastr.success(data.msg, "Success!", {
                        timeOut: 5000,
                        onHidden: function () {
                            // location.reload();
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

$(document).ready(function () {
    load_formLeft();
    load_formRight();

    $(".f-paket").select2({
        placeholder: "Please select!",
        allowClear: true,
        theme: "bootstrap",
    });
    select_members();
});
