@extends('admin.layouts.app')

@section('content')
    <div class="row page-titles">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="text-themecolor m-b-0 m-t-0">{{$title}}</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">{{$title}}</li>
            </ol>
        </div>

    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-block">
                    <h4 class="card-title">{{$title}}</h4>
                    <form class="form-material m-t-40" method="post"  action="{{ route('settings.store') }}"  enctype="multipart/form-data">
                      @csrf


                        <div class="form-group {{ $errors->has('key') ? ' has-error has-danger' : '' }} ">
                            <label>Key<span style="color:red">*</span></label>
                            <input type="text"  value="{{ old('key') }}" name="key" class="form-control form-control-line"  required>
                            @if ($errors->has('key'))
                                <span class="help-block">
                                    <strong class="help-block">{{ $errors->first('key') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('type') ? ' has-error has-danger' : '' }} ">
                            <label>Type<span style="color:red">*</span></label>
                            <select  name="type" class="form-control"  required>
                              <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Text</option>
                              <option value="longtext" {{ old('type') == 'longtext' ? 'selected' : '' }}>Long Text</option>
                              <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Image</option>
                            </select>
                            @if ($errors->has('type'))
                                <span class="help-block">
                                    <strong class="help-block">{{ $errors->first('type') }}</strong>
                                </span>
                            @endif
                        </div>





                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-2 col-md-10">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
