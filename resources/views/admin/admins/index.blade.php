@extends('admin.layouts.app')
@section('content')
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
                                        <th>{{ trans('admin.username') }}</th>
                                        <th>{{ trans('admin.email') }}</th>
                                        <th>{{ trans('admin.phone') }}</th>
                                        <th>{{ trans('admin.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($admins as $admin)
                                    <tr id="show_{{ $admin->id }}">
                                        <td>{{ $admin->username }}</td>
                                        <td>{{ $admin->email }}</td>
                                        <td>{{ $admin->phone }}</td>
                                        <td>
                                            <a href="{{ aurl('/admins/edit/'.$admin->id) }}" class="btn btn-secondary btn-min-width mr-1 mb-1"><i class="ft-edit"></i> {{ trans('admin.edit') }}</a>
                                            <input type="hidden" value="{{ csrf_token() }}" id="csrf_token" />
                                            <button class="btn btn-danger btn-min-width btn-glow mr-1 mb-1" onclick="DeleteRow({{$admin->id}})"><i class="ft-delete"></i> {{ trans('admin.delete') }}</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>{{ trans('admin.username') }}</th>
                                        <th>{{ trans('admin.email') }}</th>
                                        <th>{{ trans('admin.phone') }}</th>
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
                              url: "{{aurl("/admins/destroy/")}}",
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
                              url: "{{aurl("/admins/destroy/")}}",
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
