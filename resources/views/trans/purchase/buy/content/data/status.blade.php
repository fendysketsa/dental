@switch($status)

@case(1)
<a role="button" class="btn btn-xs btn-warning">proses</a>
@break
@case(2)
<a role="button" class="btn btn-xs btn-success">selesai</a>
@break
@case(3)
<a role="button" class="btn btn-xs btn-info">menunggu</a>
@break
@default

@endswitch
