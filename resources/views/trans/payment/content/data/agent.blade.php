@switch($agent)

@case('Android')
<a role="button" class="btn btn-xs btn-default">Android</a>
@break
@default
<a role="button" class="btn btn-xs btn-danger">Web Based</a>
@break

@endswitch
