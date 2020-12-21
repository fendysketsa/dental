function load_detail() {
    var cont = $(".load-detail");
    cont.load(base_url + '/profiles-detail', function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat detail!';
            toastr.error(fls, 'Oops!', {
                timeOut: 2000
            })
            $(this).html('<em class="fa fa-warning"></em> ' + fls);
        } else {
            form_attribut();
        }
    });
}

function load_formProfile() {
    var cont = $(".load-form");
    $(".display-future").addClass('blocking-content');
    cont.load(base_url + '/profiles/create', function (e, s, f) {
        if (s == 'error') {
            var fls = 'Gagal memuat form!';
            toastr.error(fls, 'Oops!', {
                timeOut: 2000
            })
            $(this).html('<div class="box-body"><em class="fa fa-warning"></em> ' + fls + '</div>');
        } else {
            $(".display-future").removeClass('blocking-content');
            $(".button-action").removeClass('hide');
            setTimeout(function () {
                $('input').removeAttr('disabled');
            }, 1500)
            submit();
        }
    });
}

function form_attribut() {
    document.getElementById("file-input").onchange = function () {
        var input = this;
        var max_height = 340;
        var max_width = 310;
        if (input.files && input.files[0]) {
            var fileReader = new FileReader();
            var imageFile = input.files[0];
            var imageSize = imageFile.size / 1048576

            if (imageFile.type == "image/png" || imageFile.type == "image/jpeg") {
                if (imageSize > 0.5) {
                    $("input[name=photo]").val('');
                    toastr.warning("Ukuran foto tidak boleh lebih dari 500 KB !", 'Oops!', {
                        timeOut: 2000
                    })
                    return false;
                } else {
                    fileReader.readAsDataURL(imageFile);
                    fileReader.onload = function (e) {

                        var image = new Image();
                        image.src = e.target.result;
                        image.onload = function () {
                            var inHeight = this.height;
                            var inWidth = this.width;
                            if (inHeight > max_height && inWidth > max_width) {
                                $("input[name=photo]").val('');
                                toastr.warning("Ukuran foto tidak boleh lebih dari 340px X 310px !", 'Oops!', {
                                    timeOut: 2000
                                })
                                return false;
                            } else {
                                $("input[name=photo]").val(e.target.result);
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                                    }
                                });
                                $.ajax({
                                    url: base_url + '/profiles-upload',
                                    data: $("#form-upload").serialize(),
                                    type: 'POST',
                                    dataType: 'JSON',
                                    success: function (json) {
                                        if (json.status == 1) {
                                            toastr.success("Foto berhasil diperbaharui...", 'Success!', {
                                                timeOut: 2000,
                                                onHidden: function () {
                                                    $('#preview-upload').attr("src", e.target.result);
                                                    $('.photo-profile-user').attr("src", e.target.result);
                                                }
                                            })
                                        } else {
                                            $("input[name=photo]").val('');
                                            toastr.error("Foto gagal diperbaharui...", 'Error!', {
                                                timeOut: 2000
                                            })
                                        }
                                    }
                                });

                            }
                        }
                    }
                }
            } else {
                $("input[name=photo]").val('');
                toastr.warning("Format foto yang diizinkan hanya jpg/png !", 'Oops!', {
                    timeOut: 2000
                });
                return false;
            }
        }
    };
}

function submit() {
    $("form#formProfile").submit(function (e) {
        e.preventDefault();

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
                        $(".user-panel").find('p').text(data.data.nama)
                        $(".user-panel").find('a').html('<i class="fa fa-circle text-aqua"></i>' + data.data.role)
                        $(".user-panel").find('p > small').text(data.data.role)
                        $(".user-menu").find('span.hidden-xs').text(data.data.nama)
                        $(".user-header").find('p').html(data.data.nama + '<small>' + data.data.role + '</small>')
                        $('.user-header').find('img').attr("src", base_url + '/storage/master-data/employee/uploads/' + data.data.foto);
                        $(".load-detail").find('.profile-username').text(data.data.nama);
                        $(".load-detail").find('p.det-mail').html('<small>' + data.data.email + '</small>');
                        $(".load-detail").find('p.det-jabatan').html('<small><span class="label label-info">' + data.data.jabatan + '</span></small>');
                        //$(".load-detail").find('p.det-address').text(data.data.email);
                        toastr.success(data.msg, 'Success!', {
                            timeOut: 2000,
                            onHidden: function () {
                                setTimeout(function () {
                                    $('input[type=password]').val('');
                                }, 1500);
                                $(".display-future").removeClass('blocking-content');
                            }
                        })
                        break;
                    default:
                        $(".display-future").removeClass('blocking-content');
                        toastr.warning(data.msg, 'Peringatan!', {
                            timeOut: 2000
                        })
                        break;
                }
            },
            error: function () {
                var timer = 5; // timer in seconds
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
    load_detail();
    load_formProfile();
});
