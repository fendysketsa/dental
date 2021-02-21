<form action="{{ $action }}" id="formRegistrasi"></form>
@if(!empty($data))
<input type="hidden" name="id" value="{{ $data[0]->id }}" data-member-id="{{ $data[0]->member_id }}"
    form="formRegistrasi">
@endif

<div class="load-form-periksa-gigi">

</div>
