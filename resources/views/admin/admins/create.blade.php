@extends('admin.layouts.app')

@section('content')

<div class="content-header row">
    <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">{{ $title }}</h3>

    </div>
</div>
<div class="content-body">
    <!-- Striped row layout section start -->
    <section id="striped-row-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="horz-layout-icons">{{ $title }}</h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                <li><a data-action="close"><i class="ft-x"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collpase show">
                        <div class="card-body">
                            <form class="form form-horizontal striped-rows form-bordered" method="post"
                                action="{{ aurl('/admins/create') }}">
                                @csrf
                                <div class="form-body">
                                    <!-- Add UserName -->
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control"
                                            for="timesheetinput2">{{ trans('admin.name') }}</label>
                                        <div class="col-md-9">
                                            <div class="position-relative has-icon-left">
                                                <input type="text" required class="form-control"
                                                    placeholder="{{ trans('admin.name') }}" name="username"
                                                    value="{{ old('username') }}">
                                                <div class="form-control-position">
                                                    <i class="ft-user"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Add Phone -->
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control"
                                            for="timesheetinput2">{{ trans('admin.mobile') }}</label>
                                        <div class="col-md-9">
                                            <div class="position-relative has-icon-left">
                                                <input type="number" required class="form-control"
                                                    placeholder="{{ trans('admin.mobile') }}" name="phone"
                                                    value="{{ old('phone') }}">
                                                <div class="form-control-position">
                                                    <i class="la la-phone"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Add Email -->
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control"
                                            for="timesheetinput2">{{ trans('admin.email') }}</label>
                                        <div class="col-md-9">
                                            <div class="position-relative has-icon-left">
                                                <input type="email" required class="form-control"
                                                    placeholder="{{ trans('admin.email') }}" name="email"
                                                    value="{{ old('email') }}">
                                                <div class="form-control-position">
                                                    <i class="la la-envelope"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Add Password -->
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control"
                                            for="timesheetinput2">{{ trans('login.password') }}</label>
                                        <div class="col-md-9">
                                            <div class="position-relative has-icon-left">
                                                <input type="password" class="form-control"
                                                    placeholder="{{ trans('login.password') }}" name="password">
                                                <div class="form-control-position">
                                                    <i class="la la-briefcase"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                    <!-- Roles -->
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.permissions') }}</label>
                                        <div class="col-md-9">
                                            <div class="position-relative has-icon-left">
                                                @foreach ($roles as $role)
                                                    <div class="d-inline-block custom-control custom-checkbox mr-1" style="padding:0 25px;">
                                                        <input type="checkbox" class="custom-control-input" name="role_id[]" id="r{{ $role->id }}" value="{{ $role->id }}" {{ in_array($role->id, old('role_id') ?? []) ? "checked" : ""   }} >
                                                        <label class="custom-control-label"{{ in_array($role->id,['956','3','6']) ? 'style=color:red' : '' }}  for="r{{ $role->id }}">{{ $role->title_ar }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>



                                </div>
                                <!-- Save Button -->
                                <div class="form-actions right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> {{ trans("admin.save") }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
