@extends('layouts.app')

@section('content')
<section class="content-header">
    <h1>
        {{ $attribute['title_bc'] }}
        <small>{{ $attribute['desc_bc'] }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-area-chart"></i> Home</a></li>
        <li class="active">{{ $attribute['title_bc'] }}</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-danger display-future">
                <div class="box-header with-border bg-info">
                    <i class="fa fa-calendar"></i>
                    <h3 class="box-title">Tahun :</h3>
                    <select id="fil-y"></select>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <em class="fa fa-refresh fa-spin"></em> loading...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
