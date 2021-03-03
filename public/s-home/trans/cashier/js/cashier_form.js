function select_member() {
    $.ajax({
        url: base_url + "/trans/show/member",
        method: "GET",
        dataType: "json",
        success: function (data) {
            var html = `<option></option>`;
            var i;
            for (i = 0; i < data.length; i++) {
                html +=
                    `<option value='` +
                    data[i].id +
                    `'>` +
                    data[i].no_member +
                    ` | ` +
                    data[i].nama +
                    `</option>`;
            }
            $("select[name=no_member]").html(html);
        },
    });
}

function select_(table) {
    var token = $("meta[name=csrf-token]").attr("content");
    $.ajax({
        url: base_url + "/cashiers/form/option",
        method: "POST",
        data: {
            table: table,
            _token: token,
        },
        dataType: "json",
        success: function (data) {
            var html = `<option></option>`;
            var i;
            for (i = 0; i < data.length; i++) {
                var percents =
                    table == "voucher"
                        ? `data-diskon="` + data[i].diskon + `"`
                        : "";
                var nomDiskon =
                    table == "diskon"
                        ? `data-param="` +
                          data[i].param +
                          `" data-nominal="` +
                          data[i].nominal +
                          `"`
                        : "";
                html +=
                    `<option ` +
                    percents +
                    ` ` +
                    nomDiskon +
                    ` value='` +
                    data[i].id +
                    `'>` +
                    data[i].nama +
                    `</option>`;
            }
            $("select#" + table).html(html);
        },
    });
}

function select_members() {
    return `<select name="no_member" class="select2 form-control input-sm" style="width: 100%;" required></select>`;
}

function input_member() {
    var html = "";
    html += `<input type="text" name="no_member" class="form-control input-sm" placeholder="No Member..." required>`;
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
        (data ? data.nama : "") +
        `" class="form-control input-sm" placeholder="Nama..." required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email: <em class="text-danger">*</em></label>
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </div>
                        <input type="email" name="email" value="` +
        (data ? data.email : "") +
        `" class="form-control input-sm" placeholder="Email..." required>
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
        `" class="form-control input-sm" placeholder="Telepon..." required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Alamat: <em class="text-danger">*</em></label>
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon">
                            <i class="fa fa-home"></i>
                        </div>
                    <textarea name="alamat" cols="15" rows="4" class="form-control add-style input-sm" style="height:50px;" placeholder="Alamat...">` +
        (data ? data.alamat : "") +
        `</textarea>
                </div>
            </div>`;
    return html;
}

function dt_row() {
    //variable element
    let thisElem = $(".point-nom");
    var numb = thisElem.length + 1;
    //end variable element
    //html
    var markup = `<tr class="point-nom">`;
    markup +=
        `<td class="nom" style="vertical-align:middle;">` + numb + `</td>`;
    markup += `<td class="select input-group-sm" style="vertical-align:middle;">`;
    markup +=
        `<select style="width:100%;" class="select2 form-control produk-slct-avail" id="on-prd-` +
        numb +
        `"></select>
    <input type="hidden" name="produk[]" form="formKasir" id="on-prd-value-` +
        numb +
        `"></td>`;
    markup +=
        `<td class="prc text-center" style="vertical-align:middle;"><em id="prc-` +
        numb +
        `" class="on-harga">0</em></td>`;
    markup += `<td class="qty text-center input-group-sm" style="vertical-align:middle;">`;
    markup +=
        `<input disabled name="jml_produk[]" id="qty-` +
        numb +
        `" type="number" class="form-control on-qty input-sm" value="1"  min="1" form="formKasir"></td>`;
    // markup += `<td class="disc text-center" style="vertical-align:middle;"><em id="disc-` + numb + `" class="on-disc">0</em></td>`
    markup +=
        `<td class="subtotal text-center" style="vertical-align:middle;"><em id="subtotal-` +
        numb +
        `" class="subtotal">0</em></td>`;
    markup += `<td style="vertical-align:middle;"><em class="fa fa-times delete-rows text-danger"></em></td>`;
    markup += `</tr>`;
    //end html
    setTimeout(() => {
        load_avail_produk(numb);
    }, 500);
    return markup;
}

function formatState(state) {
    if (!state.id) {
        return state.text;
    }
    var img_load = "/s-home/master-data/product/uploads/";
    var img =
        state.element.attributes[0].value == "null"
            ? "/images/noimage.jpg"
            : img_load + state.element.attributes[0].value;

    var $state = $(
        '<span><img width="70" height="50" src="' +
            base_url +
            img +
            '" /> ' +
            state.text +
            "</span>"
    );

    return $state;
}

function load_avail_produk(numb, Id) {
    var id_ = Id || "";
    $("select#on-prd-" + numb).select2({
        placeholder: "Please select!",
        allowClear: true,
        templateResult: formatState,
        theme: "bootstrap",
    });
    $.ajax({
        url: base_url + "/trans/show/produk",
        method: "GET",
        dataType: "json",
        success: function (data) {
            var html = `<option></option>`;
            var i;
            for (i = 0; i < data.length; i++) {
                var selected = data[i].id == id_ ? "selected" : "";
                html +=
                    `<option ` +
                    selected +
                    ` data-gambar='` +
                    data[i].gambar +
                    `' data-harga='` +
                    data[i].harga_jual +
                    `' data-harga-member='` +
                    data[i].harga_jual_member +
                    `' value='` +
                    data[i].id +
                    `'>` +
                    data[i].nama +
                    `</option>`;
            }
            $("select#on-prd-" + numb).html(html);
            if (id_ && $("select#on-prd-" + numb).val() == id_) {
                setTimeout(() => {
                    $("select#on-prd-" + numb).prop("disabled", true);
                    $("input#on-prd-value-" + numb).val(
                        $("input[name=id]").data("produk")[numb - 1]
                    );
                    $("em#prc-" + numb).text(
                        $("input[name=id]").data("produk-harga")[numb - 1]
                    );
                    $("input#qty-" + numb)
                        .removeAttr("disabled")
                        .val(
                            $("input[name=id]").data("produk-jumlah")[numb - 1]
                        );

                    totalan();
                    loadTotal();
                }, 500);
            }
        },
    });
}

function load_formLeft() {
    var cont = $(".load-form-left");
    $(".display-future").addClass("blocking-content");
    cont.load(base_url + "/cashiers/create?form=left", function (e, s, f) {
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

function load_formRight(ev) {
    var cont = $(".load-form-right");
    $(".display-future").addClass("blocking-content");
    cont.load(base_url + "/cashiers/create?form=right", function (e, s, f) {
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
            submit(ev);
        }
    });
}

function load_formRightOrder() {
    var cont = $(".load-form-right-order");
    $(".display-future").addClass("blocking-content");
    cont.load(base_url + "/cashiers/form-right", function (e, s, f) {
        if (s == "error") {
            var fls = "Gagal memuat form!";
            toastr.error(fls, "Oops!", {
                timeOut: 2000,
            });
            cont.html(fls);
        } else {
            $(".display-future").removeClass("blocking-content");
            $(".button-action").removeClass("hide");

            $(".f-layanan-tambahan").removeClass("hide").removeAttr("style");

            setTimeout(function () {
                $(".clean-sheet").removeClass("on-dutty-off");
            }, 500);

            form_attribut_rightOrder();
        }
    });
}

function form_attribut() {
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
            $("input[name=no_member]").val("Loading...");
            var token = $("meta[name=csrf-token]").attr("content");
            $.ajax({
                url: base_url + "/registrations/member/generate",
                method: "POST",
                data: {
                    id: "new",
                    _token: token,
                },
                dataType: "json",
                success: function (data) {
                    $("input[name=no_member]").val(data.auto);
                },
            });
        });

        show_on_form_right_kasir(0, "");
    });

    $("div.change-to-field").delegate("div.use", "click", function (e) {
        var attr_ = $(".f-input-an");
        var attr_m = $(".f-new-member");
        var chg_ = $(this);
        chg_.removeClass("bg-blue use").addClass("bg-green add");
        chg_.find("i").removeClass("fa-search").addClass("fa-plus");
        attr_.html(select_members());
        select_member();
        attr_m.html("");
        $(".select2").select2({
            placeholder: "Please select!",
            allowClear: true,
            theme: "bootstrap",
        });
        $(".auto-nom").addClass("hide");
    });

    select_member();

    $(".select2").select2({
        placeholder: "Please select!",
        allowClear: true,
        theme: "bootstrap",
    });

    $("em.f-input-an").delegate(
        "select[name=no_member]",
        "change",
        function (e) {
            $(".f-new-member").html(
                '<div class="row"><div class="text-center"><em class="fa fa-spin fa-spinner"></em> loading...</div></div>'
            );
            var id = $(this).val();
            if (!id) {
                show_on_form_right_kasir(0, "");
                $(".f-new-member").html("");
                return false;
            }

            show_on_form_right_kasir(0, id);

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
                    $(".f-new-member").html(f_member(data[0]));
                },
            });
        }
    );
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

function form_attribut_right() {
    if (!$("input[name=id]").val()) {
        $("table tbody.data-product").append(dt_row);
    }

    // add row
    $(".add-rows").delegate(".add-row", "click", function () {
        $("table tbody.data-product").append(dt_row);
        $("#add-row").addClass("disabled");
        $("#add-row").removeClass("add-row");
    });

    // Find and remove selected table rows
    $("table tbody").delegate(
        "tr > td > em.delete-rows",
        "click",
        function (e) {
            var valueBeforeDelete = $(this)
                .parents("tr")
                .find("em.subtotal")
                .text();
            $(".total-belanja").text(
                parseInt($(".total-belanja").text()) -
                    parseInt(valueBeforeDelete)
            );

            $(this).parents("tr").remove();
            var total_all = 0;
            var count_valin = 0;
            var trs = $(".data-product tr.point-nom");
            trs.each(function (e, f) {
                $(trs[e])
                    .find("td.nom")
                    .text(e + 1);
                $(trs[e])
                    .find("td.select")
                    .find("select")
                    .removeAttr("id")
                    .attr("id", "on-prd-" + (e + 1));
                $(trs[e])
                    .find("td.select")
                    .find("input")
                    .removeAttr("id")
                    .attr("id", "on-prd-value-" + (e + 1));
                $(trs[e])
                    .find("td.prc")
                    .find("em")
                    .removeAttr("id")
                    .attr("id", "prc-" + (e + 1));
                $(trs[e])
                    .find("td.qty")
                    .find("input")
                    .removeAttr("id")
                    .attr("id", "qty-" + (e + 1));
                $(trs[e])
                    .find("td.subtotal")
                    .find("em")
                    .removeAttr("id")
                    .attr("id", "subtotal-" + (e + 1));

                var valin = $(trs[e])
                    .find("select#on-prd-" + (e + 1) + " option:selected")
                    .val();
                if (valin !== "") {
                    count_valin++;
                }
                var total = $(trs[e]).find("td.subtotal").find("em").text();
                total_all += parseInt(total);
            });
            if (trs.length == 0 || trs.length === count_valin) {
                $("#add-row").removeClass("disabled");
                $("#add-row").addClass("add-row");
            }

            $(".total-belanja-produk").text(total_all);
            loadTotal();

            var total_temp =
                parseInt($("input[name=id]").data("total-biaya")) +
                parseInt(total_all);
            var voucherInUse = $("#voucher")
                .find("option:selected")
                .data("diskon");
            var AfDisk = 0;

            if ($("#voucher").val()) {
                AfDisk += parseInt(total_temp * voucherInUse) / 100;
                $("#voucher").attr("temp-diskon", AfDisk);
                setTimeout(() => {
                    $(".total-belanja").text(
                        parseInt(total_temp) - parseInt(AfDisk)
                    );
                }, 0);
            } else {
                $(".total-belanja").text(
                    parseInt(total_temp) +
                        parseInt(
                            $("#voucher").attr("temp-diskon")
                                ? $("#voucher").attr("temp-diskon")
                                : 0
                        )
                );
                $("#voucher").removeAttr("temp-diskon");
            }
        }
    );

    //isian jumlah dan harga
    $("table tbody").delegate("tr > td", "input change", function (e) {
        totalan();

        var total_temp =
            parseInt($("input[name=id]").data("total-biaya")) +
            parseInt(
                $(".total-belanja-produk").text()
                    ? $(".total-belanja-produk").text()
                    : 0
            );
        var voucherInUse = $("#voucher").find("option:selected").data("diskon");
        var AfDisk = 0;

        if ($("#voucher").val()) {
            AfDisk += parseInt(total_temp * voucherInUse) / 100;
            $("#voucher").attr("temp-diskon", AfDisk);
            setTimeout(() => {
                $(".total-belanja").text(
                    parseInt(total_temp) - parseInt(AfDisk)
                );
            }, 0);
        } else {
            $(".total-belanja").text(
                parseInt(total_temp) +
                    parseInt(
                        $("#voucher").attr("temp-diskon")
                            ? $("#voucher").attr("temp-diskon")
                            : 0
                    )
            );
            $("#voucher").removeAttr("temp-diskon");
        }

        loadTotal();
    });

    //isian jumlah dan harga
    $("table tbody").delegate("tr > td > select", "change click", function (e) {
        var fls = peringatan($(this));
        if (fls > 0) {
            return false;
        }
        show_on_form_right_kasir(fls, "");
    });

    select_("diskon");
    select_("voucher");
    select_("cara_bayar");

    $("#diskon,#voucher,#cara_bayar").select2({
        placeholder: "Please select!",
        allowClear: true,
        theme: "bootstrap",
    });

    $("#cara_bayar").on("change", function (e) {
        var Id = $(this).val();
        if (Id) {
            if (Id == 2) {
                select_("bank_select");
                select_("cara_bayar_select");
                $("#content-bayar").html(bank_select);
                $("#content-methode-bayar").html(cara_bayar_select);
                $("#bank_select,#cara_bayar_select").select2({
                    placeholder: "Please select!",
                    allowClear: true,
                    theme: "bootstrap",
                });
            } else {
                $("input[name=bayar]")
                    .removeAttr("readonly")
                    .removeAttr("disabled");
                $("#content-bayar").html("");
                $("#content-methode-bayar").html("");
            }
        } else {
            $("input[name=bayar]")
                .attr("readonly", true)
                .attr("disabled", true);
            $("#content-bayar").html("");
            $("#content-methode-bayar").html("");
        }
    });

    $("#content-methode-bayar").delegate(
        "#cara_bayar_select",
        "change",
        function () {
            var Id = $(this).val();

            if (Id) {
                select_("bank_select");
                $("#content-bayar").html(bank_select);
                $("#bank_select").select2({
                    placeholder: "Please select!",
                    allowClear: true,
                    theme: "bootstrap",
                });
                $("input[name=bayar]")
                    .removeAttr("readonly")
                    .removeAttr("disabled");
            } else {
                $("input[name=bayar]")
                    .attr("readonly", true)
                    .attr("disabled", true);
                $("#content-bayar").html("");
            }
        }
    );

    $("#voucher").on("change", function () {
        var Elm = $(this);
        var total_all_belanja = $(".total-belanja");

        if (Elm.val()) {
            var AfterDiskon =
                (parseInt(Elm.find("option:selected").data("diskon")) *
                    parseInt(total_all_belanja.text())) /
                100;
            total_all_belanja.text(
                parseInt(total_all_belanja.text()) - parseInt(AfterDiskon)
            );
            Elm.attr("temp-diskon", AfterDiskon);
        } else {
            total_all_belanja.text(
                parseInt(total_all_belanja.text()) +
                    parseInt(
                        Elm.attr("temp-diskon") ? Elm.attr("temp-diskon") : 0
                    )
            );
            Elm.removeAttr("temp-diskon");
        }
    });

    $("input[name=bayar]").on("input", function (e) {
        var bayrValue = parseInt($(this).val().replace(/^0/gi, ""));
        $(this).val(bayrValue);

        if (isNaN(bayrValue)) {
            $(this).val("0");
            return false;
        }

        if (bayrValue < 1) {
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
        var TotalBelanja_ = parseInt($(".total-belanja").text());
        var hitungan = parseInt(bayrValue) - parseInt(TotalBelanja_);

        if (hitungan >= 0) {
            $(".kembalian-belanja").text(hitungan);
            $("input[name=kembalian]").val(hitungan);
            $("button[type=submit]")
                .removeAttr("disabled")
                .attr("form", "formKasir");
        } else {
            $(".kembalian-belanja").text("0");
            $("input[name=kembalian]").val("0");
            $("button[type=submit]").attr("disabled", true).removeAttr("form");
        }
    });

    $("#diskon").on("change", function (e) {
        loadTotal();
    });
}

function cara_bayar_select() {
    var html = `<div class="col-md-5 col-sm-5 col-xs-5">
                <span class="pull-right">
                    <h4><strong>Metode Bayar</strong></h4>
                </span>
            </div>

            <div class="col-md-7 col-sm-7 col-xs-7 input-group-sm">
                <select form="formKasir" name="cara_bayar_select" id="cara_bayar_select" class="form-control input-sm" style="width:100%;"></select>
            </div>`;

    return html;
}

function bank_select() {
    var html = `<div class="col-md-5 col-sm-5 col-xs-5">
                <span class="pull-right">
                    <h4><strong>Nomor Kartu</strong></h4>
                </span>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-4 input-group-sm">
                <select form="formKasir" name="bank_select" id="bank_select" class="form-control input-sm" style="width:100%;"></select>
            </div>

            <div class="col-md-3 col-sm-3 col-xs-3 input-group-sm">
                <input form="formKasir" placeholder="Nomor kartu..." name="nomor_kartu" id="nomor_kartu" class="form-control input-sm" style="width:100%;"></select>
            </div>`;

    return html;
}

function totalan() {
    var trs = $(".data-product tr.point-nom");
    var total_all = 0;

    trs.each(function (e, f) {
        var harga = $(trs[e]).find("em.on-harga").text();
        var qty = $(trs[e]).find("input.on-qty").val();
        var sub_harga = $(trs[e]).find("em.subtotal");
        var total = harga * qty;
        sub_harga.text(total);

        total_all += total;
    });
    $(".total-belanja-produk").text(total_all);
}

function show_on_form_right_kasir(fals, f_left) {
    var trs = $(".data-product tr.point-nom");
    trs.each(function (e, f) {
        var member_or_no = $("select[name=no_member]").val();
        var field_harga = $(trs[e]).find(
            "select#on-prd-" + (e + 1) + " option:selected"
        );
        var harga =
            !member_or_no && !f_left
                ? field_harga.data("harga")
                : field_harga.data("harga-member");

        var cek = $(trs[e])
            .find("select#on-prd-" + (e + 1) + " option:selected")
            .val();
        if (!cek) {
            $("#add-row").addClass("disabled");
            $("#add-row").removeClass("add-row");
            back_true(e);
        } else {
            $("#add-row").removeClass("disabled");
            $("#add-row").addClass("add-row");
            $(trs[e])
                .find("select#on-prd-" + (e + 1))
                .prop("disabled", true);
            if (fals == 0) {
                $("#on-prd-value-" + (e + 1)).val(field_harga.val());
                $("#prc-" + (e + 1)).text(harga);
                $("#qty-" + (e + 1)).removeAttr("disabled");
                $("#subtotal-" + (e + 1)).text(harga);

                totalan();
                loadTotal();
            }
        }
    });

    var total_temp =
        parseInt($("input[name=id]").data("total-biaya")) +
        parseInt(
            $(".total-belanja-produk").text()
                ? $(".total-belanja-produk").text()
                : 0
        );
    var voucherInUse = $("#voucher").find("option:selected").data("diskon");
    var AfDisk = 0;

    if ($("#voucher").val()) {
        AfDisk += parseInt(total_temp * voucherInUse) / 100;
        $("#voucher").attr("temp-diskon", AfDisk);
        setTimeout(() => {
            $(".total-belanja").text(parseInt(total_temp) - parseInt(AfDisk));
        }, 0);
    } else {
        $(".total-belanja").text(
            parseInt(total_temp) +
                parseInt(
                    $("#voucher").attr("temp-diskon")
                        ? $("#voucher").attr("temp-diskon")
                        : 0
                )
        );
        $("#voucher").removeAttr("temp-diskon");
    }
}

function submit(evv) {
    var saveIts = $("form#formKasir").closest("form")[0].saveit;
    $(saveIts).on("click", function (e) {
        saveIt();
        return false;
    });

    var savePrints = $("form#formKasir").closest("form")[0].saveprint;
    $(savePrints).on("click", function (e) {
        savePrint(evv);
        return false;
    });
}

function savePrint(evv) {
    var event = $("form#formKasir")[0];

    var caraBayar1 = $("#cara_bayar").val();
    var caraBayar = $("#cara_bayar_select").val();
    if (!caraBayar1) {
        toastr.warning("Pilih cara bayar!", "Peringatan!", {
            timeOut: 2000,
        });
        return false;
    }

    if (!caraBayar && caraBayar1 == 2) {
        toastr.warning("Pilih metode bayar!", "Peringatan!", {
            timeOut: 2000,
        });
        return false;
    }

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
                            toPrint(evv, "on-save");
                            setTimeout(() => {
                                $(".modal").modal("hide");
                                $(".preloader").fadeOut();
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

function saveIt() {
    var event = $("form#formKasir")[0];

    var caraBayar1 = $("#cara_bayar").val();
    var caraBayar = $("#cara_bayar_select").val();
    if (!caraBayar1) {
        toastr.warning("Pilih cara bayar!", "Peringatan!", {
            timeOut: 2000,
        });
        return false;
    }

    if (!caraBayar && caraBayar1 == 2) {
        toastr.warning("Pilih metode bayar!", "Peringatan!", {
            timeOut: 2000,
        });
        return false;
    }

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
});

function peringatan(value) {
    var failin = 0;
    var trs = $(".data-product tr.point-nom").length;
    for (var ii = 0; ii < trs; ii++) {
        var cek = $("select#on-prd-" + ii + " option:selected").val();
        if (cek === value.val() && cek !== "") {
            failin++;
            var name = $("select#on-prd-" + ii + " option:selected").text();
            var content = document.createElement("div");
            content.innerHTML =
                "Duplikasi pilihan <strong>" + name + "</strong> !";
            swal({
                title: "Ooopps!!...",
                content: content,
                icon: "warning",
            });
            setTimeout(function () {
                $(value).parents("tr").remove();
                $("#add-row").addClass("disabled");
                $("#add-row").removeClass("add-row");
                setTimeout(() => {
                    $("table tbody.data-product").append(dt_row);
                    var total_all = 0;
                    var trs = $(".data-product tr.point-nom");
                    trs.each(function (e, f) {
                        $(trs[e])
                            .find("td.nom")
                            .text(e + 1);
                        $(trs[e])
                            .find("td.select")
                            .find("select")
                            .removeAttr("id")
                            .attr("id", "on-prd-" + (e + 1));
                        $(trs[e])
                            .find("td.select")
                            .find("input")
                            .removeAttr("id")
                            .attr("id", "on-prd-value-" + (e + 1));
                        $(trs[e])
                            .find("td.prc")
                            .find("em")
                            .removeAttr("id")
                            .attr("id", "prc-" + (e + 1));
                        $(trs[e])
                            .find("td.qty")
                            .find("input")
                            .removeAttr("id")
                            .attr("id", "qty-" + (e + 1));
                        $(trs[e])
                            .find("td.subtotal")
                            .find("em")
                            .removeAttr("id")
                            .attr("id", "subtotal-" + (e + 1));

                        var total = $(trs[e])
                            .find("td.subtotal")
                            .find("em")
                            .text();
                        total_all += parseInt(total);
                    });
                    $(".total-belanja-produk").text(total_all);

                    $(".total-belanja").text(
                        parseInt($("input[name=id]").data("total-biaya")) +
                            parseInt($(".total-belanja-produk").text())
                    );
                }, 0);
            }, 0);
        }
    }
    return failin;
}

function form_attribut_rightOrder() {
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
                        $(trs[e])
                            .find("td.select-terapis")
                            .find("select")
                            .removeAttr("id")
                            .attr("id", "on-select-terapis-" + (e + 1));
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
                    <select name="paket[]" form="formKasir" class="select2 form-control input-group-sm" disabled id="on-select-paket-` +
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

        $("#on-select-layanan-" + numb).on("change", function (e) {
            var layId = $(this).val();
            // loadTotal(layId);
            if (layId) {
                // $("#on-select-terapis-" + numb).removeAttr("disabled");
                $("#on-select-price-custom-" + numb).removeAttr("disabled");
                // load_avail_layanan("terapis", numb, layId);
            } else {
                // var trps = $("#on-select-terapis-" + numb);
                // trps.attr("disabled", true);
                // trps.val("").trigger("change");

                var prc_cus = $("#on-select-price-custom-" + numb);
                prc_cus.attr("disabled", true);
                prc_cus.val("").trigger("change");
            }
        });
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
                    <select name="layanan[]" form="formKasir" class="select2 form-control input-group-sm" disabled id="on-select-layanan-` +
        numb +
        `"></select>
                </div>
            </td>`;
    // html += `<td class="select-terapis td-height-img">
    //             <div class="input-group-sm">
    //                 <select name="terapis[]" form="formKasir" class="select2 form-control input-group-sm" disabled id="on-select-terapis-` + numb + `"></select>
    //             </div>
    //         </td>`;

    html +=
        `<td class="select-terapis input-price-custom td-height-img">
                    <div class="input-group-sm">
                        <input name="harga_custom[]" placeholder="Harga" type="rupiah" form="formKasir" class="select2 form-control input-group-sm" disabled id="on-select-price-custom-` +
        numb +
        `"></select>
                    </div>
                </td>`;

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
        } else {
            load_avail_layanan("layanan", numb);
        }

        $("#on-select-layanan-" + numb).on("change", function (e) {
            var layId = $(this).val();

            loadTotal(layId);
            if (layId) {
                $("#on-select-terapis-" + numb).removeAttr("disabled");
                load_avail_layanan("terapis", numb, layId);
            } else {
                var trps = $("#on-select-terapis-" + numb);
                trps.attr("disabled", true);
                trps.val("").trigger("change");
            }
        });
    }, 500);

    return html;
}

function load_row_produk(idProduk) {
    let thisElem = $(".point-nom");
    var numb = thisElem.length + 1;

    var markup = `<tr class="point-nom">`;
    markup +=
        `<td class="nom" style="vertical-align:middle;">` + numb + `</td>`;
    markup += `<td class="select input-group-sm" style="vertical-align:middle;">`;
    markup +=
        `<select style="width:100%;" class="select2 form-control produk-slct-avail" id="on-prd-` +
        numb +
        `"></select>
    <input type="hidden" name="produk[]" form="formKasir" id="on-prd-value-` +
        numb +
        `"></td>`;
    markup +=
        `<td class="prc text-center" style="vertical-align:middle;"><em id="prc-` +
        numb +
        `" class="on-harga">0</em></td>`;
    markup += `<td class="qty text-center input-group-sm" style="vertical-align:middle;">`;
    markup +=
        `<input disabled name="jml_produk[]" id="qty-` +
        numb +
        `" type="number" class="form-control on-qty input-sm" value="1"  min="1" form="formKasir"></td>`;
    // markup += `<td class="disc text-center" style="vertical-align:middle;"><em id="disc-` + numb + `" class="on-disc">0</em></td>`
    markup +=
        `<td class="subtotal text-center" style="vertical-align:middle;"><em id="subtotal-` +
        numb +
        `" class="subtotal">0</em></td>`;
    markup += `<td style="vertical-align:middle;"><em class="fa fa-times delete-rows text-danger"></em></td>`;
    markup += `</tr>`;
    //end html

    setTimeout(() => {
        if (idProduk) {
            load_avail_produk(
                numb,
                $("input[name=id]").data("produk")[idProduk - 1]
            );
        } else {
            load_avail_produk(numb);
        }

        $("select#on-prd-" + numb).select2({
            placeholder: "Please select!",
            allowClear: true,
            templateResult: formatState,
            theme: "bootstrap",
        });
    }, 500);

    return markup;
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
                    `<input type="hidden" form="formKasir" name="pkt_layanan_terapis[` +
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
                        `<optgroup id="` +
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
                $("#on-select-terapis-" + numb).removeAttr("disabled");
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
                    `][]" form="formKasir" class="select2 form-control input-group-sm" disabled
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

function back_true(e) {
    $("#prc-" + (e + 1)).text("0");
    $("#qty-" + (e + 1)).val("1");
    $("#subtotal-" + (e + 1)).val("0");
}
