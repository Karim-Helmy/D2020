@extends('admin.layouts.app')
@section('content')
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">{{ $title }}</h3>
        <div class="row breadcrumbs-top d-inline-block">
          <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ eurl('/assign/create/'.$id.'/'.$subscriber_id) }}">{{ trans('admin.add') }}</a>
              </li>
            </ol>
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
                                            <th>ID</th>
                                            <th>{{ trans('admin.name') }}</th>
                                            <th>{{ trans('admin.type') }}</th>
                                            <th>{{ trans('admin.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr id="show_{{ $user->id }}">
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->user->name }}</td>
                                                <td>{{ $user->type == 2 ? trans('admin.trainer') : trans('admin.student') }}</td>
                                                <td class="table_options">
                                                    <a href="#" class="btn btn-danger" onclick="DeleteRow({{$user->id}})" ><i
                                                        class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>{{ trans('admin.name') }}</th>
                                                        <th>{{ trans('admin.type') }}</th>
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
                    <input type="hidden" value="{{ csrf_token() }}" id="csrf_token" />
                    <!--/ Column selectors table -->
                </div>
            @endsection
            @section('scripts')
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
                                        url: "{{eurl("/assign/destroy/".$subscriber_id)}}",
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
                                        url: "{{eurl("/assign/destroy/".$subscriber_id)}}",
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
