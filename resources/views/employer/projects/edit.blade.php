@extends('admin.layouts.app')
@section('content')
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">{{ trans('admin.edit') }}</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ eurl('/projects/levels/'.$level->id.'/'.$subscriber_id) }}">{{ trans('admin.projects') }}</a>
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
                                    <h4 class="card-title" id="horz-layout-icons">{{ trans('admin.edit') }}</h4>
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
                                        <form class="form form-horizontal striped-rows form-bordered" method="post"   enctype="multipart/form-data"  action="{{ route('employer.project.update', [$edit->id,$subscriber_id]) }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="form-body">

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.title') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text" required  class="form-control" placeholder="{{ trans('admin.title') }}"
                                                            name="title" value="{{ $edit->title }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.start_date') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="date" placeholder="{{ trans('admin.start_date') }}"
                                                            name="start_date" autocomplete="off" value="{{ $edit->start_date }}" class="form-control date_only" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.end_date') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="date" placeholder="{{ trans('admin.end_date') }}"
                                                            name="end_date" autocomplete="off" value="{{ $edit->end_date }}" class="form-control date_only" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.total') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="number" required  class="form-control" placeholder="{{ trans('admin.total') }}"
                                                            name="total" value="{{ $edit->total }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.students') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <select  name="type" id="type" class="form-control"  required>
                                                                <option value="2" {{ $edit->type == '2' ? "selected" : ""   }}>{{ trans('admin.all students') }}</option>
                                                                <option value="1" {{ $edit->type == '1' ? "selected" : ""   }}>{{ trans('admin.select students') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="group" class="col-md-12 col-sm-12" style="display:none;">

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.groups') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <select name="group_id[]" multiple class="form-control">
                                                                @foreach ($groups as $group)
                                                                    <option value="{{ $group->id }}">{{ $group->title }}</option>
                                                                @endforeach
                                                           </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.users') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <select name="user_id[]" multiple class="form-control">
                                                                @foreach ($users as $user)
                                                                    <option value="{{ $user->user->id }}"{{ in_array($user->user->id, $choose ?? []) ? "selected" : ""   }}>{{ $user->user->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.attachments') }}</label>
                                                <div class="col-md-9">
                                                    <div class="position-relative has-icon-left">
                                                        <input type="file" class="form-control" name="image" />
                                                        <div class="form-control-position">
                                                            <i class="la la-briefcase"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.description') }}</label>
                                                <div class="col-md-9">
                                                    <div class="position-relative has-icon-left">
                                                        <textarea class="form-control" rows="4"  name="description" >{{ $edit->description }}</textarea>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.file_upload') }}</label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <div class="d-inline-block custom-control custom-radio mr-1" style="margin: auto 20px !important;">
                                                            <input type="radio" name="file_upload" value="1" {{ $edit->file_upload == '1' ? 'checked' : '' }} class="custom-control-input" id="yes" >
                                                            <label class="custom-control-label" for="yes">{{ trans('admin.yes') }}</label>
                                                        </div>
                                                        <div class="d-inline-block custom-control custom-radio">
                                                            <input type="radio" name="file_upload" value="0" {{ $edit->file_upload == '0' ? 'checked' : '' }} class="custom-control-input" id="no" >
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

@section('scripts')
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">
        CKEDITOR.replace('description', {
            language: 'ar',
        });
    </script>
    <script>
    $(document).ready(function() {
        if($('#type').val() == '1'){
            $('#group').show();
        }
        $('#type').change(function() {
            if($(this).val() == '1'){
                $('#group').show();
            }
            if($(this).val() != '1'){
                $('#group').hide();
            }
        })
    })
    </script>
@endsection
