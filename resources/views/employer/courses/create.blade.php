@extends('admin.layouts.app')

@section('content')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
            <h3 class="content-header-title mb-0 d-inline-block">{{ $title }}</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ eurl("/courses/".$id) }}">{{ trans('admin.courses') }}</a>
                        </li>
                    </ol>
                </div>
            </div>
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
                                <form class="form form-horizontal striped-rows form-bordered" method="post" enctype="multipart/form-data"  action="{{ eurl('/courses/store/'.$id) }}">
                                    @csrf
                                    <div class="form-body">
                                        <!-- Name -->
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">{{ trans('admin.title') }}</label>
                                            <div class="col-md-9">
                                                <div class="position-relative has-icon-left">
                                                    <input type="text" required  class="form-control" placeholder="{{ trans('admin.title') }}"
                                                    name="title" value="{{ old('title') }}">
                                                    <div class="form-control-position">
                                                        <i class="la la-user"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Start Date -->
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">{{ trans('admin.start_date') }}</label>
                                            <div class="col-md-9">
                                                <div class="position-relative has-icon-left">
                                                    <input type="date"  class="form-control" placeholder="{{ trans('admin.start_date') }}"
                                                    name="start_date" value="{{ old('start_date') }}">
                                                    <div class="form-control-position">
                                                        <i class="la la-calender"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- End Date -->
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">{{ trans('admin.end_date') }}</label>
                                            <div class="col-md-9">
                                                <div class="position-relative has-icon-left">
                                                    <input type="date"  class="form-control" placeholder="{{ trans('admin.end_date') }}"
                                                    name="end_date" value="{{ old('end_date') }}">
                                                    <div class="form-control-position">
                                                        <i class="la la-calender"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <!-- Number Of Days -->
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">{{ trans('admin.days_no') }}</label>
                                            <div class="col-md-9">

                                                <div class="position-relative has-icon-left">
                                                    <input type="number"  class="form-control" placeholder="{{ trans('admin.days_no') }}"
                                                    name="days_no" value="{{ old('days_no') }}">
                                                    <div class="form-control-position">
                                                        <i class="la la-calender"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Number Of Hours -->
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">{{ trans('admin.hours_no') }}</label>
                                            <div class="col-md-9">

                                                <div class="position-relative has-icon-left">
                                                    <input type="number"  class="form-control" placeholder="{{ trans('admin.hours_no') }}"
                                                    name="hours_no" value="{{ old('hours_no') }}">
                                                    <div class="form-control-position">
                                                        <i class="la la-clock"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.description') }}</label>
                                            <div class="col-md-9">
                                                <div class="position-relative has-icon-left">
                                                    <textarea class="form-control" rows="4"  name="description" >{{ old('description') }}</textarea>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- Object -->
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.objects') }}</label>
                                            <div class="col-md-9">
                                                <div class="position-relative has-icon-left">
                                                    <textarea class="form-control" rows="4"  name="object" >{{ old('object') }}</textarea>

                                                </div>
                                            </div>
                                        </div>


                                        <!-- Levels -->
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.level') }}</label>
                                            <div class="form-group col-md-9 contact-repeater">
                                                <div data-repeater-list="repeater">
                                                    <div class="input-group mb-1" data-repeater-item>
                                                        <input type="text" name="level_name" placeholder="{{ trans('admin.level_name') }}" class="form-control" id="example-tel-input" value="{{ old('level_number') }}">
                                                        <input type="number"  class="form-control" placeholder="{{ trans('admin.level_number') }}"
                                                        name="level_number" value="{{ old('level_number') }}">
                                                        <span class="input-group-append" id="button-addon2">
                                                            <button class="btn btn-danger" type="button" data-repeater-delete><i class="ft-x"></i></button>
                                                        </span>
                                                    </div>

                                                </div>

                                                <button type="button" data-repeater-create class="btn btn-primary">
                                                    <i class="ft-plus"></i> {{ trans('admin.add_new_level') }}
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Videos -->
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">{{ trans('admin.video') }}</label>
                                            <div class="col-md-9">

                                                <div class="position-relative has-icon-left">
                                                    <input type="text"  class="form-control" placeholder="{{ trans('admin.video') }}"
                                                    name="video" value="{{ old('video') }}">
                                                    <div class="form-control-position">
                                                        <i class="la la-clock"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Upload Photo -->
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.photo') }}</label>
                                            <div class="col-md-9">
                                                <div class="position-relative has-icon-left">
                                                    <input type="file"  class="form-control"
                                                    name="image" >
                                                    <div class="form-control-position">
                                                        <i class="la la-file-image-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Category ID -->
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.category') }}</label>
                                            <div class="col-md-9">
                                                <div class="position-relative has-icon-left">
                                                    <select name="category_id" class="form-control">
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->category->id }}" {{ old('category_id') == $category->category->id ? "selected" : ""   }}>{{ $category->category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="form-control-position">
                                                        <i class="la la-reorder"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Active -->
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.active') }}</label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    <div class="d-inline-block custom-control custom-radio mr-1" style="margin: auto 20px !important;">
                                                        <input type="radio" name="active" value="1" {{ old('active') == '1' ? 'checked' : '' }} class="custom-control-input" id="yes" >
                                                        <label class="custom-control-label" for="yes">{{ trans('admin.yes') }}</label>
                                                    </div>
                                                    <div class="d-inline-block custom-control custom-radio">
                                                        <input type="radio" name="active" value="0" {{ old('active') == '0' ? 'checked' : '' }} class="custom-control-input" id="no" >
                                                        <label class="custom-control-label" for="no">{{ trans('admin.no') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-actions text-center">
                                        <button type="submit" class="btn btn-success">
                                            <i class="la la-check-square-o"></i> {{ trans('admin.save') }}
                                        </button>
                                    </div>
                                    <br>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('backend/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js')}}"
  type="text/javascript"></script>
      <script src="{{ asset('backend/app-assets/js/scripts/form-repeater.js')}}" type="text/javascript"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">
        CKEDITOR.replace('description', {
            language: 'ar',
        });
        CKEDITOR.replace('object', {
            language: 'ar',
        });
    </script>
@endsection
