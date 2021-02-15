@extends('admin.layouts.app')

@section('content')

            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">{{ $title }}</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ aurl("/products") }}">{{ trans('admin.products') }}</a>
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
                                        <form class="form form-horizontal striped-rows form-bordered" method="post"  action="{{ route('products.store') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-body">

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.name') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text" required  class="form-control" placeholder="{{ trans('admin.name') }}"
                                                            name="name" value="{{ old('name') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-briefcase"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.arabic_name') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text" required  class="form-control" placeholder="{{ trans('admin.arabic_name') }}"
                                                                   name="arabic_name" value="{{ old('arabic_name') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-briefcase"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- stage ID -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.stage') }}</label>
                                                <div class="col-md-9">
                                                    <div class="position-relative has-icon-left">
                                                        <select name="stage_id" class="form-control">
                                                            @foreach ($stages as $stage)
                                                                <option value="{{ $stage->id }}" {{ old('stage_id') == $stage->id ? "selected" : ""   }}>{{ $stage->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-reorder"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                     
                                            <!-- Upload File -->
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

                                            <div class="form-group row file-repeater">
                                                <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.other_photos') }}</label>
                                                <div data-repeater-list="repeater-list" class="col-md-9">
                                                    <div data-repeater-item>
                                                        <div class="row mb-1">
                                                            <div class="col-9 col-xl-10">
                                                                <label class="file center-block">
                                                                    <input type="file" id="file" name="files">
                                                                    <span class="file-custom"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-2 col-xl-1">
                                                                <button type="button" data-repeater-delete class="btn btn-icon btn-danger mr-1"><i class="ft-x"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>

                                            </div>
                                                <button type="button" data-repeater-create class="btn btn-primary" >
                                                    <i class="ft-plus"></i>{{ trans('admin.new_file') }}
                                                </button>

                                            </div>



                                            <!-- Description -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.description') }}</label>
                                                <div class="col-md-9">
                                                    <div class="position-relative has-icon-left">
                                                        <textarea class="form-control" rows="3"  name="description" >{{ old('description') }}</textarea>
                                                        <div class="form-control-position">
                                                            <i class="la la-file-text"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Description -->
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.arabic_description') }}</label>
                                                <div class="col-md-9">
                                                    <div class="position-relative has-icon-left">
                                                        <textarea class="form-control" rows="3"  name="arabic_description" >{{ old('arabic_description') }}</textarea>
                                                        <div class="form-control-position">
                                                            <i class="la la-file-text"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions right">
                                                <button type="submit" class="btn btn-green">
                                                    <i class="la la-check-square-o"></i> {{ trans('admin.save') }}
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


@section('scripts')


    <script src="{{ asset('backend/app-assets/vendors/js/forms/jquery.repeater.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('backend/app-assets/vendors/js/forms/app-menu.js')}}" type="text/javascript"></script>
    <script src="{{ asset('backend/app-assets/vendors/js/forms/app.js')}}" type="text/javascript"></script>

    <script src="{{ asset('backend/app-assets/vendors/js/forms/customizer.js')}}" type="text/javascript"></script>

    <script src="{{ asset('backend/app-assets/vendors/js/forms/form-repeater.js')}}" type="text/javascript"></script>

    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>

    <script type="text/javascript">
        CKEDITOR.replace('description', {
            language: 'en',
        });
    </script>
    <script type="text/javascript">
        CKEDITOR.replace('arabic_description', {
            language: 'ar',
        });
    </script>
@endsection