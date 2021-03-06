@extends('admin.layouts.app')

@section('content')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
            <h3 class="content-header-title mb-0 d-inline-block">{{ $title }}</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ aurl("/stages") }}">{{ trans("admin.stages") }}</a>
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
                                <form class="form form-horizontal striped-rows form-bordered" method="post"  action="{{ route('stages.update', [$edit->id]) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-body">

                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ trans("admin.title") }}</label>
                                            <div class="col-md-9">
                                                <div class="position-relative has-icon-left">
                                                    <input type="text" required  class="form-control" placeholder="{{ trans("admin.title") }}"
                                                    name="name" value="{{ $edit->name }}">
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
                                                           name="arabic_name" value="{{ $edit->arabic_name }}">
                                                    <div class="form-control-position">
                                                        <i class="la la-briefcase"></i>
                                                    </div>
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
                                                        <option value="{{ $category->id }}" {{ $edit->category_id == $category->id ? "selected" : ""   }}>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="form-control-position">
                                                    <i class="la la-reorder"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Subscriber ID -->
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.subscriber') }}</label>
                                        <div class="col-md-9">
                                            <div class="position-relative has-icon-left">
                                                <select name="subscriber_id" class="form-control">
                                                    @foreach ($subscribers as $subscriber)
                                                        <option value="{{ $subscriber->id }}" {{ $edit->subscriber_id == $subscriber->id ? "selected" : ""   }}>{{ $subscriber->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="form-control-position">
                                                    <i class="la la-user"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Address  -->
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.address') }}</label>
                                        <div class="col-md-9">
                                            <div class="position-relative has-icon-left">
                                                <input type="text" required  class="form-control" placeholder="{{ trans('admin.address') }}"
                                                       name="address" value="{{ $edit->address }}">
                                                <div class="form-control-position">
                                                    <i class="la la-location-arrow"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- map -->

                                    <div class="form-group row" >
                                        <label class="col-md-3 label-control" for="timesheetinput2"></label>
                                        <div class="col-md-3" >
                                            {!! $edit->map   !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.map') }}</label>
                                        <div class="col-md-9">
                                            <div class="position-relative has-icon-left">
                                                <textarea class="form-control" rows="3"  name="map" >{{ old('map') }}</textarea>
                                                <div class="form-control-position">
                                                    <i class="la la-file-text"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- City ID -->
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.city') }}</label>
                                        <div class="col-md-9">
                                            <div class="position-relative has-icon-left">
                                                <select name="city_id" class="form-control">
                                                    @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}"{{ $edit->city_id == $city->id ? "selected" : ""   }}>{{ $city->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="form-control-position">
                                                    <i class="la la-reorder"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control" for="timesheetinput2"></label>
                                        <div class="col-md-3">
                                            <div class="position-relative has-icon-left">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">
                                                        <span class="badge badge-default badge-pill bg-primary float-right">{{ $edit->begin }}</span>
تاريخ بداية الاشتراك                                                    </li>
                                                    <li class="list-group-item">
                                                        <span class="badge badge-default badge-pill bg-danger float-right">{{ $edit->end }}</span>
                                                        تاريخ نهاية الاشتراك                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.end_date') }}</label>
                                        <div class="col-md-9">
                                            <div class="position-relative has-icon-left">
                                                <input type="date" id="start" name="end">

                                            </div>
                                        </div>
                                    </div>

                                    <!-- Upload File -->
                                    @if ($edit->image)
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="timesheetinput2"></label>
                                            <div class="col-md-9">
                                                <div class="position-relative has-icon-left">
                                                    <img src="{{ asset('uploads/'.$edit->image) }}" title="{{ trans('admin.photo') }}" style=" width:150px;" />
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
                                    @if ($stageImages!== null)

                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="timesheetinput2"></label>
                                            <div class="col-md-9">
                                                <div class="position-relative has-icon-left">
                                                    @foreach($stageImages as $image)
                                                    <img src="{{ asset('uploads/'.$image->image) }}" title="{{ trans('admin.photo') }}" style=" width:150px;" />
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                    @endif
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
                                                <textarea class="form-control" rows="3"  name="description" >{{ $edit->description }}</textarea>
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
                                                <textarea class="form-control" rows="3"  name="arabic_description" >{!! $edit->arabic_description  !!}  </textarea>
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