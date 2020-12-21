function load_formSettingNota() {
    var cont = $(".load-form");
    $(".display-future").addClass('blocking-content');
    cont.load(base_url + '/set/notas/create', function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat form!';
            toastr.error(fls, 'Oops!', { timeOut: 2000 })
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            $(".display-future").removeClass('blocking-content');
            $(".button-action").removeClass('hide');
            setTimeout(function () {
                $('input').removeAttr('disabled');
                $(function () {
                    CKEDITOR.replace('contact_info', {
                        toolbarGroups: [{
                            "name": "basicstyles",
                            "groups": ["basicstyles"]
                        },
                        {
                            "name": "styles",
                            "groups": ["styles"]
                        },
                        ],
                        removeButtons: 'Strike,Subscript,Superscript,Anchor,Styles,Specialchar',
                        contentsCss: "body {font-size: 12px;}",
                        height: '8em',
                    });
                })
            }, 1500);
            form_attribut();
            submit();
        }
    });
}

function load_preview() {
    var cont = $(".load-preview");
    cont.load(base_url + '/set/notas/prev', function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat preview!';
            toastr.error(fls, 'Oops!', { timeOut: 2000 })
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        }
    });
}

function changeProfile() {
    $('#file').click();
}

function readPreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#preview_image').attr('src', e.target.result);
            $('#file_name').val(e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removeFile() {
    if ($('#file_name').val() != '') {
        $('#preview_image').attr('src', base_url + '/images/noimage.jpg');
        $('#file_name').val('');
        $("#file").val('');
    }
}

function form_attribut() {
    $('#file').change(function () {
        if ($(this).val() != '') {
            var file = this.files[0];
            var imagefile = file.type;
            var match = ["image/jpeg", "image/png", "image/jpg"];
            if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
                $('#preview_image').attr('src', base_url + '/images/noimage.jpg');
                $('#file_name').val('');
                $("#file").val('');
                var fls = "Pilih gambar yang sesuai!, hanya diperbolehkan format jpeg, jpg and png!</ul>";
                toastr.warning(fls, 'Oops!', { timeOut: 2000 })
                return false;
            } else {
                readPreview(this);
            }
        }
    });
}

function submit() {
    $("form#formSetNota").submit(function (e) {
        e.preventDefault();
        CKEDITOR.instances['contact_info'].updateElement();

        var event = $(this)[0];

        $(".display-future").addClass('blocking-content');
        var data = new FormData(event);
        var url = event.action;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });

        $.ajax({
            url: url,
            data: data,
            contentType: false,
            processData: false,
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
                switch (data.cd) {
                    case 200:
                        load_preview();
                        toastr.success(data.msg, 'Success!', {
                            timeOut: 2000,
                            onHidden: function () {
                                $(".display-future").removeClass('blocking-content');
                            }
                        })
                        break;
                    default:
                        $(".display-future").removeClass('blocking-content');
                        toastr.warning(data.msg, 'Peringatan!', { timeOut: 2000 })
                        break;
                }
            },
            error: function () {
                var timer = 5;// timer in seconds
                (function customSwal() {
                    swal({
                        title: "Kesalahan sistem!",
                        text: "Sistem error, menutup otomatis pada " + timer + ' detik !',
                        timer: timer * 1000,
                        button: false,
                        icon: base_url + '/images/icons/loader.gif',
                        closeOnClickOutside: false,
                        closeOnEsc: false
                    }).then(() => {
                        setTimeout(function () {
                            $(".display-future").removeClass('blocking-content');
                            swal.close()
                        }, 1000)
                    });

                    if (timer) {
                        timer--;
                        if (timer > 0) {
                            setTimeout(customSwal, 1000);
                        }
                    }
                })();
            }
        });
    });
}

$(document).ready(function () {
    load_preview();
    load_formSettingNota();
});
