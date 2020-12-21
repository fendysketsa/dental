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
    @include('trans.stockManagement.content.index')
</section>
@endsection

@section('ext-modal')
<div id="formModalStockManagement" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        @include('trans.stockManagement.content.form.modal.form')
    </div>
</div>
@endsection

@section('ext-modal-1')
<div id="detModalStockManagement" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        @include('trans.stockManagement.content.form.modal.detail')
    </div>
</div>
@endsection
