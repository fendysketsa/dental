@switch($status)

@case(1)
<a role='button' class='btn btn-xs btn-warning'>Batal</a>
@break
@case(2)
<a role='button' class='btn btn-xs btn-success'>Aktif</a>
@break
@case(4)
<a role='button' class='btn btn-xs btn-default'>Non Aktif</a>
@break

@endswitch
