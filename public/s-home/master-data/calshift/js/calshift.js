$(document).ready(function () {
    calendar();
    cancel();

    setTimeout(function () {
        submit();
    }, 500);
});

function cancel() {
    $(".cancel-form-modal, button.close").on('click', function () {
        var modal = $('.modal');
        // $('#calendar').fullCalendar('refetchEvents');
        $('#calendar').fullCalendar('unselect');
        modal.find('input,select').val('').trigger('change');
        ReloadKalendar();
    });
}

function fullCalendars() {
    topRightButton()
    $('#calendar').fullCalendar({
        locale: 'id',
        header: {
            left: '',
            center: 'title',
            right: 'month,basicWeek,basicDay'
        },
        defaultDate: moment(),
        navLinks: true,
        selectable: true,
        selectHelper: true,
        select: function (date, endd) {
            var date_diff_indays = function (date, endd) {
                dt1 = new Date(date);
                dt2 = new Date(endd);
                return Math.floor((Date.UTC(dt2.getFullYear(), dt2.getMonth(), dt2.getDate()) - Date.UTC(dt1.getFullYear(), dt1.getMonth(), dt1.getDate())) / (1000 * 60 * 60 * 24));
            }

            if (date.isBefore(moment().add('days', -1))) {
                $('#calendar').fullCalendar('unselect');
                toastr.warning('Oops!, tidak diperbolehkan merubah data!', 'Peringatan!', {
                    timeOut: 2000
                });
                return false;
            }

            var filCabang = $(".filt-cabang").val();
            if (!filCabang) {
                swal('Ooopss!', 'Silakan pilih cabang buat menambah data shift!', 'info')
                return false;
            }

            if (date_diff_indays(date, endd) > 1) {
                $('#calendar').fullCalendar('unselect');
                toastr.warning('Oops!, Silakan masukan data per hari saja!', 'Peringatan!', {
                    timeOut: 2000
                });
                return false;
            }

            $('.modal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('.modal').modal('show');
            $('.modal-title').html('<em class="fa fa-pencil-square-o"></em> Form Kalendar Shift')
            $('.modal').find('.kal-shf-peg').val(date.format('DD-MM-YYYY')).attr('data-tanggal', date)
            select_('', 'shift');
            select_('', 'pegawai');

            $('.modal').find('div.ajukan-ijin').addClass('hide').find('input#ch-ijin').attr('disabled', true).removeAttr('form');
            $('.modal').find('div.ajukan-keterangan').addClass('hide').find('textarea.keterangan').attr('disabled', true).removeAttr('form').removeClass('required');
        },
        eventClick: function (event) {
            if (event.start.isBefore(moment().add('days', -1))) {
                $('#calendar').fullCalendar('unselect');
                toastr.warning('Oops!, tidak diperbolehkan merubah data!', 'Peringatan!', {
                    timeOut: 2000
                });
                return false;
            }

            if (event.ijin > 0) {
                $('#calendar').fullCalendar('unselect');
                calendar('put');
                toastr.warning('Oops!, Tidak diperkenankan merubah data yang telah melakukan perijinan!', 'Peringatan!', {
                    timeOut: 2000
                });
                return false;
            }

            if (event.noEdit) {
                swal({
                    title: "Oopss!",
                    text: "Anda dalam mode edit yang bukan di cabang utamanya!",
                    icon: "warning",
                    buttons: [
                        'batalkan!',
                        'edit!'
                    ],
                    dangerMode: false,
                }).then((willConfirm) => {
                    if (willConfirm) {

                        $('.modal').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        $('.modal').modal('show');
                        $('.modal-title').html('<em class="fa fa-pencil-square-o"></em> Form Kalendar Shift')
                        $('.modal').find('.kal-shf-peg').val(event.start.format('DD-MM-YYYY')).attr('data-tanggal', event.start)
                        $('.modal').find('input[name=idKal]').val(event.id)
                        select_(event.shift, 'shift');
                        select_(event.pegawai, 'pegawai');

                        $('.modal').find('div.ajukan-ijin').removeClass('hide').find('input#ch-ijin').removeAttr('disabled').attr('form', 'formSettingKalenderShift');

                        setTimeout(() => {
                            $('input#ch-ijin').on('click', function () {
                                var checked = $(this).is(':checked');
                                if (checked === true) {
                                    $('.modal').find('div.ajukan-keterangan').removeClass('hide').find('textarea.keterangan').removeAttr('disabled').attr('form', 'formSettingKalenderShift').addClass('required');
                                }
                                if (checked === false) {
                                    $('.modal').find('div.ajukan-keterangan').addClass('hide').find('textarea.keterangan').attr('disabled', true).removeAttr('form').removeClass('required');
                                }
                            });
                        }, 500);
                    }
                })
                return false;
            }
            swal({
                title: "Konfirmasi tindakan!",
                text: "Anda diarahkan untuk mengubah atau menghapus",
                icon: "info",
                buttons: [
                    'lanjutkan, edit!',
                    'delete'
                ],
                dangerMode: true,
            }).then((willConfirm) => {
                if (willConfirm) {
                    swal({
                        title: "Menghapus?",
                        text: "Data yang dihapus tidak bisa dikembalikan!",
                        icon: "warning",
                        buttons: [
                            'tidak, kembali edit?',
                            'ya, hapus!'
                        ],
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (willDelete) {

                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });

                            $.ajax({
                                url: base_url + '/calendars/shift/' + event.id,
                                type: 'DELETE',
                                dataType: 'JSON',
                                success: function (data) {
                                    switch (data.cd) {
                                        case 200:
                                            //$('#calendar').fullCalendar('refetchEvents');
                                            calendar('delete');
                                            toastr.success(data.msg, 'Success!', {
                                                timeOut: 2000
                                            })
                                            break;
                                        default:
                                            toastr.warning(data.msg, 'Peringatan!', {
                                                timeOut: 2000
                                            })
                                            break;
                                    }
                                },
                                error: function () {
                                    toastr.error('Kesalahan system!', 'Error!', {
                                        timeOut: 2000
                                    })
                                }
                            });
                        } else {

                            var filCabang = $(".filt-cabang").val();
                            if (!filCabang) {
                                swal('Ooopss!', 'Silakan pilih cabang buat menambah data shift!', 'info')
                                return false;
                            }

                            $('.modal').modal({
                                backdrop: 'static',
                                keyboard: false
                            });

                            $('.modal').modal('show');
                            $('.modal-title').html('<em class="fa fa-pencil-square-o"></em> Form Kalendar Shift')
                            $('.modal').find('.kal-shf-peg').val(event.start.format('DD-MM-YYYY')).attr('data-tanggal', event.start)
                            $('.modal').find('input[name=idKal]').val(event.id)
                            select_(event.shift, 'shift');
                            select_(event.pegawai, 'pegawai');

                            $('.modal').find('div.ajukan-ijin').removeClass('hide').find('input#ch-ijin').removeAttr('disabled').attr('form', 'formSettingKalenderShift');

                            setTimeout(() => {
                                $('input#ch-ijin').on('click', function () {
                                    var checked = $(this).is(':checked');
                                    if (checked === true) {
                                        $('.modal').find('div.ajukan-keterangan').removeClass('hide').find('textarea.keterangan').removeAttr('disabled').attr('form', 'formSettingKalenderShift').addClass('required');
                                    }
                                    if (checked === false) {
                                        $('.modal').find('div.ajukan-keterangan').addClass('hide').find('textarea.keterangan').attr('disabled', true).removeAttr('form').removeClass('required');
                                    }
                                });
                            }, 500);
                        }
                    });
                } else {

                    var filCabang = $(".filt-cabang").val();
                    if (!filCabang) {
                        swal('Ooopss!', 'Silakan pilih cabang buat menambah data shift!', 'info')
                        return false;
                    }

                    var title = $("#calendar").find('table').find('.fc-event-container').find('span');
                    title.html(title.text());
                    $("#calendar").find('table').find('.fc-event-container').find('span').find('em').removeClass('hide')

                    $('.modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('.modal').modal('show');
                    $('.modal-title').html('<em class="fa fa-pencil-square-o"></em> Form Kalendar Shift')
                    $('.modal').find('.kal-shf-peg').val(event.start.format('DD-MM-YYYY')).attr('data-tanggal', event.start)
                    $('.modal').find('input[name=idKal]').val(event.id)

                    $('.modal').find('div.ajukan-ijin').removeClass('hide').find('input#ch-ijin').removeAttr('disabled').attr('form', 'formSettingKalenderShift');

                    setTimeout(() => {
                        $('input#ch-ijin').on('click', function () {
                            var checked = $(this).is(':checked');
                            if (checked === true) {
                                $('.modal').find('div.ajukan-keterangan').removeClass('hide').find('textarea.keterangan').removeAttr('disabled').attr('form', 'formSettingKalenderShift').addClass('required');
                            }
                            if (checked === false) {
                                $('.modal').find('div.ajukan-keterangan').addClass('hide').find('textarea.keterangan').attr('disabled', true).removeAttr('form').removeClass('required');
                            }
                        });
                    }, 500);

                    select_(event.shift, 'shift');
                    select_(event.pegawai, 'pegawai');

                }
            });
        },
        editable: true,
        eventDrop: function (info) {
            if (info.dayPast == 'past') {
                $('#calendar').fullCalendar('unselect');
                calendar('put');
                toastr.warning('Oops!, tidak diperbolehkan merubah data', 'Peringatan!', {
                    timeOut: 2000
                });
                return false;
            }

            if (info.start.isBefore(moment().add('days', -1))) {
                $('#calendar').fullCalendar('unselect');
                calendar('put');
                toastr.warning('Oops!, tidak diperbolehkan merubah data!', 'Peringatan!', {
                    timeOut: 2000
                });
                return false;
            }

            if (info.ijin > 0) {
                $('#calendar').fullCalendar('unselect');
                calendar('put');
                toastr.warning('Oops!, Tidak diperkenankan merubah data yang telah melakukan perijinan!', 'Peringatan!', {
                    timeOut: 2000
                });
                return false;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: base_url + '/calendars/shift',
                data: {
                    id: info.id,
                    date: info.start.format("DD-MM-YYYY")
                },
                type: 'PUT',
                dataType: 'JSON',
                success: function (data) {
                    switch (data.cd) {
                        case 200:
                            //$('#calendar').fullCalendar('refetchEvents');
                            calendar('put');
                            toastr.success(data.msg, 'Success!', {
                                timeOut: 2000
                            })
                            break;
                        default:
                            toastr.warning(data.msg, 'Peringatan!', {
                                timeOut: 2000
                            })
                            break;
                    }
                },
                error: function () {
                    toastr.error('Kesalahan system!', 'Error!', {
                        timeOut: 2000
                    })
                }
            });
        },
        eventLimit: true,
        //events: "/calendars/shift/json",
        eventRender: function (event, element, view) {
            var Cabangan = $("select[nama=cabang]").val();
            if (view.name == 'month') {
                element.html(element.text());
                if (!Cabangan) {
                    element.find('em').addClass('hide')
                    element.find('em').attr('id', event.id)
                }
            }
        }
    });
    ReloadKalendarFirst()
}

function calendar(saved) {
    fullCalendars();
    topRightButton();

    if (!saved) {
        var htmls = `<div class="fc-button-group" style="width:120%;">`;

        htmls += `<button type="button" class="fc-button btn btn-xs prev-month">prev</button>`;
        htmls += `<button type="button" class="fc-button btn btn-xs to-now-month">today</button>`;
        htmls += `<button type="button" class="fc-button btn btn-xs next-month">next</button>`;

        htmls += `<div class="input-group-sm col-xs-6">`;
        htmls += `<select form="formSettingKalenderShift" name="cabang"
            class="form-control input-group select2 filt-cabang" style="width:100%;"></select>`;
        htmls += `</div>`;
        htmls += `</div>`;

        $(".fc-left").css({
            'width': '20%'
        }).append(htmls)

        select_('', 'cabang')
        var elemtFiltCabang = $(".filt-cabang");
        elemtFiltCabang.select2({
            placeholder: "Semua cabang...",
            allowClear: true,
            theme: "bootstrap"
        })

        elemtFiltCabang.on('change', function (e) {
            ReloadKalendar();
        });

        $('button.prev-month').on('click', function (e) {
            ReloadKalendar('prev')
        });

        $('button.next-month').on('click', function (e) {
            ReloadKalendar('next')
        });

        $('button.to-now-month').on('click', function (e) {
            ReloadKalendar('today')
        });
    }
}

function topRightButton() {
    $(".fc-month-button,.fc-basicWeek-button,.fc-basicDay-button").on('click', function () {
        $('.fc-button').removeAttr('disabled');
        $(this).attr('disabled', true);
        ReloadKalendarBtn('');
    });
}

function ReloadKalendarBtn(noReload) {

    var cbang = $(".filt-cabang").val();
    if (!noReload) {
        $(".preloader").fadeIn();
    }

    var elemt = $("#calendar").find('.fc-event');
    $.each(elemt, function (e, f) {
        var text = $(this).find('.fc-title').text();
        $(this).find('.fc-title').html(text);
    });

    if (!noReload) {
        setTimeout(function () {
            $(".preloader").fadeOut();
        }, 500);
    }

    if (!cbang) {
        $("#calendar").find('.fc-event').find('em').addClass('hide');
    } else {
        setTimeout(function () {
            $("#calendar").find('.fc-event').find('em').removeClass('hide');
        }, 1000);
    }
}

function ReloadKalendarFirst() {
    var event = "/calendars/shift/json";
    setTimeout(function () {
        $('#calendar').fullCalendar('removeEventSources');
        $('#calendar').fullCalendar('addEventSource', event);
        $("#calendar").fullCalendar({
            eventRender: function (event, element, view) {
                var Cabangan = $("select[nama=cabang]").val();
                if (view.name == 'month') {
                    element.html(element.text());
                    if (Cabangan) {
                        element.find('em').removeClass('hide')
                    }
                }
            }
        });
    }, 1000);
}

function ReloadKalendar(Kalendar) {
    $(".preloader").fadeIn();

    $('#calendar').fullCalendar('removeEventSources');
    if (Kalendar) {
        $('#calendar').fullCalendar(Kalendar);
    }
    var cbang = $(".filt-cabang").val();

    var event = "/calendars/shift/json" + (!cbang ? '' : `?cabang=` + cbang);

    $('#calendar').fullCalendar('removeEventSources');
    setTimeout(function () {
        $('#calendar').fullCalendar('addEventSource', event);
        setTimeout(() => {
            var elemt = $("#calendar").find('.fc-event');
            $.each(elemt, function (e, f) {
                var text = $(this).find('.fc-title').text();
                $(this).find('.fc-title').html(text);
            });
            if (!cbang) {
                ReloadKalendarBtn('no-reload');
            }
        }, 1000);
        ReloadKalendarBtn();
        if (Kalendar !== '') {
            $(".preloader").fadeOut();
        }
        if (cbang) {
            setTimeout(() => {
                $("#calendar").find('table').find('.fc-event').find('em').removeClass('hide');
                prosesDeleteFalse();
            }, 500);
        }

    }, 1000);
    return false
}

function prosesDeleteFalse() {
    $("#calendar").find('table').find('.fc-event').find('em').on('click', function (event) {

        swal({
            title: "Menghapus?",
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: "warning",
            buttons: [
                'tidak, batalkan!',
                'ya, hapus!'
            ],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: base_url + '/calendars/shift/' + event.target.id,
                    type: 'DELETE',
                    dataType: 'JSON',
                    success: function (data) {
                        switch (data.cd) {
                            case 200:
                                //$('#calendar').fullCalendar('refetchEvents');
                                ReloadKalendar()
                                toastr.success(data.msg, 'Success!', {
                                    timeOut: 2000
                                })
                                break;
                            default:
                                toastr.warning(data.msg, 'Peringatan!', {
                                    timeOut: 2000
                                })
                                break;
                        }
                    },
                    error: function () {
                        toastr.error('Kesalahan system!', 'Error!', {
                            timeOut: 2000
                        })
                    }
                });
            }
        });
        return false;
    })
}

function select_(id, table) {
    var id_ = id || '';
    var cabang = $('.filt-cabang').val()
    var cbg_ = !cabang ? '' : cabang
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: base_url + "/calendars/shift/option",
        method: "POST",
        data: {
            table: table,
            cabang_id: (table == 'pegawai' ? cbg_ : '')
        },
        dataType: 'json',
        success: function (data) {
            var html = `<option></option>`;
            var i;
            for (i = 0; i < data.length; i++) {
                var selected = data[i].id == id_ ? "selected" : '';
                html += `<option ` + selected + ` value='` + data[i].id + `'>` + data[i].nama + `</option>`;
            }
            var elemSelect = table == 'cabang' ? $("select.filt-" + table) : $("select[name=" + table + "]")
            elemSelect.html(html);
        }
    });
}

function submit() {
    $("form#formSettingKalenderShift").submit(function (e) {
        e.preventDefault();

        var event = $(this)[0];

        var data_ = new FormData(event);
        var url = event.action;
        var tanggal = $('input[name=tanggal]').data('tanggal')
        var modal = $('.modal');

        var falses = 0;
        $('.required').each(function (e) {
            var values = $(this).val()
            if (!values) {
                falses = 1;
            }
        });

        if (falses > 0) {
            toastr.warning('Isikan keterangan ijin Anda!', 'Peringatan!', {
                timeOut: 2000
            })
            return false;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: url,
            data: data_,
            contentType: false,
            processData: false,
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
                switch (data.cd) {
                    case 200:
                        // $('#calendar').fullCalendar('refetchEvents');
                        modal.find('input').removeAttr('data-tanggal');
                        modal.modal('hide');

                        $('#calendar').fullCalendar('unselect');
                        modal.find('select').val('').change();

                        toastr.success(data.msg, 'Success!', {
                            timeOut: 2000,
                            onHidden: function () {
                                $("input[name=idKal]").val('').trigger('change')
                                ReloadKalendar('')
                                setTimeout(() => {
                                    $(".preloader").fadeOut();
                                }, 1500);
                            }
                        })
                        break;
                    default:
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
