@extends('admin.layouts.app')
@section('styles')
    @if (session()->get('locale') == "ar")
        <link rel="stylesheet" type="text/css" href="{{ asset('backend/app-assets/css-rtl/pages/chat-application.css')}}">
    @else
        <link rel="stylesheet" type="text/css" href="{{ asset('backend/app-assets/css/pages/chat-application.css')}}">
    @endif
@endsection
@section('breadcrumbs2')
    <ul id="breadcrumb">
        <li><a href="{{ turl('/') }}"><span class="fa fa-home"> </span></a></li>
        <li><a href="{{ turl('/courses') }}"><span class="icon icon-beaker"> </span> {{ trans('admin.courses') }}</a></li>
        <li><a href="{{ turl('/courses/overview/'.$course_id) }}"><span class="icon icon-beaker"> </span> {{ trans('admin.overview') }}</a></li>
        <li><a href="{{ turl('/courses/show/'.$course_id) }}"><span class="icon icon-beaker"> </span> {{ trans('admin.show') }}</a></li>
        <li><a href="{{ turl('/discussions/'.$course_id) }}"><span class="icon icon-beaker"> </span> {{ trans('admin.discussions') }}</a></li>
        <li><a href="#"><span class="icon icon-beaker"> </span> {{ trans('admin.show') }}</a></li>
    </ul>
    <br /><br />
@endsection
@section('content')
    <div class="col-md-9">
        @include('trainer.includes.messages')
        <div class="chat-application">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col text-center d-none d-md-inline-block">
                    <h3 class="main_title">{{ trans('admin.discussions') }}</h3>
                </div>
            </div>
            <br />
            <section class="chat-app-window">
                <div class="badge badge-default mb-1"></div>
                <div class="chats">
                    <div class="chats">

                        @foreach ($discussions as $discussion)
                            <div {{ $discussion->user_id != auth()->id() ? 'class=chat-left' : "class=chat" }}  id="show_{{ $discussion->id }}">
                                <div class="chat-avatar">
                                    <a class="avatar" data-toggle="tooltip" href="#"  {{ $discussion->user_id != auth()->id() ? 'data-placement=right' : "data-placement=left" }} title=""
                                        data-original-title="">
                                        @if($discussion->user->photo)
                                            <img style="height:50px; width:50px;" src="{{ asset('uploads/'.$discussion->user->photo)}}" alt="profile"/>
                                        @else
                                            <img style="height:50px; width:50px;"  src="{{ asset('backend/app-assets/images/icons/user.png')}}" alt="profile"/>
                                        @endif
                                    </a>
                                </div>
                                <div class="chat-body">
                                    <div class="chat-content">
                                        <p style="font-size:20px;">{{ $discussion->user->name }} -
                                            @if ($discussion->type == '1')
                                                {{ trans('admin.supervisor') }}
                                            @elseif ($discussion->type == '2')
                                                {{ trans('admin.trainer') }}
                                            @else
                                                {{ trans('admin.student') }}
                                            @endif
                                        </p>
                                        <hr />
                                        <p>{{ $discussion->comment }}</p>
                                        <br />
                                        <p class="time" style="text-align:inherit">{{ $discussion->created_at }}</p>
                                        <br />
                                        <a href="#"  onclick="DeleteRow({{$discussion->id}})" class="btn btn-danger">{{ trans('admin.delete') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <input type="hidden" value="{{ csrf_token() }}" id="csrf_token" />
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('backend/app-assets/js/scripts/chat-application.js')}}" type="text/javascript"></script>
    @if (session()->get('locale') == "ar")
        <script>
            function DeleteRow(id) {
                event.preventDefault();
                swal({
                    title: "هل انت متأكد من الحذف ؟",
                    text: "في حالة الموافقة على الحذف سيتم حذف البيانات نهائيا",
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: "لا أريد الحذف",
                            value: null,
                            visible: true,
                            className: "",
                            closeModal: false,
                        },
                        confirm: {
                            text: "نعم أريد الحذف",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: false
                        }
                    }
                })
                .then((isConfirm) => {
                    if (isConfirm) {
                        $.ajax({
                            type: 'post',
                            url: "{{eurl("/discussions/destroy/item/".$subscriber_id)}}",
                            data: {
                                '_token': $('#csrf_token').val(),
                                'id': id,
                            },

                        });
                        $('#show_' + id).remove();
                        swal("تم الحذف!", "البيانات تم حذفها بنجاح", "success");
                    } else {
                        swal("تم الغاء الطلب", "لم يتم حذف الداتا", "error");
                    }
                });

            }

        </script>
    @else
        <script>
            function DeleteRow(id) {
                event.preventDefault();
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this imaginary file!",
                    icon: "warning",
                    buttons: {
                        cancel: {
                            text: "No, cancel",
                            value: null,
                            visible: true,
                            className: "",
                            closeModal: false,
                        },
                        confirm: {
                            text: "Yes, delete it!",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: false
                        }
                    }
                })
                .then((isConfirm) => {
                    if (isConfirm) {
                        $.ajax({
                            type: 'post',
                            url: "{{eurl("/discussions/destroy/item/".$subscriber_id)}}",
                            data: {
                                '_token': $('#csrf_token').val(),
                                'id': id,
                            },

                        });
                        $('#show_' + id).remove();
                        swal("Deleted!", "Your data has been deleted.", "success");
                    } else {
                        swal("Cancelled", "Your data is safe", "error");
                    }
                });

            }

        </script>
    @endif
@endsection
