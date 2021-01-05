@extends('admin.layouts.app')

@section('content')

            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">{{ $title }}</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ aurl("/subscribers") }}">{{ trans('admin.subscribers') }}</a>
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
                                        <form class="form form-horizontal striped-rows form-bordered" method="post"  action="{{ route('subscribers.store') }}">
                                            @csrf
                                            <div class="form-body">
                                                <!-- Name -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control">{{ trans('admin.name') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text" required  class="form-control" placeholder="{{ trans('admin.name') }}"
                                                            name="name" value="{{ old('name') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-user"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Email -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control">{{ trans('admin.email') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="email" required  class="form-control" placeholder="{{ trans('admin.email') }}"
                                                            name="email" value="{{ old('email') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-envelope"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Phone -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control">{{ trans('admin.phone') }}</label>
                                                    <div class="col-md-9">
                                                        <div style="color:#FF0000">Example:- 05xxxxxxxx</div>
                                                        <div class="position-relative has-icon-left">
                                                            <input type="number" required  class="form-control" placeholder="{{ trans('admin.phone') }}"
                                                            name="phone" value="{{ old('phone') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-phone"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- Address -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control">{{ trans('admin.address') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text"  class="form-control" placeholder="{{ trans('admin.address') }}"
                                                            name="address" value="{{ old('address') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-map"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- city ID -->
                                                <div class="form-group row">
                                                   <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.city') }}</label>
                                                   <div class="col-md-9">
                                                       <div class="position-relative has-icon-left">
                                                           <select  name="city_id" class="form-control"  required>
                                                             @foreach ($cities as $city)
                                                                 <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                                             @endforeach
                                                           </select>
                                                           <div class="form-control-position">
                                                               <i class="la la-server"></i>
                                                           </div>
                                                       </div>
                                                   </div>
                                               </div>

                                                <!-- Status -->
                                                <div class="form-group row  mx-auto last">
                                                    <label class="col-md-3 label-control">{{ trans('admin.active') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="input-group">
        													<div class="d-inline-block custom-control custom-radio mr-1" style="margin: auto 20px !important;">
        														<input type="radio" name="status" value="1" class="custom-control-input" id="yes" {{ old('status') == '1' ? 'checked' : '' }}>
        														<label class="custom-control-label" for="yes">{{ trans('admin.yes') }}</label>
        													</div>
        													<div class="d-inline-block custom-control custom-radio">
        														<input type="radio" name="status" value="0" class="custom-control-input" id="no"{{ old('status') == '0' ? 'checked' : '' }}>
        														<label class="custom-control-label" for="no">{{ trans('admin.no') }}</label>
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
