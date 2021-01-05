@extends('admin.layouts.app')

@section('content')

            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">{{ trans('admin.add user') }}</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ eurl('/assign/'.$id.'/'.$subscriber_id) }}">{{ trans('admin.user courses') }}</a>
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
                                    <h4 class="card-title" id="horz-layout-icons">{{ trans('admin.add user') }}</h4>
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
                                        <form class="form form-horizontal striped-rows form-bordered" method="post"  action="{{ eurl('/assign/store/'.$id.'/'.$subscriber_id) }}">
                                            @csrf
                                            <div class="form-body">

                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.search') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <select  name="type" id="type" class="form-control"  required>
                                                                <option value="1" {{ old('type') == '1' ? "selected" : ""   }}>{{ trans('admin.write-name') }}</option>
                                                                <option value="2" {{ old('type') == '2' ? "selected" : ""   }}>{{ trans('admin.multi-select') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row"  id="id" style="display:none;">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.search') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text"  name="user_id" class="form-control typeahead" autocomplete="off"  value="{{ request()->name }}" >
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row"  id="name" style="display:none;">
                                                    <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.search') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <select name="user_id[]" multiple class="form-control">
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                            @endforeach
                                                            </select>
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
<script>
$(document).ready(function() {
    if($('#type').val() == '2'){
        $('#id').hide();
        $('#name').show();
    }else{
        $('#id').show();
        $('#name').hide();
    }
    $('#type').change(function() {
        if($(this).val() == '2'){
            $('#id').hide();
            $('#name').show();
        }
        if($(this).val() != '2'){
            $('#id').show();
            $('#name').hide();
        }
    })
})
</script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
<script type="text/javascript">
    var path = "{{ route('employer.autocomplete.course',[$subscriber_id]) }}";
    $('input.typeahead').typeahead({
        source:  function (query, process) {
            return $.get(path, { query: query }, function (data) {
                return process(data);
            });
        }
    });
</script>
@endsection
