<div class="form_wrapper m-b-2">
    <div class="row ">

        <div class="col-md-4 col-sm-6">
            <label class="req">{{ trans("admin.username") }}</label>
            <input type="text" name="username" value="{{ $edit->username }}" class="form-control" required>
            <i class="fal fa-user icon_date"></i>
        </div>

        <div class="col-md-4 col-sm-6">
            <label class="req">{{ trans("admin.name") }}</label>
            <input type="text" name="name" value="{{ $edit->name }}" class="form-control" required>
            <i class="fal fa-user icon_date"></i>
        </div>

        <div class="col-md-4 col-sm-6">
            <label class="req">{{ trans("admin.id_number") }}</label>
            <input type="number" name="id_number" value="{{ $edit->id_number }}" class="form-control" required>
            <i class="fal fa-address-card icon_date"></i>
        </div>

        <div class="col-md-4 col-sm-6">
            <label class="req">{{ trans("admin.email") }}</label>
            <input type="email" name="email" value="{{ $edit->email }}" class="form-control" required>
            <i class="fal fa-envelope icon_date"></i>
        </div>

        <div class="col-md-4 col-sm-6">
            <label class="req">{{ trans("admin.mobile") }}</label>
            <input type="number"  name="mobile" value="{{ $edit->mobile }}" class="form-control" required>
            <i class="fal fa-mobile icon_date"></i>
        </div>


        <div class="col-md-4 col-sm-6">
            <label class="req">{{ trans('admin.birth_date') }}</label>
            <input type="text"  name="birth_date"  autocomplete="off" value="{{ $edit->birth_date }}" class="form-control date_only" required>
            <i class="fal fa-calendar icon_date"></i>
        </div>
    </div>


    <div class="m-t-2">
        <div class="input_image">
            <span>{{ trans('admin.photo') }}<i class="fal fa-upload"></i></span>
            <input type="file" name="image" id="">
            <img src="" alt="">
            <p></p>
        </div>
    </div>

</div>

<div class="text-left"><input class="main_btn" type="submit" value="{{ trans("admin.edit") }}"></div>
