@extends('admin.layouts.app')

@section('content')

            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">{{ $title }}</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ aurl("/attachments") }}">{{ trans('admin.attachments') }}</a>
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
                                        <form class="form form-horizontal striped-rows form-bordered" method="post" enctype="multipart/form-data" action="{{ route('attachments.store') }}">
                                            @csrf
                                            <div class="form-body">

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.title') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text" required  class="form-control" placeholder="{{ trans('admin.title') }}"
                                                            name="title" value="{{ old('title') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-tag"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- attachments -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.attachments') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input class="form-control" type="file" name="image">
                                                            <div class="form-control-position">
                                                                <i class="la la-file"></i>
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
                                                            <div class="form-control-position">
                                                                <i class="la la-file-text"></i>
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
                                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? "selected" : ""   }}>{{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="form-control-position">
                                                                <i class="la la-reorder"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- STAGE ID -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.stages') }}</label>
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

                                            </div>
                                            <div class="form-actions right">
                                                <button type="submit" class="btn btn-primary">
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
