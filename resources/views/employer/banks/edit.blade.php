@extends('admin.layouts.app')
@section('content')

                    <div class="content-header row">
                        <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                            <h3 class="content-header-title mb-0 d-inline-block">{{ trans('admin.edit') }}</h3>
                            <div class="row breadcrumbs-top d-inline-block">
                                <div class="breadcrumb-wrapper col-12">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ eurl('/banks/'.$id.'/'.$subscriber_id) }}">{{ trans('admin.banks') }}</a>
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
                                                <form class="form form-horizontal striped-rows form-bordered" method="post"  action="{{ route('employer.bank.update', [$edit->id,$subscriber_id]) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="form-body">

                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.levels') }}</label>
                                                            <div class="col-md-9">
                                                                <div class="position-relative has-icon-left">
                                                                    <select class="form-control" required name="level_id">
                                                                      <option value="" selected disabled>{{ trans('admin.chooose') }}</option>
                                                                      @foreach ($levels as $level)
                                                                          <option value="{{ $level->id }}" {{ $edit->level_id == $level->id ? "selected" : ""   }}>{{ $level->title }}</option>
                                                                      @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.grade') }}</label>
                                                            <div class="col-md-9">
                                                                <div class="position-relative has-icon-left">
                                                                    <input type="text" required  class="form-control" placeholder="{{ trans('admin.grade') }}"
                                                                    name="grade" value="{{ $edit->grade }}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Title -->
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.title') }}</label>
                                                            <div class="col-md-9">
                                                                <div class="position-relative has-icon-left">
                                                                    <textarea class="form-control" rows="4" name="title" >{{ $edit->title }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if ($edit->best_answer == "صح" || $edit->best_answer == "خطأ")
                                                        <div id="tf">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.Right Answer') }}</label>
                                                            <div class="col-md-9">
                                                                <div class="position-relative has-icon-left">
                                                                    <label class="checkbox_wrapper ml-3 ">
                                                                      <input type="radio" name="best_answer1" {{ 'صح' == $edit->best_answer ? 'checked' : '' }}  value="صح">
                                                                      <span class="checkLabel"></span>
                                                                      {{ trans('admin.true') }}
                                                                    </label>

                                                                    <label class="checkbox_wrapper">
                                                                      <input type="radio" name="best_answer1" {{ 'خطأ' == $edit->best_answer ? 'checked' : '' }}   value="خطأ">
                                                                      <span class="checkLabel"></span>
                                                                      {{ trans('admin.false') }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                @else
                                                    <div id="mcq">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.Right Answer') }}</label>
                                                            <div class="col-md-9">
                                                                <div class="position-relative has-icon-left">
                                                                    <input type="text" class="form-control" placeholder="{{ trans('admin.Right Answer') }}"
                                                                    name="best_answer" value="{{ $edit->best_answer }}">
                                                                </div>
                                                            </div>
                                                        </div>


                                                    <div class="form-group row">
                                                        <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.Add wrong answers') }}</label>
                                                        <div class="col-md-9 field_wrapper">
                                                            @foreach (collect(explode('|', $edit->answers))->filter(function ($value, $key) use($edit) {
                                                                return $value != $edit->best_answer;
                                                            }); as $answer)
                                                                <div class="position-relative has-icon-left">
                                                                    <input type="text" class="form-control" placeholder="{{ trans('admin.Add wrong answers') }}"
                                                                    name="answers[]" value="{{ $answer }}">
                                                                </div>
                                                                <br />
                                                          @endforeach
                                                        </div>
                                                        <a href="javascript:void(0);" class="add_button btn btn-primary" style="margin:0 auto; display:block" title="Add field">{{ trans('admin.add') }}</a>
                                                    </div>
                                                    </div>
                                                @endif

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
                CKEDITOR.replace('title', {
                    language: 'ar',
                });
            </script>
            <script type="text/javascript">
        $(document).ready(function(){
            var maxField = 10; //Input fields increment limitation
            var addButton = $('.add_button'); //Add button selector
            var wrapper = $('.field_wrapper'); //Input field wrapper
            var fieldHTML = '<div><br /><div class="position-relative has-icon-left"><input type="text" class="form-control" placeholder="{{ trans('admin.Add wrong answers') }}"name="answers[]"></div><a href="javascript:void(0);" class="remove_button"><i class="fa fa-trash"></i></a></div>'; //New input field html
            var x = 1; //Initial field counter is 1

            //Once add button is clicked
            $(addButton).click(function(){
                //Check maximum number of input fields
                if(x < maxField){
                    x++; //Increment field counter
                    $(wrapper).append(fieldHTML); //Add field html
                }
            });

            //Once remove button is clicked
            $(wrapper).on('click', '.remove_button', function(e){
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                x--; //Decrement field counter
            });
        });
        </script>
        @endsection
