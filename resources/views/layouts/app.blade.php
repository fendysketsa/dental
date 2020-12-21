<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="...">
    <meta name="author" content="...">
    <meta name="keyword" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('s-login/images/favicon.login.png') }}" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name_', 'Medina Dental') }}</title>
    <link rel="stylesheet" href="{{ asset('s-home/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('s-home/dist/css/offline-theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('s-home/dist/css/offline-language-english.css') }}" />
    <link rel="stylesheet" href="{{ asset('s-home/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('s-home/bower_components/Ionicons/css/ionicons.min.css') }}">
    @if(!empty($css))
    @foreach($css as $css)
    <link rel="stylesheet" href="{{ asset($css) }}">
    @endforeach
    @endif
    <link rel="stylesheet" href="{{ asset('s-home/bower_components/fullcalendar/dist/fullcalendar.print.min.css') }}"
        media="print">
    <link rel="stylesheet"
        href="{{ asset('s-home/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('s-home/bower_components/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('s-home/bower_components/select2-bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('s-home/dist/css/AdminLTE.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('s-home/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css') }}">
    <link rel="stylesheet" href="{{ asset('s-home/dist/css/skins/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('s-home/plugins/pace/pace.min.css') }}">
    <link rel="stylesheet" href="{{ asset('s-home/bower_components/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet"
        href="{{ asset('s-home/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('s-home/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('s-home/plugins/timepicker/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('s-home/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('s-home/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('s-home/style-add.css') }}">
    <link rel="stylesheet" href="{{ asset('s-home/plugins/toast/css/toastr.min.css') }}">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="stylesheet" href="{{ asset('s-home/dist/css/simplebar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('s-home/dist/css/preloader.css') }}">
</head>

<body class="hold-transition skin-blue fixed sidebar-mini sidebar-collapse">

    <div class="preloader">
        <div class="loading">
            <div class="spinner">
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
            </div>
        </div>
    </div>

    <div class="wrapper">
        <header class="main-header">
            <a href="@role('kasir') {{ url('registrations') }} @endrole  @role('super-admin|owner|manager') {{ url('home') }} @endrole"
                class="logo">
                <span class="logo-mini"><b>M</b>D</span>
                <span class="logo-lg"><b>Medina</b> Dental</span>
            </a>
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                @role('owner')
                <ul class="nav navbar-nav navbar-custom-menu pull-left text-center nav-cabang-bgcolor">

                    @foreach(LoadCabang() as $nCabg => $row)
                    <li style="background-color:{{ Color($nCabg) }}; {{ (!empty(session('cabang_session')) && session('cabang_session') == $row->id ? 'font-size:14px;' : '') }}"
                        class="dropdown navbar-static-top
                        {{ (!empty(session('cabang_session')) && session('cabang_session') == $row->id ? 'active' : '') }}
                        {{ (empty(session('cabang_session')) ? (!empty($row->selected) && $row->selected == 'CHECK' ? 'active' : '') : '') }}">
                        <a href="javascript:void(0)" class="fa fa-map-marker reload_session_branch"
                            data-link="{{ route('session.branch', base64_encode($row->id)) }}"
                            data-redirect="{{ $_SERVER['REQUEST_URI'] }}"> <em
                                class="fm-em {{ (!empty(session('cabang_session')) && session('cabang_session') == $row->id ? 'text-bold' : '') }}">{{ $row->nama }}</em></a>
                    </li>
                    @endforeach
                </ul>
                @endrole

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{ Auth::user()->pegawaiBelongs()['img'] }}"
                                    class="user-image photo-profile-user" alt="User Image">
                                <span class="hidden-xs">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    <img src="{{ Auth::user()->pegawaiBelongs()['img'] }}"
                                        class="img-circle photo-profile-user" alt="User Image">
                                    <p>
                                        {{ Auth::user()->name }}
                                        <small>{{ Auth::user()->pegawaiBelongs()['role'] }}</small>
                                    </p>
                                </li>

                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="{{ route('profiles.index') }}"
                                            class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="javascript:void(0)"
                                            class="btn btn-default btn-flat confirm-logout"><span
                                                class="fa fa-power-off "></span> Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="main-sidebar">
            <section class="sidebar">
                <div class="user-panel bg-user-panel-main">
                    <div class="pull-left image">
                        <img src="{{ Auth::user()->pegawaiBelongs()['img'] }}" class="img-circle photo-profile-user"
                            alt="User Image" style="height:45px;">
                    </div>
                    <div class="pull-left info">
                        <p data-branch-id="{{ (!empty(session('cabang_session')) ? session('cabang_session') : base64_decode(session('cabang_id')) ) }}"
                            data-branch-code="{{ base64_decode(session('cabang_code')) }}">
                            {{ Auth::user()->name }}</p>
                        <a href="{{ route('profiles.index') }}"><i class="fa fa-circle text-aqua"></i>
                            {{ Auth::user()->pegawaiBelongs()['role'] }}</a>
                    </div>
                </div>

                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">MAIN NAVIGATION</li>
                    @role('manager|super-admin|owner')
                    <li class="{{ (!empty($attribute['dashboard']) ? 'active ' . $attribute['dashboard'] : null) }}">
                        <a href="{{ url('home') }}"><i class="fa fa-area-chart"></i> <span>Dashboard</span></a></li>
                    @endrole
                    @role('manager|super-admin|owner')
                    <li class="treeview {{ (!empty($attribute['m_data']) ? 'active ' . $attribute['m_data'] : null) }}">
                        <a href="#">
                            <i class="fa fa-mortar-board"></i> <span>Master</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu" @if(!empty($attribute['m_data'])) style="display:block;" @endif>
                            @role('manager|super-admin|owner')
                            <li
                                class="{{ (!empty($attribute['menu_category']) ? $attribute['menu_category'] : null) }}">
                                <a href="{{ route('categories.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Kategori</a></li>
                            <li class="{{ (!empty($attribute['menu_product']) ? $attribute['menu_product'] : null) }}">
                                <a href="{{ route('products.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Produk</a></li>
                            <li
                                class="{{ (!empty($attribute['menu_services']) ? $attribute['menu_services'] : null) }}">
                                <a href="{{ route('services.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Layanan</a></li>
                            <li class="{{ (!empty($attribute['menu_package']) ? $attribute['menu_package'] : null) }}">
                                <a href="{{ route('packages.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Paket</a></li>
                            <li
                                class="{{ (!empty($attribute['menu_discount']) ? $attribute['menu_discount'] : null) }}">
                                <a href="{{ route('discounts.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Diskon</a></li>
                            <li class="{{ (!empty($attribute['menu_voucher']) ? $attribute['menu_voucher'] : null) }}">
                                <a href="{{ route('vouchers.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Voucher</a></li>
                            <li
                                class="{{ (!empty($attribute['menu_employee']) ? $attribute['menu_employee'] : null) }}">
                                <a href="{{ route('employees.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Pegawai</a></li>
                            @endrole

                            @role('super-admin|owner')
                            <li class="{{ (!empty($attribute['menu_member']) ? $attribute['menu_member'] : null) }}">
                                <a href="{{ route('members.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Pelanggan</a></li>
                            <li
                                class="{{ (!empty($attribute['menu_supplier']) ? $attribute['menu_supplier'] : null) }}">
                                <a href="{{ route('suppliers.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Supplier</a></li>
                            <li class="{{ (!empty($attribute['menu_bank']) ? $attribute['menu_bank'] : null) }}"><a
                                    href="{{ route('banks.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Bank</a></li>
                            <li
                                class="{{ (!empty($attribute['menu_location']) ? $attribute['menu_location'] : null) }}">
                                <a href="{{ route('locations.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Lokasi</a></li>
                            <li class="header"><em class="fa fa-th-large"></em> Other</li>
                            <li class="{{ (!empty($attribute['menu_branch']) ? $attribute['menu_branch'] : null) }}">
                                <a href="{{ route('branchs.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Cabang</a></li>
                            <li class="{{ (!empty($attribute['menu_slide']) ? $attribute['menu_slide'] : null) }}">
                                <a href="{{ route('slides.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Slider</a></li>
                            <li class="{{ (!empty($attribute['menu_promo']) ? $attribute['menu_promo'] : null) }}">
                                <a href="{{ route('promos.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Promo</a></li>
                            <li class="{{ (!empty($attribute['menu_berita']) ? $attribute['menu_berita'] : null) }}">
                                <a href="{{ route('news.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Berita</a></li>
                            <li class="{{ (!empty($attribute['menu_brand']) ? $attribute['menu_brand'] : null) }}">
                                <a href="{{ route('brands.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Brand</a></li>
                            @endrole
                        </ul>

                    </li>
                    @endrole
                    @role('kasir|super-admin')
                    <li class="{{ (!empty($attribute['menu_registrasi']) ? $attribute['menu_registrasi'] : null) }}">
                        <a href="{{ route('registrations.index') }}"><i class="fa fa-keyboard-o"></i>
                            <span>Pendaftaran</span></a></li>
                    <li class="{{ (!empty($attribute['menu_orderM']) ? $attribute['menu_orderM'] : null) }}"><a
                            href="{{ route('orders.index') }}"><i class="fa fa-search"></i>
                            <span>Monitoring Order</span></a></li>
                    <li class="{{ (!empty($attribute['menu_Cas']) ? $attribute['menu_Cas'] : null) }}">
                        <a href="{{ route('cashiers.index') }}">
                            <i class="fa fa-calculator"></i> <span>Pembayaran</span>
                        </a>
                    </li>
                    @endrole
                    @role('kasir|super-admin')
                    <li class="treeview {{ (!empty($attribute['m_info']) ? 'active ' . $attribute['m_info'] : null) }}">
                        <a href="#">
                            <i class="fa fa-bookmark"></i> <span>Informasi</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu @if(!empty($attribute['m_info'])) style=" display:block;" @endif">
                            <li
                                class="{{ (!empty($attribute['menu_inf_member']) ? $attribute['menu_inf_member'] : null) }}">
                                <a href="{{ route('members-info.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Member</a></li>
                            <li
                                class="{{ (!empty($attribute['menu_inf_payment']) ? $attribute['menu_inf_payment'] : null) }}">
                                <a href="{{ route('payments.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Pembayaran</a></li>
                            <li
                                class="{{ (!empty($attribute['menu_salesProdServ']) ? $attribute['menu_salesProdServ'] : null) }}">
                                <a href="{{ route('salesprodserv.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Layanan & Produk Terjual</a></li>
                        </ul>
                    </li>

                    <li
                        class="treeview {{ (!empty($attribute['m_transaction']) ? 'active ' . $attribute['m_transaction'] : null) }}">
                        <a href="#">
                            <i class="fa fa-calculator"></i> <span>Transaksi</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu" @if(!empty($attribute['m_transaction'])) style="display:block;"
                            @endif>
                            <li class="{{ (!empty($attribute['menu_sell']) ? $attribute['menu_sell'] : null) }}"><a
                                    href="{{ route('trans.spends.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Pengeluaran</a></li>
                            <li
                                class="{{ (!empty($attribute['menu_setModal']) ? $attribute['menu_setModal'] : null) }}">
                                <a href="{{ route('set.modals.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Set Modal Per Shift</a></li>
                            @role('owner|super-admin')
                            <li class="{{ (!empty($attribute['menu_buy']) ? $attribute['menu_buy'] : null) }}">
                                <a href="{{ route('trans.purchases.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Pembelian</a></li>
                            @endrole
                        </ul>
                    </li>
                    @endrole
                    @role('manager|super-admin|owner')
                    <li
                        class="treeview {{ (!empty($attribute['m_mntrg']) ? 'active ' . $attribute['m_mntrg'] : null) }}">
                        <a href="#">
                            <i class="fa fa-desktop"></i> <span>Monitoring</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu @if(!empty($attribute['m_mntrg'])) style=" display:block;" @endif">
                            <li
                                class="{{ (!empty($attribute['menu_mntrg_income']) ? $attribute['menu_mntrg_income'] : null) }}">
                                <a href="{{ route('incomes.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Pendapatan</a></li>
                            @role('super-admin|owner')
                            <li
                                class="{{ (!empty($attribute['menu_mntrg_sale']) ? $attribute['menu_mntrg_sale'] : null) }}">
                                <a href="{{ route('sales.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Penjualan</a></li>
                            @endrole
                            <li
                                class="{{ (!empty($attribute['menu_mntrg_visit']) ? $attribute['menu_mntrg_visit'] : null) }}">
                                <a href="{{ route('visits.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Kunjungan</a></li>
                            <li
                                class="{{ (!empty($attribute['menu_comm_terap']) ? $attribute['menu_comm_terap'] : null) }}">
                                <a href="{{ route('therapists.fee.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Komisi Terapis</a></li>
                            <li
                                class="{{ (!empty($attribute['menu_mntrg_member']) ? $attribute['menu_mntrg_member'] : null) }}">
                                <a href="{{ route('mntrg.members.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Member</a></li>
                        </ul>
                    </li>
                    @endrole

                    @role('super-admin|owner')
                    <li class="bg-navy">
                        <a href="https://akunting.gulawaxing.com" target="_blank"><i class="fa fa-balance-scale"></i>
                            <span>Akunting</span></a></li>
                    @endrole

                    @role('super-admin|owner')
                    <li
                        class="treeview {{ (!empty($attribute['m_other']) ? 'active ' . $attribute['m_other'] : null) }}">
                        <a href="#">
                            <i class="fa fa-arrows"></i> <span>Lain-Lain</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu" @if(!empty($attribute['m_other'])) style="display:block;" @endif>
                            <li
                                class="{{ (!empty($attribute['menu_calshift']) ? $attribute['menu_calshift'] : null) }}">
                                <a href="{{ route('calendars.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Kalendar Shift</a></li>
                            <li class="{{ (!empty($attribute['menu_shift']) ? $attribute['menu_shift'] : null) }}">
                                <a href="{{ route('shifts.index') }}"><i class="fa fa-check-circle-o"></i>
                                    Setting Shift</a></li>
                            {{-- <li class="{{ (!empty($attribute['menu_nota']) ? $attribute['menu_nota'] : null) }}"><a
                                href="{{ route('notas.index') }}"><i class="fa fa-check-circle-o"></i>
                                Setting Nota</a>
                    </li> --}}
                    <li class="{{ (!empty($attribute['menu_stock']) ? $attribute['menu_stock'] : null) }}">
                        <a href="{{ route('stocks.index') }}"><i class="fa fa-check-circle-o"></i>
                            Stok Management</a></li>
                </ul>
                </li>
                @endrole
                </ul>
            </section>
        </aside>

        <div class="content-wrapper ignielPelangi" data-simplebar>
            @yield('content')
        </div>
        @yield('ext-modal')
        @yield('ext-modal-1')

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 1.0
            </div>
            <strong>Copyright &copy; 2019 <a href="#">Medina Dental</a></strong>
        </footer>

    </div>



    <script src="{{ asset('s-home/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('s-home/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>

    <script src="{{ asset('s-home/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('s-home/bower_components/PACE/pace.min.js') }}"></script>
    <script src="{{ asset('s-home/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('s-home/bower_components/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('s-home/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('s-home/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('s-home/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('s-home/bower_components/jquery-knob/dist/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('s-home/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('s-home/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('s-home/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>
    <script src="{{ asset('s-home/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
    <script src="{{ asset('s-home/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('s-home/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('s-home/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('s-home/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('s-home/bower_components/fastclick/lib/fastclick.js') }}"></script>
    <script src="{{ asset('s-home/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('s-home/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js') }}">
    </script>
    <script src="{{ asset('s-home/dist/js/demo.js') }}"></script>
    <script src="{{ asset('s-home/dist/js/jquery.scrolly.min.js') }}"></script>
    <script src="{{ asset('s-home/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('s-home/dist/js/simplebar.js') }}"></script>
    <script src="{{ asset('s-home/dist/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('s-home/home/js/home.js') }}"></script>
    @include('sweet::alert')
    <script src="{{ asset('s-home/plugins/toast/js/toastr.min.js') }}"></script>
    <script src="{{ asset('s-home/dist/js/pages/toast_confirm.js') }}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js"></script>
    <script type="text/javascript">
        try{Typekit.load();}catch(e){}
    </script>
    <script src="{{ asset('s-home/dist/js/offline.min.js') }}"></script>

    <script>
        var token = `<?php echo csrf_field(); ?>`;
        var base_url = `<?php echo URL::to('/');  ?>`;

        $(document).ready(function(){
        $(".preloader").fadeOut();
        setTimeout(function() {
            $(document).ajaxStart(function () {
                Pace.restart()
            });
        }, 600);

        $(".reload_session_branch").on('click', function(e) {
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

            $.ajax({
                url: $(this).data('link') + '?load=' + $(this).data('redirect'),
                type: 'POST',
                dataType: 'JSON',
                success: function (data) {
                    switch (data.cd) {
                        case 200:
                            window.location.href = base_url + data.redirect;
                            break;
                    }
                },
                error: function () {
                    toastr.error('Kesalahan system!', 'Error!', {
                        timeOut: 2000
                    })
                }
            });
        })

    });

    $(function () {
        $('#data-table-view,#data-table-view2').DataTable({
            responsive: true
        });
        $('.select2').select2({
            placeholder: "Please select!",
            allowClear: true
        });


    })

    $('.btn-remove-id').click(function () {
        var postId = $(this).data('href');
        swal({
            title: "Menghapus data?",
            text: "Data yang dihapus tidak bisa dikembalikan.",
            icon: "warning",
            buttons: ["Batal", "Ok"],
            dangerMode: true,
        }).then(function(willExec){
            if(willExec) {
                window.location.href = postId;
            } else {
                swal.close()
            }
        });
   });

    </script>
    @if(!empty($js))
    @foreach($js as $js)
    <script src="{{ asset($js) }}"></script>
    @endforeach
    @endif
</body>

</html>
