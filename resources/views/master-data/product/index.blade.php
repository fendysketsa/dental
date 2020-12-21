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
    @include('master-data.product.content.index')
</section>
@endsection

@section('ext-modal')
<div id="formModalProduk" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        @include('master-data.product.content.form.modal.form')
    </div>
</div>
@endsection
