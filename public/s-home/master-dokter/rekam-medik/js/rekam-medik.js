$(".load-data").delegate(".edit", "click", function (e) {
    var event = $(this);
    load_formEdit(event);
});

$(".button-action").delegate(".cancel-form", "click", function () {
    load_formAdd();
    refresh_action_table();
});

function refresh_action_table() {
    var ths = $("tbody");
    var attrbt = ths.find("a.edit");
    attrbt.each(function (e, f) {
        $(f)
            .closest("tr")
            .find("a.btn-remove-id")
            .removeClass("disabled")
            .attr("data-route", $(f).data("route"));
    });
}

function load_formAdd() {
    var cont = $(".load-form");
    $(".display-future").addClass("blocking-content");
    cont.load(base_url + "/rekams/add", function (e, s, f) {
        if (s == "error") {
            var fls = "Gagal memuat form!";
            toastr.error(fls, "Oops!", {
                timeOut: 2000,
            });
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            $(".display-future").removeClass("blocking-content");
            $(".button-action").removeClass("hide");
            form_attribut();
            submit();
        }
    });
}

function load_formEdit(e) {
    var elemtTb = $("#data-table-view").DataTable();
    var dTbPageInfo = elemtTb.page.info().page;

    var cont = $(".load-form");
    $(".display-future").addClass("blocking-content");
    var ths = $(e);

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('input[name="_token"]').val(),
        },
    });

    $.ajax({
        type: "PUT",
        url: ths.data("route"),
        success: function (result) {
            refresh_action_table();
            cont.html(result);

            $(".display-future").removeClass("blocking-content");
            $(".button-action").removeClass("hide");
            var attrbt = ths.parents("div.btn-group").find("a.btn-remove-id");
            attrbt.addClass("disabled");
            attrbt.removeAttr("data-route");
            var editValue = $(".edit-rekam");

            form_attribut(editValue);

            submit(dTbPageInfo);
        },
        error: function () {
            toastr.error("Gagal mengambil data", "Oops!", {
                timeOut: 2000,
            });
        },
    });
}

function load_data() {
    var cont = $(".load-data");
    $(".button-action").addClass("hide");
    $(".display-future").addClass("blocking-content");
    cont.load(base_url + "/rekams/data", function (e, s, f) {
        if (s == "error") {
            var fls = "Gagal memuat data!";
            toastr.error(fls, "Oops!", {
                timeOut: 2000,
            });
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            $(".display-future").removeClass("blocking-content");
            $(".button-action").removeClass("hide");
            data_attribut();
        }
    });
}

function data_attribut() {
    var response_load_dt = function () {
        var ins = $("input[name=id]").val() || 0;
        $(".btn-id-" + ins)
            .addClass("disabled")
            .removeAttr("data-route");
    };

    var dTable = $("#data-table-view").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: base_url + "/rekams/json",
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
                data: "nama",
                name: "nama",
            },
            {
                data: "option",
                name: "option",
                render: convertOption,
            },
            {
                data: "set_input",
                name: "set_input",
                render: convertSetInput,
            },
            {
                data: "status",
                name: "status",
                render: convertStatus,
            },
            {
                data: "action",
                name: "action",
                orderable: false,
                className: "text-center",
            },
        ],
        order: [[0, "desc"]],
    });
    dTable.ajax.reload();
    $("select[name=data-table-view_length]").on("change", function () {
        dTable.ajax.reload(response_load_dt);
    });
    $("input[type=search]").on("input", function (e) {
        dTable.ajax.reload(response_load_dt);
    });
    remove();
}

function contPlaceholder(val) {
    var html = "";

    html +=
        `<div class="form-group">
                <label>Placeholder: <em class="text-danger">*</em></label>
                <div class="input-group input-group-sm date">
                    <div class="input-group-addon">
                        <i class="fa fa-tag"></i>
                    </div>
                    <input type="text" name="placeholder" value="` +
        (val && val.data("input-placeholder")
            ? val.data("input-placeholder")
            : "") +
        `" class="form-control"
                        placeholder="Placeholder..." form="formRekam">
                </div>
            </div>`;

    html +=
        `<div class="form-group">
                <label>Label: </label>
                <div class="input-group input-group-sm date">
                    <div class="input-group-addon">
                        <i class="fa fa-tag"></i>
                    </div>
                    <textarea name="label" style="height:80px;" class="form-control"
                        placeholder="Label..." form="formRekam">` +
        (val && val.data("input-label") ? val.data("input-label") : "") +
        `</textarea>
                </div><small id="emailHelp" class="form-text text-info"><em class="fa fa-info-circle"></em> Isikan label dan sesuaikan dengan pilihan, tekan Enter untuk memisahkan label apabila lebih dari satu.</small>
            </div>`;

    return html;
}

function form_attribut(e) {
    $("#tambahan").on("click", function () {
        var isCek = $("input[name=tambahan_input]").is(":checked");

        if (isCek == true) {
            $("#set_input").prop("disabled", true);
            $(".more-placeholder").html(contPlaceholder());

            $("#set_input").prop("disabled", true);
            $("#set_input").prop("checked", true);
            $(".set-input-desc").html("Single");
        } else {
            $("#set_input").prop("disabled", false);
            $(".more-placeholder").html("");

            $("#set_input").prop("disabled", false);
        }
    });

    $("#status").on("click", function () {
        var isCek = $("input[name=status]").is(":checked");

        if (isCek == true) {
            $(".status-desc").html("Aktif");
        } else {
            $(".status-desc").html("Tidak Aktif");
        }
    });

    $("#set_input")
        .closest("label.container-radio")
        .on("click", function () {
            var isDis = $("input[name=set_input]").is(":disabled");

            if (isDis == true) {
                toastr.warning(
                    "Apabila mode tambahan input tidak diperkenankan mengganti set multi choise!"
                );
            }
        });

    $("#set_input").on("click", function () {
        var isCek = $("input[name=set_input]").is(":checked");

        if (isCek == true) {
            $(".set-input-desc").html("Single");
        } else {
            $(".set-input-desc").html("Multi");
        }
    });

    var edit = e || null;

    if (edit) {
        var isCek = edit.data("input");

        if (isCek) {
            $("#tambahan").prop("checked", true);
            $("#set_input").prop("disabled", true);
            $(".more-placeholder").html(contPlaceholder(edit));
        } else {
            $("#tambahan").prop("checked", false);
            $("#set_input").prop("disabled", false);
            $(".more-placeholder").html("");
        }

        var isCekSts = edit.data("status");

        if (isCekSts) {
            $("#status").prop("checked", true);
            $(".status-desc").html("Aktif");
        } else {
            $("#status").prop("checked", false);
            $(".status-desc").html("Tidak Aktif");
        }

        var isCekSetInp = edit.data("set-input");

        if (isCekSetInp) {
            $("#set_input").prop("checked", true);
            $(".set-input-desc").html("Single");
        } else {
            $("#set_input").prop("checked", false);
            $(".set-input-desc").html("Multi");
        }
    }
}

function convertOption(e) {
    var opt = e || "";

    if (opt) {
        var exp = e.split("\n");
        var htm = "";

        $.each(exp, function (e, i) {
            htm += "- " + i + "<br>";
        });

        return htm;
    }
}

function convertStatus(e) {
    var sts = e || "";

    var stsDesc = "";
    var tc = "";

    if (sts == 1) {
        stsDesc = "Aktif";
        tc = "label-success";
    } else {
        stsDesc = "Tidak Aktif";
        tc = "label-danger";
    }

    return `<em class="badge ` + tc + `">` + stsDesc + `</em>`;
}

function convertSetInput(e) {
    var setInp = e || "";

    var setInpDesc = "";
    var tc = "";

    if (setInp == 1) {
        setInpDesc = "Single";
        tc = "label-success";
    } else {
        setInpDesc = "Multi";
        tc = "label-info";
    }

    return `<em class="badge ` + tc + `">` + setInpDesc + `</em>`;
}

function remove() {
    $("tbody").delegate(".btn-remove-id", "click", function () {
        var event = $(this);
        var target = event.data("route");
        var tables = event.closest("table");

        var ins = $("input[name=id]").val() || 0;
        var response_load_dt = function () {
            $(".btn-id-" + ins)
                .addClass("disabled")
                .removeAttr("data-route");
        };

        swal({
            title: "Menghapus data?",
            text: "Data yang dihapus tidak bisa dikembalikan.",
            icon: "warning",
            buttons: ["Batal", "Ok"],
            dangerMode: true,
        }).then(function (willExec) {
            if (willExec) {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('input[name="_token"]').val(),
                    },
                });
                $.ajax({
                    url: target,
                    type: "DELETE",
                    dataType: "JSON",
                    success: function (data) {
                        switch (data.cd) {
                            case 200:
                                tables
                                    .DataTable()
                                    .ajax.reload(response_load_dt);
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
            } else {
                swal.close();
            }
        });
    });
}

function submit(page) {
    $("form#formRekam").submit(function (e) {
        e.preventDefault();

        var event = $(this)[0];

        $(".display-future").addClass("blocking-content");
        var data = new FormData(event);
        var url = event.action;

        var reload_form = function () {
            load_formAdd();
        };

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
                        $("table#data-table-view")
                            .DataTable()
                            .ajax.reload(reload_form);
                        if (page) {
                            $("table#data-table-view")
                                .DataTable()
                                .page(page)
                                .draw("page");
                        }
                        toastr.success(data.msg, "Success!", {
                            timeOut: 2000,
                        });
                        break;
                    default:
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

$(document).ready(function () {
    load_formAdd();
    load_data();
});
