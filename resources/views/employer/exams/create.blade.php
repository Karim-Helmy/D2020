@extends('admin.layouts.app')
@section('content')
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">{{ trans('admin.add') }}</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ eurl('/exams/'.$id.'/'.$subscriber_id) }}">{{ trans('admin.exams') }}</a>
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
                                    <h4 class="card-title" id="horz-layout-icons">{{ trans('admin.add') }}</h4>
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
                                        <form class="form form-horizontal striped-rows form-bordered" method="post"   enctype="multipart/form-data"  action="{{ eurl('/exams/store/'.$id.'/'.$subscriber_id) }}">
                                            @csrf
                                            <div class="form-body">

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.title') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text" required  class="form-control" placeholder="{{ trans('admin.title') }}"
                                                            name="title" value="{{ old('title') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.levels') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <select class="form-control"  name="level_id">
                                                              <option value="" {{ old('level_id') == null ? "selected" : ""   }}>{{ trans('admin.comprehensive') }}</option>
                                                              @foreach ($levels as $level)
                                                                  <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? "selected" : ""   }}>{{ $level->title }}</option>
                                                              @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.start_date') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="date" placeholder="{{ trans('admin.start_date') }}"
                                                            name="start_date" autocomplete="off" value="{{ old('start_date') }}" class="form-control date_only" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.end_date') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="date" placeholder="{{ trans('admin.end_date') }}"
                                                            name="end_date" autocomplete="off" value="{{ old('end_date') }}" class="form-control date_only" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.time_minutes') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="number" required  class="form-control" placeholder="{{ trans('admin.time_minutes') }}"
                                                            name="time_minutes" value="{{ old('time_minutes') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.success_average') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="number" required  class="form-control" placeholder="{{ trans('admin.success_average') }}"
                                                            name="success_average" value="{{ old('success_average') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.try_no') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="number" required  class="form-control" placeholder="{{ trans('admin.try_no') }}"
                                                            name="try_no" value="{{ old('try_no') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.question_no') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="number" required  class="form-control" placeholder="{{ trans('admin.question_no') }}"
                                                            name="question_no" value="{{ old('question_no') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.students') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <select  name="type" id="type" class="form-control"  required>
                                                                <option value="2" {{ old('type') == '2' ? "selected" : ""   }}>{{ trans('admin.all students') }}</option>
                                                                <option value="1" {{ old('type') == '1' ? "selected" : ""   }}>{{ trans('admin.select students') }}</option>
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
                                                                    <option value="{{ $group->id }}" {{ in_array($group->id, old('group_id') ?? []) ? "selected" : ""   }}>{{ $group->title }}</option>
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
                                                                    <option value="{{ $user->user->id }}" {{ in_array($user->user->id, old('user_id') ?? []) ? "selected" : ""   }}>{{ $user->user->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>


                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.description') }}</label>
                                                <div class="col-md-9">
                                                    <div class="position-relative has-icon-left">
                                                        <textarea class="form-control" rows="4"  name="description" >{{ old('description') }}</textarea>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.question_choose') }}</label>
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <div class="d-inline-block custom-control custom-radio mr-1" style="margin: auto 20px !important;">
                                                            <input type="radio" name="question_choose" value="1" {{ old('question_choose') == '1' ? 'checked' : '' }} class="custom-control-input" id="yes" >
                                                            <label class="custom-control-label" for="yes">{{ trans('admin.yes') }}</label>
                                                        </div>
                                                        <div class="d-inline-block custom-control custom-radio">
                                                            <input type="radio" name="question_choose" value="0" {{ old('question_choose') == '0' ? 'checked' : '' }} class="custom-control-input" id="no" >
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
