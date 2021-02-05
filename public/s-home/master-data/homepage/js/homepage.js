$(".load-data").delegate(".edit", "click", function (e) {
    var event = $(this);
    load_formEdit(event);
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

$(".reload-on-table").on("click", function () {
    var ins = $("input[name=id]").val() || 0;
    var response_load_dt = function () {
        $(".btn-id-" + ins)
            .addClass("disabled")
            .removeAttr("data-route");
    };
    $("tbody").closest("table").DataTable().ajax.reload(response_load_dt);
});

$(".button-action").delegate(".cancel-form", "click", function () {
    load_formAdd();
    refresh_action_table();
});

function load_formAdd() {
    var cont = $(".load-form");
    $(".display-future").addClass("blocking-content");
    cont.load(base_url + "/homepages/add", function (e, s, f) {
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
    var ths = $(e);
    $(".display-future").addClass("blocking-content");

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
            form_attribut();
            submit(dTbPageInfo);
        },
        error: function () {
            toastr.error("Gagal mengambil data", "Oops!", {
                timeOut: 2000,
            });
        },
    });
}

function changeProfile() {
    $("#file").click();
}

function readPreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $("#preview_image").attr("src", e.target.result);
            $("#file_name").val(e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeFile() {
    if ($("#file_name").val() != "") {
        $("#preview_image").attr("src", base_url + "/images/noimage.jpg");
        $("#file_name").val("");
        $("#file").val("");
    }
}

function form_attribut() {
    setTimeout(function () {
        $(function () {
            CKEDITOR.replace("deskripsi", {
                height: "25em",
            });
        });
    }, 1000);

    $("#file").change(function () {
        if ($(this).val() != "") {
            var file = this.files[0];
            var imagefile = file.type;
            var match = ["image/jpeg", "image/png", "image/jpg"];
            if (
                !(
                    imagefile == match[0] ||
                    imagefile == match[1] ||
                    imagefile == match[2]
                )
            ) {
                $("#preview_image").attr(
                    "src",
                    base_url + "/images/noimage.jpg"
                );
                $("#file_name").val("");
                $("#file").val("");
                var fls =
                    "Pilih gambar yang sesuai!, hanya diperbolehkan format jpeg, jpg and png!</ul>";
                toastr.warning(fls, "Oops!", {
                    timeOut: 2000,
                });
                return false;
            } else {
                readPreview(this);
            }
        }
    });
}

function load_data() {
    var cont = $(".load-data");
    $(".button-action").addClass("hide");
    $(".display-future").addClass("blocking-content");
    cont.load(base_url + "/homepages/data", function (e, s, f) {
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
            url: base_url + "/homepages/json",
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
                data: "video",
                name: "video",
                render: getVideo,
                className: "td-height-img",
            },
            {
                data: "gambar",
                name: "gambar",
                render: getImg,
                className: "text-center td-height-img",
            },
            {
                data: "judul",
                name: "nama",
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
    dTable.ajax.reload();

    $("select[name=data-table-view_length]").on("change", function () {
        dTable.ajax.reload(response_load_dt);
    });
    $("input[type=search]").on("input", function (e) {
        dTable.ajax.reload(response_load_dt);
    });

    remove();
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
        : "/storage/master-data/home-page/uploads/" + data;
    return (
        '<img onerror="imgError(this);" width="100" height="55" src="' +
        base_url +
        img +
        '">'
    );
}

function getVideo(url) {
    return (
        `<a href="https://www.youtube.com/watch?v=` +
        url +
        `" target="_blank"><img alt="" width="100" height="55" src="https://i.ytimg.com/vi/` +
        url +
        `/hqdefault.jpg"></a>`
    );
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
    $("form#formHomePage").submit(function (e) {
        e.preventDefault();
        CKEDITOR.instances["deskripsi"].updateElement();

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
