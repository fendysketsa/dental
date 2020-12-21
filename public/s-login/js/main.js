$(document).ready(function () {
    $('.form-holder').delegate("input", "focus", function () {
        $('.form-holder').removeClass("active");
        $(this).parent().addClass("active");
    })

    $('.form-holder').find("input[name=password]").on("keyup", function (e) {
        $(this).attr('type', 'password')
    })

    //akan tampil apabila sudah lain hari akan tetapi tetap muncul sekali saja
    var asiaTime = new Date().toLocaleString("en-US", {
        timeZone: "Asia/Jakarta"
    });
    asiaTime = new Date(asiaTime);
    var NewVersionDate = asiaTime.getDate() + '-' + asiaTime.getMonth() + '-' + asiaTime.getFullYear();

    var isshow = localStorage.getItem('isshowMessVersion');
    var isTimeshow = localStorage.getItem('isshowTimeMessVersion');

    if (isshow == null || (isTimeshow == null || isTimeshow != NewVersionDate)) {

        localStorage.setItem('isshowMessVersion', 1);
        localStorage.setItem('isshowTimeMessVersion', NewVersionDate);

        var contMsg = document.createElement("div");
        contMsg.innerHTML = `<div>
                                <span>
                                    Tersedia fitur akunting untuk memudahkan keuangan Anda
                                </span>
                            </div>`;

        swal({
            title: "Apa yang baru",
            content: contMsg,
            icon: "info",
            buttons: false,
            dangerMode: false,
        });

        $(".swal-title").append('<span class="last-update">Last Update: 31 Maret 2020</span>');
        $(".last-update").css({
            "font-size": "10px",
            "display": "inherit"
        });
    }

    $(function () {

        if (localStorage.chkbx && localStorage.chkbx != '') {
            $('#remember').attr('checked', 'checked');
            $('#i-email').val(localStorage.usrname);
            $('#i-pass').val(localStorage.pass);
        } else {
            $('#remember').removeAttr('checked');
            $('#i-email').val('');
            $('#i-pass').val('');
        }

        $('#remember').click(function () {

            if ($('#remember').is(':checked')) {
                // save username and password
                localStorage.usrname = $('#i-email').val();
                localStorage.pass = $('#i-pass').val();
                localStorage.chkbx = $('#remember').val();
            } else {
                localStorage.usrname = '';
                localStorage.pass = '';
                localStorage.chkbx = '';
            }
        });
    });
});
