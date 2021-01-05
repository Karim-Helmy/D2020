@extends('admin.layouts.app')

@section('content')

            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">{{ $title }}</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ aurl("/packages") }}">{{ trans('admin.packages') }}</a>
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
                                        <form class="form form-horizontal striped-rows form-bordered" method="post"  action="{{ route('packages.store') }}">
                                            @csrf
                                            <div class="form-body">
                                                <!-- Name -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.title') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text" required  class="form-control" placeholder="{{ trans('admin.title') }}"
                                                            name="name" value="{{ old('name') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-tag"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Price -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.price') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="string" required  class="form-control" placeholder="{{ trans('admin.price') }}"
                                                            name="price" value="{{ old('price') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-dollar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Options of Packages -->
                                                @foreach ($options as $key => $value)
                                                    @if ($value->id == 1)
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ $value->name }}</label>
                                                            <div class="col-md-9">
                                                                <div class="position-relative has-icon-left">
                                                                    <input type="number" required class="form-control" placeholder="{{ $value->name }}"
                                                                    name="option[{{ $value->id }}]" value="{{ old("option[]") }}">
                                                                    <div class="form-control-position">
                                                                        <i class="la la-server"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif ($value->id == 2 || $value->id == 4)
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ $value->name }}</label>
                                                            <div class="col-md-9">
                                                                <div class="position-relative has-icon-left">
                                                                    <select class="form-control" name="option[{{ $value->id }}]">
                                                                        <option value="مفعل">مفعل</option>
                                                                        <option value="غير مفعل">غير مفعل</option>
                                                                    </select>
                                                                    <div class="form-control-position">
                                                                        <i class="la la-server"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ $value->name }}</label>
                                                            <div class="col-md-9">
                                                                <div class="position-relative has-icon-left">
                                                                    <input type="text" required class="form-control" placeholder="{{ $value->name }}"
                                                                    name="option[{{ $value->id }}]" value="{{ old("option[]") }}">
                                                                    <div class="form-control-position">
                                                                        <i class="la la-server"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach

                                                <!-- Category ID -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.category') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            @foreach ($categories as $category)
                                                                <div class="d-inline-block custom-control custom-checkbox mr-1" style="padding:0 25px;">
                                                                    <input type="checkbox" class="custom-control-input" name="category_id[]" id="{{ $category->id }}" value="{{ $category->id }}" {{ in_array($category->id, old('category_id') ?? []) ? "checked" : ""   }} >
                                                                    <label class="custom-control-label" for="{{ $category->id }}">{{ $category->name }}</label>
                                                                </div>
                                                            @endforeach
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
