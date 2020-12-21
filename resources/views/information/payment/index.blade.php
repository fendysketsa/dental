@extends('layouts.app')

@section('content')
<section class="content-header">
    <h1>
        {{ $attribute['title_bc'] }}
        <small>{{ $attribute['desc_bc'] }}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-area-chart"></i> Home {{ session('cabang_session')}}</a></li>
        <li class="active">{{ $attribute['title_bc'] }}</li>
    </ol>
</section>
<section class="content">
    @include('information.payment.content.index')
</section>
@endsection

@section('ext-modal')
<div id="formModalInfoPembayaran" class="modal fade" role="dialog">
    <div id="load-detail" class="modal-dialog modal-xlg">
        @include('information.payment.content.detail.modal.detail')
    </div>
</div>
@endsection