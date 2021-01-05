@extends('admin.layouts.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery.fancybox.min.css')}}">
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
            <h3 class="content-header-title mb-0 d-inline-block">{{ trans('admin.choose') }}</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ eurl('/scorms/levels/'.$id.'/'.$subscriber_id) }}">{{ trans('admin.Scorms') }}</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <!-- Column selectors table -->
            <form id="search" action="{{ eurl('/scorms/choose/'.$id.'/'.$subscriber_id) }}" method="get" >
                <br />
                <div class="form_wrapper m-b-2">
                    <div class="row ">
                        <div class="col-md-6 col-sm-6">
                            <label>{{ trans('admin.stage') }}</label>
                            <select name="stage" class="form-control"  style="display:inline; margin:20px;">
                                <option value="">{{ trans('admin.stage') }}</option>
                                @foreach ($stages as $stage)
                                    <option value="{{ $stage->id }}" {{ (request()->stage == $stage->id) ? "selected" : "" }}>{{ $stage->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <button class="btn btn-success btn-min-width mr-1 mb-1" type="submit" style="display:inline; margin:20px auto;"></i>{{ trans('admin.search') }}</button>
                        </div>
                    </div>
                </div>
            </form>

        <section class="our_courses_table m-t-2">
            <div class="container">
                <form method="post" action="{{ eurl('/scorms/store/choose/'.$id.'/'.$subscriber_id) }}">
                    @csrf
                    <div class="row">
                        @foreach ($scorms as $scorm)
                            <div class="col-md-3 col-sm-6 m-b-1">
                                <div class="card">
                                    <br />
                                        <br />
                                      <div style="text-align:center"><i style="font-size: 08.4rem;" class="fa fa-file"></i></div>
                                    <div class="card-body">
                                      <p class="card-text" style="text-align:center">{{ $scorm->title }}</p>
                                            <div class="d-inline-block custom-control custom-checkbox mr-1" style="padding:0 25px;">
                                                <input type="checkbox" class="custom-control-input" id="p{{ $scorm->id }}" name="scorm_id[]" value="{{ $scorm->id }}" />
                                                <label class="custom-control-label" for="p{{ $scorm->id }}" ></label>
                                            </div>
                                          </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-left"><input class="btn btn-primary" type="submit" value="{{ trans("admin.save") }}"></div>
                    </form>
                    <div class="clearfix"></div>
                    <div class="pagination" style="margin:10px auto">
                        {{ $scorms->appends(request()->except('page'))->render() }}
                    </div><br />
            </div>
        </section>
    </div>
@endsection
