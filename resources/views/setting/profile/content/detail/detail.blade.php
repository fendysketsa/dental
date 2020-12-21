<form id="form-upload"></form>

<div class="text-center">
    <div class="app__user">
        <div class="app__user-photo">
            <img id="preview-upload" class="profile-user-img img-responsive img-circle"
                src="{{ Auth::user()->pegawaiBelongs()['img'] }}" alt="User profile picture">
        </div>
        <span class="app__user-notif">
            <label for="file-input">
                <i class="fa fa-camera"></i>
            </label>
            <input type="file" id="file-input" form="form-upload">
            <input type="hidden" name="photo" form="form-upload">
        </span>
    </div>
</div>
<h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>
<p class="text-muted text-center">{{ Auth::user()->pegawaiBelongs()['role'] }}</p>
<ul class="list-group list-group-unbordered">
    <li class="list-group-item">
        <div class="row">
            <div class="col-md-6 col-xs-6 word-break">
                <strong><em class="fa fa-envelope margin-r-5"></em> Email</strong>
                <p class="text-muted det-mail">
                    <small>{{ Auth::user()->email }}</small>
                </p>
            </div>
            <div class="col-md-6 col-xs-6 word-break">
                <strong><em class="fa fa-coffee margin-r-5"></em> Jabatan</strong>
                <p class="text-muted det-jabatan"><small><span
                            class="label label-info">{{ Auth::user()->pegawaiBelongs()['jabatan'] }}</span></small></p>
            </div>
        </div>
    </li>
</ul>
<strong><i class="fa fa-map-marker margin-r-5"></i> Alamat</strong>
<p class="text-muted det-address"><small>Jogja</small></p>
