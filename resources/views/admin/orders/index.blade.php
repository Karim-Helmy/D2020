@extends('admin.layouts.app')


@section('content')
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">{{ $title }}</h3>
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
                    <table class="table table-striped table-bordered  table-bordered dataex-html5-selectors">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>{{ trans('admin.username') }}</th>
                          <th>{{ trans('admin.mobile') }}</th>
                          <th>{{ trans('admin.email') }}</th>
                          <th>{{ trans('admin.date') }}</th>
                          <th>{{ trans('admin.show') }}</th>

                        </tr>
                      </thead>
                      <tbody>
                              <tr>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td>

                                  </td>
                              </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>ID</th>
                          <th>{{ trans('admin.username') }}</th>
                          <th>{{ trans('admin.mobile') }}</th>
                          <th>{{ trans('admin.email') }}</th>
                          <th>{{ trans('admin.date') }}</th>
                          <th>{{ trans('admin.show') }}</th>

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
