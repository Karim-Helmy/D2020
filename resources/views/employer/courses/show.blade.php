@extends('admin.layouts.app')
@section('content')
    <div>
        <h3 class="course_title m-b-1">{{ $course->title }}</h3>
        <br />
        <div class="row">
            <div class="col-12">
                <a href="{{ eurl('/banks/'.$course->id.'/'.$subscriber_id) }}" class="btn btn-social mb-1 btn-block btn-lg btn-github" style="width:200px;">
                    <span class="fa fa-question"></span> {{ trans('admin.banks') }}</a>
                <a href="{{ eurl('/exams/'.$course->id.'/'.$subscriber_id) }}" class="btn btn-social mb-1 btn-block btn-lg btn-github" style="width:200px;">
                        <span class="fa fa-diagnoses"></span> {{ trans('admin.exams') }}</a>
            </div>
            <div class="clearfix"></div>
            <!-- Simple User Cards with Shadow-->
            <section id="user-cards-with-square-thumbnail" class=" col-md-3 col-xs-12">
                <div class="col-12">
                    <div class="card box-shadow-1">
                        <div class="text-center">
                            <div class="card-body">
                                <ul class="list-group">
                                    <li  class="list-group-item" @if (session()->get('locale') == "ar") style="text-align:right;" @else style="text-align:left;" @endif >
                                        <span class="badge badge-primary badge-pill float-right">{{ $course->days_no }} {{ trans('admin.days') }}</span>
                                        {{ trans('admin.duration') }}
                                    </li>
                                    <li  class="list-group-item" @if (session()->get('locale') == "ar") style="text-align:right;" @else style="text-align:left;" @endif>
                                        <span class="badge badge-success badge-pill float-right">{{ $course->hours_no }}</span>
                                        {{ trans('admin.hours') }}
                                    </li>
                                    <li  class="list-group-item" @if (session()->get('locale') == "ar") style="text-align:right;" @else style="text-align:left;" @endif>
                                        <span class="badge badge-success badge-pill float-right">{{ $course->start_date }}</span>
                                        {{ trans('admin.start_date') }}
                                    </li>
                                    <li  class="list-group-item" @if (session()->get('locale') == "ar") style="text-align:right;" @else style="text-align:left;" @endif>
                                        <span class="badge badge-danger badge-pill float-right">{{ $course->end_date }}</span>
                                        {{ trans('admin.end_date') }}
                                    </li>
                                    <li  class="list-group-item" @if (session()->get('locale') == "ar") style="text-align:right;" @else style="text-align:left;" @endif>
                                        <span class="badge badge-secondary badge-pill float-right">{{ $course->course_student_count }}</span>
                                        {{ trans('admin.students no') }}
                                    </li>
                                    <li  class="list-group-item" @if (session()->get('locale') == "ar") style="text-align:right;" @else style="text-align:left;" @endif>
                                        <span class="badge badge-primary badge-pill float-right">{{ $course->photo_count }}</span>
                                        {{ trans('admin.photos no') }}
                                    </li>
                                    <li  class="list-group-item" @if (session()->get('locale') == "ar") style="text-align:right;" @else style="text-align:left;" @endif>
                                        <span class="badge badge-success badge-pill float-right">{{ $course->video_count }}</span>
                                        {{ trans('admin.videos no') }}
                                    </li>
                                    <li  class="list-group-item" @if (session()->get('locale') == "ar") style="text-align:right;" @else style="text-align:left;" @endif>
                                        <span class="badge badge-warning badge-pill float-right">{{ $course->attachment_count }}</span>
                                        {{ trans('admin.attachments no') }}
                                    </li>
                                    <li  class="list-group-item" @if (session()->get('locale') == "ar") style="text-align:right;" @else style="text-align:left;" @endif>
                                        <span class="badge badge-danger badge-pill float-right">{{ $course->scorm_count }}</span>
                                        {{ trans('admin.scorms no') }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Simple User Cards -->
            <section id="simple-user-cards" class="col-md-9 col-xs-12">
                <div class="container">
                    <div class="row">
                        @foreach ($course->level as $level)
                            <div class="col-4">
                                <div class="card border-teal border-lighten-2">
                                    <div class="text-center">
                                        <div class="card-body">
                                            <h4 class="card-title">{{ $level->title }}</h4>
                                        </div>
                                        <div class="text-center">
                                            <a href="{{ eurl('/photos/levels/'.$level->id.'/'.$subscriber_id) }}" class="btn btn-social-icon mr-1 mb-1 btn-outline-facebook" title="{{ trans('admin.Photos') }}">
                                                <span class="la la-image"></span>
                                            </a>
                                            &nbsp;&nbsp;
                                            <a href="{{ eurl('/videos/levels/'.$level->id.'/'.$subscriber_id) }}" class="btn btn-social-icon mr-1 mb-1 btn-outline-twitter" title="{{ trans('admin.videos') }}">
                                                <span class="la la-youtube"></span>
                                            </a>
                                            &nbsp;&nbsp;
                                            <a href="{{ eurl('/attachments/levels/'.$level->id.'/'.$subscriber_id) }}" class="btn btn-social-icon mb-1 btn-outline-linkedin" title="{{ trans('admin.attachments') }}">
                                                <span class="la la-file font-medium-4"></span>
                                            </a>
                                            &nbsp;&nbsp;
                                            <a href="{{ eurl('/scorms/levels/'.$level->id.'/'.$subscriber_id) }}" class="btn btn-social-icon mb-1 btn-outline-facebook" title="{{ trans('admin.Scorms') }}">
                                                <span class="la la-book font-medium-4"></span>
                                            </a>
                                            &nbsp;&nbsp;
                                            <a href="{{ eurl('/projects/levels/'.$level->id.'/'.$subscriber_id) }}" class="btn btn-social-icon mb-1 btn-outline-twitter" title="{{ trans('admin.projects') }}">
                                                <span class="fa fa-project-diagram font-medium-4"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

        </div>
    </div>
@endsection
