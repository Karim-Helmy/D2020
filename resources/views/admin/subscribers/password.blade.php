@extends('admin.layouts.app')

@section('content')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
            <h3 class="content-header-title mb-0 d-inline-block">{{ $title }}</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ aurl("/subscribers") }}">{{ trans("admin.subscribers") }}</a>
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
                                <form class="form form-horizontal striped-rows form-bordered" method="post"  action="{{ route('subscribers.update_password', [$edit->id]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-body">

                                        <!-- UserName -->
                                        <div class="form-group row">
                                            <label class="col-md-3 label-control">{{ trans('admin.username') }}</label>
                                            <div class="col-md-9">
                                                <div class="position-relative has-icon-left">
                                                    <input type="text" required  class="form-control" placeholder="{{ trans('admin.username') }}"
                                                    name="username" value="{{ $edit->username }}" >
                                                    <div class="form-control-position">
                                                        <i class="la la-envelope"></i>
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
                                                    name="password" >
                                                    <div class="form-control-position">
                                                        <i class="la la-envelope"></i>
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
                                                    name="password_confirmation" >
                                                    <div class="form-control-position">
                                                        <i class="la la-envelope"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-actions right">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="la la-check-square-o"></i> {{ trans("admin.edit") }}
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
