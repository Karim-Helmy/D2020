<div class="form_wrapper m-b-2">
    <div class="row ">
        <div class="col-md-4 col-sm-6">
            <label class="req">{{ trans('admin.old password') }}</label>
            <input type="password" class="form-control" name="old_password" required>
            <i class="fal fa-key icon_date"></i>
        </div>

        <div class="col-md-4 col-sm-6">
            <label class="req">{{ trans('admin.password') }}</label>
            <input type="Password" class="form-control" name="password" required>
            <i class="fal fa-key icon_date"></i>
        </div>

        <div class="col-md-4 col-sm-6">
            <label class="req">{{ trans('admin.password_confirmation') }}</label>
            <input type="Password" class="form-control" name="password_confirmation" required>
            <i class="fal fa-key icon_date"></i>
        </div>

    </div>
</div>

<div class="text-left"><input class="main_btn" type="submit" value="Submit"></div>
