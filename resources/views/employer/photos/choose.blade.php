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
                        <li class="breadcrumb-item"><a href="{{ eurl('/photos/levels/'.$id.'/'.$subscriber_id) }}">{{ trans('admin.Photos') }}</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <!-- Column selectors table -->
            <form id="search" action="{{ eurl('/photos/choose/'.$id.'/'.$subscriber_id) }}" method="get" >
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
                <form method="post" action="{{ eurl('/photos/store/choose/'.$id.'/'.$subscriber_id) }}">
                    @csrf
                    <div class="row">
                        @foreach ($photos as $photo)
                            <div class="col-md-3 col-sm-6 m-b-1">
                                <div class="card">
                                    <br />
                                    <a data-fancybox href="{{ asset('uploads/'.$photo->image) }}">
                                        <img class="card-img-top img-fluid" style="height:150px;" src="{{ asset('uploads/'.$photo->image) }}" class="img-responsive" />
                                    </a>
                                    <div class="card-body">
                                        <label class="checkbox_wrapper">
                                            <p class="card-text"><a data-fancybox href="{{ asset('uploads/'.$photo->image) }}">{{ $photo->title }}
                                            </a></p>
                                            <div class="d-inline-block custom-control custom-checkbox mr-1" style="padding:0 25px;">
                                                <input type="checkbox" class="custom-control-input" id="p{{ $photo->id }}" name="photo_id[]" value="{{ $photo->id }}" />
                                                <label class="custom-control-label" for="p{{ $photo->id }}" ></label>
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
                        {{ $photos->appends(request()->except('page'))->render() }}
                    </div><br />
            </div>
        </section>
    </div>
@endsection
