@extends('admin.layouts.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/app-assets/css-rtl/plugins/animate/animate.css')}}">
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
            <h3 class="content-header-title mb-0 d-inline-block">{{ $title }}</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">

                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <!-- HTML5 export buttons table -->
        <!-- Column selectors table -->
        <section id="column-selectors">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ $title }}</h4>
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
                        <div class="card-content collapse show">
                            <div class="card-body card-dashboard">
                                <table class="table table-striped table-bordered dataex-html5-selectors">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('admin.name') }}</th>
                                            <th>{{ trans('admin.email') }}</th>
                                            <th>{{ trans('admin.mobile') }}</th>
                                            <th>{{ trans('admin.city') }}</th>
                                            <th>{{ trans('admin.date') }}</th>
                                            <th>{{ trans('admin.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($index as $subscriber)
                                            <tr id="show_{{ $subscriber->id }}">
                                                <td>{{ $subscriber->name }}</td>
                                                <td>{{ $subscriber->email }}</td>
                                                <td>{{ $subscriber->phone }}</td>
                                                <td>{{ $subscriber->city->name }}</td>
                                                <td>{{ date('Y-m-d',strtotime($subscriber->created_at)) }}</td>
                                                <td>
                                                    @if ($subscriber->description)
                                                        <button type="button" class="btn btn-info btn-min-width mr-1 mb-1" data-toggle="modal"
                                                        data-target="#rotateInUpRight_{{ $subscriber->id }}">
                                                        {{ trans('admin.notes') }}
                                                    </button>
                                                    <!-- Modal -->
                                                    <div class="modal animated rotateInUpRight text-left" id="rotateInUpRight_{{ $subscriber->id }}" tabindex="-1"
                                                        role="dialog" aria-labelledby="myModalLabel68" aria-hidden="true">
                                                        <!-- Modal -->
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="myModalLabel_{{ $subscriber->id }}">{{ trans('admin.notes') }}</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h5>{{ trans('admin.notes') }}</h5>
                                                                    <p>{!! $subscriber->description !!}
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">{{ trans('admin.close') }}</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($subscriber->status == 1)
                                                    <a href="{{ aurl('/subscribers/active/'.$subscriber->id) }}" class="btn btn-warning btn-min-width mr-1 mb-1"><i
                                                        class="la la-check-circle-o"></i> {{ trans('admin.deactivate') }}</a>
                                                    @else
                                                        <a href="{{ aurl('/subscribers/active/'.$subscriber->id) }}" class="btn btn-success btn-min-width mr-1 mb-1"><i
                                                            class="la la-check-circle-o"></i> {{ trans('admin.activation') }}</a>
                                                        @endif
                                                        &nbsp;&nbsp;&nbsp;
                                                        <a href="{{ aurl('/subscribers/edit/'.$subscriber->id) }}" class="btn btn-secondary btn-min-width mr-1 mb-1"><i
                                                            class="ft-edit"></i> {{ trans('admin.edit') }}</a>
                                                            <a href="{{ aurl('/subscribers/edit-password/'.$subscriber->id) }}" class="btn btn-secondary btn-min-width mr-1 mb-1"><i
                                                                class="ft-edit"></i> {{ trans('admin.edit password') }}</a>
                                                                <input type="hidden" value="{{ csrf_token() }}" id="csrf_token" />
                                                                &nbsp;&nbsp;&nbsp;
                                                                <button class="btn btn-danger btn-min-width btn-glow mr-1 mb-1" onclick="DeleteRow({{$subscriber->id}})"><i
                                                                    class="ft-delete"></i> {{ trans('admin.delete') }}
                                                                </button>
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>{{ trans('admin.name') }}</th>
                                                        <th>{{ trans('admin.email') }}</th>
                                                        <th>{{ trans('admin.mobile') }}</th>
                                                        <th>{{ trans('admin.city') }}</th>
                                                        <th>{{ trans('admin.date') }}</th>
                                                        <th>{{ trans('admin.actions') }}</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!--/ Column selectors table -->
                </div>
            @endsection
            @section('scripts') @if (session()->get('locale') == "ar")
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
                                    url: "{{aurl("/subscribers/destroy/")}}",
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
                                    url: "{{aurl("/subscribers/destroy/")}}",
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
            <script src="{{ asset('backend/app-assets/js/scripts/modal/components-modal.js')}}" type="text/javascript"></script>
        @endsection
