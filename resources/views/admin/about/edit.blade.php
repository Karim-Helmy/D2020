@extends('admin.layouts.app')

@section('content')

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
                                <form class="form form-horizontal striped-rows form-bordered" method="post" enctype="multipart/form-data"  action="{{ route('about.update') }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-body">

                                        <!-- Vision Ar-->
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.vision_ar') }}</label>
                                            <div class="col-md-9">
                                                <div class="position-relative has-icon-left">
                                                    <textarea class="form-control" rows="4"  name="vision_ar" >{{ $edit->vision_ar }}</textarea>
                                                    <div class="form-control-position">
                                                        <i class="la la-file-text"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Vision En-->
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.vision_en') }}</label>
                                            <div class="col-md-9">
                                                <div class="position-relative has-icon-left">
                                                    <textarea class="form-control" rows="4"  name="vision_en" >{{ $edit->vision_en }}</textarea>
                                                    <div class="form-control-position">
                                                        <i class="la la-file-text"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Upload File -->
                                        @if ($edit->vision_photo)
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.vision_photo') }}</label>
                                                <div class="col-md-9">
                                                    <div class="position-relative has-icon-left">
                                                        <img src="{{ asset('uploads/'.$edit->vision_photo) }}" title="{{ trans('admin.vision_photo') }}" style="height:100px; width:200px;" />
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.vision_photo') }}</label>
                                            <div class="col-md-9">
                                                <div class="position-relative has-icon-left">
                                                    <input type="file"  class="form-control"
                                                    name="vision_photo" >
                                                    <div class="form-control-position">
                                                        <i class="la la-file-image-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Mission Ar-->
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.mission_ar') }}</label>
                                            <div class="col-md-9">
                                                <div class="position-relative has-icon-left">
                                                    <textarea class="form-control" rows="4"  name="mission_ar" >{{ $edit->mission_ar }}</textarea>
                                                    <div class="form-control-position">
                                                        <i class="la la-file-text"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Mission En-->
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.mission_en') }}</label>
                                            <div class="col-md-9">
                                                <div class="position-relative has-icon-left">
                                                    <textarea class="form-control" rows="4"  name="mission_en" >{{ $edit->mission_en }}</textarea>
                                                    <div class="form-control-position">
                                                        <i class="la la-file-text"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Upload File -->
                                        @if ($edit->mission_photo)
                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.mission_photo') }}</label>
                                                <div class="col-md-9">
                                                    <div class="position-relative has-icon-left">
                                                        <img src="{{ asset('uploads/'.$edit->mission_photo) }}" title="{{ trans('admin.mission_photo') }}" style="height:100px; width:200px;" />
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.mission_photo') }}</label>
                                            <div class="col-md-9">
                                                <div class="position-relative has-icon-left">
                                                    <input type="file"  class="form-control"
                                                    name="mission_photo" >
                                                    <div class="form-control-position">
                                                        <i class="la la-file-image-o"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                    </div>
                                    <div class="form-actions right">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="la la-check-square-o"></i> {{ trans("admin.edit") }}
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
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">
        CKEDITOR.replace('vision_ar', {
            language: 'ar',
        });
    </script>
    <script type="text/javascript">
        CKEDITOR.replace('vision_en', {
            language: 'en',
        });
    </script>
    <script type="text/javascript">
        CKEDITOR.replace('mission_ar', {
            language: 'ar',
        });
    </script>
    <script type="text/javascript">
        CKEDITOR.replace('mission_en', {
            language: 'en',
        });
    </script>
@endsection
