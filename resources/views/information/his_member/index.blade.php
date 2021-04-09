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
    @include('information.his_member.content.index')
</section>
@endsection

@section('ext-modal')
<div id="detMember" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xlg">
        @include('information.his_member.content.detail.modal.detail')
    </div>
</div>
@endsection

@section('ext-modal-detail-history')
<div id="detHisMember" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        @include('information.his_member.content.detail.modal.detail_history')
    </div>
</div>
@endsection
