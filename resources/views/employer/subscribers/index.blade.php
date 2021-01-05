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
                                            <th>{{ trans('admin.name') }}</th>
                                            <th>{{ trans('admin.email') }}</th>
                                            <th>{{ trans('admin.mobile') }}</th>
                                            <th>{{ trans('admin.school') }}</th>
                                            <th>{{ trans('admin.package') }}</th>
                                            <th>{{ trans('admin.date') }}</th>
                                            <th>{{ trans('admin.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($index->subscriber as $subscriber)
                                            <tr id="show_{{ $subscriber->id }}">
                                                <td>{{ $subscriber->name }}</td>
                                                <td>{{ $subscriber->email }}</td>
                                                <td>{{ $subscriber->phone }}</td>
                                                <td>{{ $subscriber->school }}</td>
                                                <td><a href="{{ aurl('/packages/show/'.$subscriber->package->id) }}">{{ $subscriber->package->name }}</a></td>
                                                <td>{{ date('Y-m-d',strtotime($subscriber->created_at)) }}</td>
                                                <td>
                                                    <a href="{{ eurl('/courses/'.$subscriber->id) }}" class="btn btn-primary btn-min-width mr-1 mb-1"><i
                                                        class="ft-eye"></i> {{ trans('admin.courses') }}</a>
                                                        &nbsp; &nbsp; &nbsp;
                                                        <a href="{{ eurl('/users/'.$subscriber->id) }}" class="btn btn-primary btn-min-width mr-1 mb-1"><i
                                                            class="ft-eye"></i> {{ trans('admin.users') }}</a>
                                                            &nbsp; &nbsp; &nbsp;
                                                            <a href="{{ eurl('/subscribers/edit-password/'.$subscriber->id) }}" class="btn btn-secondary btn-min-width mr-1 mb-1"><i
                                                                class="ft-edit"></i> {{ trans('admin.edit password') }}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th>{{ trans('admin.name') }}</th>
                                                        <th>{{ trans('admin.email') }}</th>
                                                        <th>{{ trans('admin.mobile') }}</th>
                                                        <th>{{ trans('admin.school') }}</th>
                                                        <th>{{ trans('admin.package') }}</th>
                                                        <th>{{ trans('admin.date') }}</th>
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
