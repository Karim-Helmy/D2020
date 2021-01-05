@extends('admin.layouts.app')
@section('styles')
    <style>
    .form-red{
        border-color: #FF0000 !important;
        color:#FF0000 !important;
    }
    .help-block{
        color: #FF0000;
    }
    .alert-danger{
        display:none;
    }
    </style>
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
            <h3 class="content-header-title mb-0 d-inline-block">{{ trans('admin.excel') }}</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ eurl("/users/".$id) }}">{{ trans('admin.users') }}</a>
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
                            <h4 class="card-title" id="horz-layout-icons">{{ trans('admin.excel') }}</h4>
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
                                <form class="form form-horizontal striped-rows form-bordered" method="post" action="{{ eurl('/users/import/store/'.$id) }}">
                                    @csrf

                                    @foreach ($excel_rows as $key => $excel)

                                        <div class="form_wrapper m-b-2"  id="show_{{ $excel->id }}">
                                            <div class="row ">

                                                <!-- Name -->
                                                <div class="col-md-4 col-sm-6" style="margin:10px 0;">
                                                    <label class="req">{{ trans('admin.name') }}</label>
                                                    <input type="text" name="name[{{ $key }}]" class="{{ $errors->has('name.'.$key) ? 'form-control form-red' : 'form-control'  }}" value="{{ $excel->name}}" required>
                                                    @if ($errors->has('name.'.$key))
                                                        <span class="help-block">
                                                            <span class="help-block">{{ $errors->first('name.'.$key) }}</span>
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- UserName -->
                                                <div class="col-md-4 col-sm-6" style="margin:10px 0;">
                                                    <label class="req">{{ trans('admin.username') }}</label>
                                                    <input type="text" name="username[{{ $key }}]" class="{{ $errors->has('username.'.$key) ? 'form-control form-red' : 'form-control'  }}" value="{{ $excel->username }}" required>
                                                    @if ($errors->has('username.'.$key))
                                                        <span class="help-block">
                                                            <span class="help-block">{{ $errors->first('username.'.$key) }}</span>
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Password -->
                                                <div class="col-md-4 col-sm-6" style="margin:10px 0;">
                                                    <label class="req">{{ trans('admin.password') }}</label>
                                                    <input type="text" name="password[{{ $key }}]" class="{{ $errors->has('password.'.$key) ? 'form-control form-red' : 'form-control'  }}" value="{{ $excel->password }}" required>
                                                    @if ($errors->has('password.'.$key))
                                                        <span class="help-block">
                                                            <span class="help-block">{{ $errors->first('password.'.$key) }}</span>
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Email -->
                                                <div class="col-md-4 col-sm-6" style="margin:10px 0;">
                                                    <label class="req">{{ trans('admin.email') }}</label>
                                                    <input type="email" name="email[{{ $key }}]" class="{{ $errors->has('email.'.$key) ? 'form-control form-red' : 'form-control'  }}" value="{{ $excel->email }}" required>
                                                    @if ($errors->has('email.'.$key))
                                                        <span class="help-block">
                                                            <span class="help-block">{{ $errors->first('email.'.$key) }}</span>
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Mobile -->
                                                <div class="col-md-4 col-sm-6" style="margin:10px 0;">
                                                    <label class="req">{{ trans('admin.mobile') }}</label>
                                                    <input type="number" name="mobile[{{ $key }}]" class="{{ $errors->has('mobile.'.$key) ? 'form-control form-red' : 'form-control'  }}" value="{{ $excel->mobile }}" required>
                                                    @if ($errors->has('mobile.'.$key))
                                                        <span class="help-block">
                                                            <span class="help-block">{{ $errors->first('mobile.'.$key) }}</span>
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- ID NUMBER -->
                                                <div class="col-md-4 col-sm-6" style="margin:10px 0;">
                                                    <label class="req">{{ trans('admin.id_number') }}</label>
                                                    <input type="number" name="id_number[{{ $key }}]" class="{{ $errors->has('id_number.'.$key) ? 'form-control form-red' : 'form-control'  }}" value="{{ $excel->id_number }}" required>
                                                    @if ($errors->has('id_number.'.$key))
                                                        <span class="help-block">
                                                            <span class="help-block">{{ $errors->first('id_number.'.$key) }}</span>
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Type -->
                                                <div class="col-md-4 col-sm-6" style="margin:10px 0;">
                                                    <label class="req">{{ trans('admin.type') }}</label>
                                                    <select  name="type[{{ $key }}]" required id="type" class="{{ $errors->has('type.'.$key) ? 'form-control form-red' : 'form-control'  }}"  required>
                                                        <option value="">{{ trans('admin.type') }}</option>
                                                        <option value="3" {{ $excel->type == '3' ? "selected" : ""   }}>{{ trans('admin.student') }}</option>
                                                        <option value="2" {{ $excel->type == '2' ? "selected" : ""   }}>{{ trans('admin.trainer') }}</option>
                                                        <option value="4" {{ $excel->type == '4' ? "selected" : ""   }}>{{ trans('admin.father') }}</option>
                                                    </select>
                                                    @if ($errors->has('type.'.$key))
                                                        <span class="help-block">
                                                            <span class="help-block">{{ $errors->first('type.'.$key) }}</span>
                                                        </span>
                                                    @endif
                                                </div>


                                            </div>
                                            <button class="btn btn-danger btn-min-width btn-glow center-block mb-1" type="button" style="margin:auto; display:block;" onclick="DeleteRow({{$excel->id}})"><i
                                                    class="ft-delete"></i> {{ trans('admin.delete') }}
                                            </button>
                                        </div>


                                    @endforeach
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
