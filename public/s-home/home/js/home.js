$(".confirm-logout").on('click', function () {
    swal({
        title: "Keluar sistem?",
        text: "Anda akan diarahkan keproses logout.",
        icon: "warning",
        buttons: ["Batal", "Ok"],
        dangerMode: true,
    }).then(function (willExec) {
        if (willExec) {
            $(".preloader").fadeIn();
            $.ajax({
                url: base_url + '/logout',
                type: 'POST',
                data: '_token=' + $('meta[name="csrf-token"]').attr('content'),
                success: function () {
                    toastr.success('Logout Berhasil', 'Success!', {
                        timeOut: 2000,
                        onHidden: function () {
                            location.href = base_url;
                        }
                    });
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
                                $(".preloader").fadeOut();
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
        } else {
            swal.close()
        }
    });
})
