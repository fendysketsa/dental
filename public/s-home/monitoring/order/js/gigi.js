function loadFile() {
    $("#file_gigi").click();

    $("#file_gigi").change(function () {
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
                $("#preview_image_gigi").attr(
                    "src",
                    base_url + "/images/noimage.jpg"
                );
                $("#file_gigi_name").val("");
                $("#file_gigi").val("");
                var fls =
                    "Pilih gambar yang sesuai!, hanya diperbolehkan format jpeg, jpg and png!</ul>";
                toastr.warning(fls, "Oops!", {
                    timeOut: 2000,
                });
                return false;
            } else {
                readPreview(this);

                return true;
            }
        }
    });
}

function loadFileTindakan() {
    $("#file_tindakan").click();

    $("#file_tindakan").change(function () {
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
                $("#preview_image_tindakan").attr(
                    "src",
                    base_url + "/images/noimage.jpg"
                );
                $("#file_gigi_tindakan").val("");
                $("#file_tindakan").val("");
                var fls =
                    "Pilih gambar yang sesuai!, hanya diperbolehkan format jpeg, jpg and png!</ul>";
                toastr.warning(fls, "Oops!", {
                    timeOut: 2000,
                });
                return false;
            } else {
                readPreviewTindakan(this);

                return true;
            }
        }
    });
}

function readPreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $("#preview_image_gigi").attr("src", e.target.result);
            $("#file_gigi_name").val(e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function readPreviewTindakan(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $("#preview_image_tindakan").attr("src", e.target.result);
            $("#file_tindakan_name").val(e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeFile() {
    if ($("#file_gigi_name").val() != "") {
        $("#preview_image_gigi").attr("src", base_url + "/images/noimage.jpg");
        $("#file_gigi_name").val("");
        $("#file_gigi").val("");
    }
}

function removeFileTindakan() {
    if ($("#file_tindakan_name").val() != "") {
        $("#preview_image_tindakan").attr("src", base_url + "/images/noimage.jpg");
        $("#file_tindakan_name").val("");
        $("#file_tindakan").val("");
    }
}

