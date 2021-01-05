@extends('admin.layouts.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery.fancybox.min.css')}}">
@endsection
@section('content')
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">{{ trans('admin.Attachments') }}</h3>
        <div class="row breadcrumbs-top d-inline-block">
          <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ eurl('/attachments/create/'.$id.'/'.$subscriber_id) }}">{{ trans('admin.add') }}</a>
              </li>
              <li class="breadcrumb-item"><a href="{{ eurl('/attachments/choose/'.$id.'/'.$subscriber_id) }}">{{ trans('admin.choose') }}</a>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
        <section class="our_courses_table m-t-2">
            <div class="container">
                <div class="row">
                    @foreach ($attachments as $attachment)
                        <div class="col-md-4 col-sm-6 m-b-1" id="show_{{ $attachment->attachment_id }}">
                            <div class="card">
                                <a  href="{{ asset('uploads/'.$attachment->attachment->attachments) }}">
                                    <br />
                                  <div style="text-align:center"><i style="font-size: 08.4rem;" class="fa fa-file"></i></div>
                                </a>
                                <div class="card-body">
                                  <p class="card-text" style="text-align:center"><a href="{{ asset('uploads/'.$attachment->attachment->attachments) }}">{{ $attachment->attachment->title }}
                                    </a></p>
                                    <br />
                                    @if ($attachment->attachment->user)
                                        <a href="{{ eurl('/attachments/edit/'.$attachment->attachment_id.'/'.$subscriber_id) }}"  class="btn btn-info btn-min-width btn-glow mr-1 mb-1" ><i class="fa fa-edit" style="color:#FFF"></i></a>
                                    @endif
                                    <a href="#" onclick="DeleteRow({{$attachment->attachment_id}})"  class="btn btn-danger btn-min-width btn-glow mr-1 mb-1" style="color:#FFF;" ><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <input type="hidden" value="{{ csrf_token() }}" id="csrf_token" />
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ asset('frontend/js/jquery.fancybox.min.js')}}"></script>
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
                            url: "{{eurl("/attachments/destroy/".$subscriber_id)}}",
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
                            url: "{{eurl("/attachments/destroy/".$subscriber_id)}}",
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
