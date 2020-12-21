<form action="{{ $action }}" id="formProfile"></form>
@csrf

<div class="box-body">
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="form-group">
                <label>Nama: <em class="text-danger">*</em></label>
                <div class="input-group input-group-sm date">
                    <div class="input-group-addon">
                        <i class="fa fa-tag"></i>
                    </div>
                    <input type="text" disabled name="name" @if(!empty($data)) value="{{ $data->name }}" @endif
                        class="form-control" placeholder="Nama..." form="formProfile">
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="form-group">
                <label>Email: <em class="text-danger">*</em></label>
                <div class="input-group input-group-sm date">
                    <div class="input-group-addon">
                        <i class="fa fa-envelope"></i>
                    </div>
                    <input type="email" disabled name="email" @if(!empty($data)) value="{{ $data->email }}" @endif
                        class="form-control" placeholder="Email..." form="formProfile">
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="form-group">
                <label>Password:</label>
                <div class="input-group input-group-sm date">
                    <div class="input-group-addon">
                        <i class="fa fa-key"></i>
                    </div>
                    <input type="password" disabled name="password" class="form-control" placeholder="Password..."
                        form="formProfile">
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="form-group">
                <label>Confirm Password:</label>
                <div class="input-group input-group-sm date">
                    <div class="input-group-addon">
                        <i class="fa fa-key"></i>
                    </div>
                    <input type="password" disabled name="confirm_password" class="form-control"
                        placeholder="Confirm Password..." form="formProfile">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="button-action box-footer bg-gray-light hide">
    <div class="row">
        <div class="col-md-4 col-xs-4 col-sm-4 pull-left">
            <button type="submit" class="btn btn-info col-md-12 col-xs-12 col-sm-12" form="formProfile"><em
                    class="fa fa-envelope"></em> Save</button>
        </div>
    </div>
</div>
