@extends('admin.layouts.app')

@section('content')
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">{{ $title }}</h3>
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
                                    <h4 class="card-title" id="horz-layout-icons">{{ $title }}</h4>
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
                                        <form class="form form-horizontal striped-rows form-bordered" method="post" enctype="multipart/form-data" action="{{ eurl('/users/store/'.$id) }}">
                                            @csrf
                                            <div class="form-body">
                                                <!-- Name -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control">{{ trans('admin.name') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text" required  class="form-control" placeholder="{{ trans('admin.name') }}"
                                                            name="name" value="{{ old('name') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-user"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- UserName -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control">{{ trans('admin.username') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text" required  class="form-control" placeholder="{{ trans('admin.username') }}"
                                                            name="username" value="{{ old('username') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-user"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Password -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control">{{ trans('admin.password') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="password" required  class="form-control" placeholder="{{ trans('admin.password') }}"
                                                            name="password" value="{{ old('password') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-key"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- Password Confirmation -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control">{{ trans('admin.password_confirmation') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="password" required  class="form-control" placeholder="{{ trans('admin.password_confirmation') }}"
                                                            name="password_confirmation" value="{{ old('password_confirmation') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-key"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Email -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control">{{ trans('admin.email') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="email" required  class="form-control" placeholder="{{ trans('admin.email') }}"
                                                            name="email" value="{{ old('email') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-envelope"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Phone -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control">{{ trans('admin.mobile') }}</label>
                                                    <div class="col-md-9">
                                                        <div style="color:#FF0000">Example:- 05xxxxxxxx</div>
                                                        <div class="position-relative has-icon-left">
                                                            <input type="number" required  class="form-control" placeholder="{{ trans('admin.mobile') }}"
                                                            name="mobile" value="{{ old('mobile') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-phone"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Number ID -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control">{{ trans('admin.id_number') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="number" required  class="form-control" placeholder="{{ trans('admin.id_number') }}"
                                                            name="id_number" value="{{ old('id_number') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-square"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Address -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control">{{ trans('admin.address') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text"  class="form-control" placeholder="{{ trans('admin.address') }}"
                                                            name="address" value="{{ old('address') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-map"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Nationality -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control">{{ trans('admin.nationality') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="text"  class="form-control" placeholder="{{ trans('admin.nationality') }}"
                                                            name="nationality" value="{{ old('nationality') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-globe"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Nationality -->
                                                <div class="form-group row">
                                                    <label class="col-md-3 label-control">{{ trans('admin.birth_date') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="position-relative has-icon-left">
                                                            <input type="date"  class="form-control" placeholder="{{ trans('admin.birth_date') }}"
                                                            name="birth_date" value="{{ old('birth_date') }}">
                                                            <div class="form-control-position">
                                                                <i class="la la-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Courses -->
                                                <div class="form-group row">
                                                   <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.course') }}</label>
                                                   <div class="col-md-9">
                                                       <div class="position-relative has-icon-left">
                                                           <select  name="package_id" class="form-control"  >
                                                             @foreach ($courses as $course)
                                                                 <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                                                             @endforeach
                                                           </select>
                                                           <div class="form-control-position">
                                                               <i class="la la-server"></i>
                                                           </div>
                                                       </div>
                                                   </div>
                                               </div>

                                               <!-- Type -->
                                               <div class="form-group row">
                                                  <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.type') }}</label>
                                                  <div class="col-md-9">
                                                      <div class="position-relative has-icon-left">
                                                          <select  name="type" id="type" class="form-control"  required>
                                                              <option value="3" {{ old('type') == '3' ? "selected" : ""   }}>{{ trans('admin.student') }}</option>
                                                              <option value="2" {{ old('type') == '2' ? "selected" : ""   }}>{{ trans('admin.trainer') }}</option>
                                                              <option value="4" {{ old('type') == '4' ? "selected" : ""   }}>{{ trans('admin.father') }}</option>
                                                          </select>
                                                          <div class="form-control-position">
                                                              <i class="la la-server"></i>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>

                                              <!-- Father -->
                                              <div  id="father" style="display:none; width:100%;">
                                                  <div class="form-group row">
                                                      <label class="col-md-3 label-control">{{ trans('admin.father mobile') }}</label>
                                                      <div class="col-md-9">
                                                          <div style="color:#FF0000">Example:- 05xxxxxxxx</div>
                                                          <div class="position-relative has-icon-left">
                                                              <input type="number" id="father_mobile"  class="form-control" placeholder="{{ trans('admin.father mobile') }}"
                                                              name="father_mobile" value="{{ old('father_mobile') }}">
                                                              <div class="form-control-position">
                                                                  <i class="la la-phone"></i>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>

                                                  <div class="form-group row">
                                                      <label class="col-md-3 label-control">{{ trans('admin.father id') }}</label>
                                                      <div class="col-md-9">
                                                          <div class="position-relative has-icon-left">
                                                              <input type="number" id="father_id"  class="form-control" placeholder="{{ trans('admin.father id') }}"
                                                              name="father_id" value="{{ old('father_id') }}">
                                                              <div class="form-control-position">
                                                                  <i class="la la-square"></i>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>

                                              </div>

                                              <!-- Upload File -->
                                              <div class="form-group row">
                                                  <label class="col-md-3 label-control" for="timesheetinput2">{{ trans('admin.photo') }}</label>
                                                  <div class="col-md-9">
                                                      <div class="position-relative has-icon-left">
                                                          <input type="file"  class="form-control"
                                                          name="image" >
                                                          <div class="form-control-position">
                                                              <i class="la la-briefcase"></i>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>

                                                <!-- Status -->
                                                <div class="form-group row  mx-auto last">
                                                    <label class="col-md-3 label-control">{{ trans('admin.active') }}</label>
                                                    <div class="col-md-9">
                                                        <div class="input-group">
        													<div class="d-inline-block custom-control custom-radio mr-1" style="margin: auto 20px !important;">
        														<input type="radio" name="status" value="1" class="custom-control-input" id="yes" {{ old('status') == '1' ? 'checked' : '' }}>
        														<label class="custom-control-label" for="yes">{{ trans('admin.yes') }}</label>
        													</div>
        													<div class="d-inline-block custom-control custom-radio">
        														<input type="radio" name="status" value="0" class="custom-control-input" id="no"{{ old('status') == '0' ? 'checked' : '' }}>
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
    <script>
    $(document).ready(function() {
        if($('#type').val() == '3'){
            $('#father').show();
        }
        $('#type').change(function() {
            if($(this).val() == '3'){
                $('#father').show();
            }
            if($(this).val() != '3'){
                $('#father').hide();
            }
        })
    })
    </script>
    <script type="text/javascript">
    $(document).on('focusout','#father_mobile',function(event){
        event.preventDefault();
        $.ajax({
            url: '{{ eurl('/fathers/'.$id) }}',
            method: 'get',
            data:{
                mobile : $('#father_mobile').val()
            },
            success: function(response){
                $('#father_id').val(response.id_number);
            },
        })
    })
</script>
@endsection
