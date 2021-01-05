@extends('admin.layouts.app')


@section('content')
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">{{ $title }}</h3>
        <div class="row breadcrumbs-top d-inline-block">
          <div class="breadcrumb-wrapper col-12">

          </div>
        </div>
      </div>
    </div>
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
                          <th>{{ trans('admin.student no') }}</th>
                          <th>{{ trans('admin.trainer no') }}</th>
                          <th>{{ trans('admin.father no') }}</th>
                          <th>{{ trans('admin.Photos') }}</th>
                          <th>{{ trans('admin.videos') }}</th>
                          <th>{{ trans('admin.attachments') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($index as $subscriber)
                            <tr>
                                <td>{{ $subscriber['name'] }}</td>
                                <td>{{ $subscriber['email'] }}</td>
                                <td>{{ $subscriber['phone'] }}</td>
                                <td>{{ $subscriber['school'] }}</td>
                                <td>{{ $subscriber['student'] }}</td>
                                <td>{{ $subscriber['trainer'] }}</td>
                                <td>{{ $subscriber['father'] }}</td>
                                <td>{{ $subscriber['photo'] }}</td>
                                <td>{{ $subscriber['video'] }}</td>
                                <td>{{ $subscriber['attachment'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                          <th>{{ trans('admin.name') }}</th>
                          <th>{{ trans('admin.email') }}</th>
                          <th>{{ trans('admin.mobile') }}</th>
                          <th>{{ trans('admin.school') }}</th>
                          <th>{{ trans('admin.student no') }}</th>
                          <th>{{ trans('admin.trainer no') }}</th>
                          <th>{{ trans('admin.father no') }}</th>
                          <th>{{ trans('admin.Photos') }}</th>
                          <th>{{ trans('admin.videos') }}</th>
                          <th>{{ trans('admin.attachments') }}</th>
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
