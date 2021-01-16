<?php
print_r($data);
die;
?>

<div class="row">
    <section class="col-lg-12 connectedSortable">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs pull-right bg-warning">
                <li class="pull-left header"><i class="fa fa-inbox"></i> Perfoma /
                    Pendapatan Outlet
                </li>
            </ul>
            <div class="tab-content padding">
                <div class="row">
                    <div class="col-lg-12">
                        <canvas class="chart tab-pane active" id="revenue-chart"
                            data-cabang-pie="[{{ str_replace('?', '"', $data['cabang']) }}]"
                            data-performa-pie="{{ $data['performa'] }}" style="position: relative; height: 400px;">
                        </canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="row">
    <div class="col-lg-12 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Layanan</h3>
            </div>
            <div class="panel-body">
                <canvas class="chart tab-pane active" id="services-chart"
                    data-cabang-bar="[{{ str_replace('?', '"', $data['cabang']) }}]"
                    data-services-label="[{{ $data['servicesLabel'] }}]"
                    data-services-set="{{ $data['servicesSet'] }}" style="position: relative; height: 300px;">
                </canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xs-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Kunjungan</h3>
            </div>
            <div class="panel-body">
                <canvas class="chart tab-pane active" id="visit-chart"
                    data-cabang-pie="[{{ str_replace('?', '"', $data['cabang']) }}]"
                    data-visit-pie="[{{ $data['visit'] }}]" style="position: relative; height: 300px;">
                </canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xs-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-user"></i> Member
                    <em class="pull-right btn btn-xs btn-warning"> Total Member :
                        {{ $data['member'] }} orang</em>
                </h3>
            </div>
            <div class="panel-body">
                <canvas class="chart tab-pane active" id="member-chart" data-member-pie="[{{ $data['memberPie'] }}]"
                    style="position: relative; height: 300px;">
                </canvas>
            </div>
        </div>
    </div>
</div>
