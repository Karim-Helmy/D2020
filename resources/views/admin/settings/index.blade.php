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
                          <th>{{ trans('admin.key') }}</th>
                          <th>{{ trans('admin.value') }}</th>
                          <th>{{ trans('admin.edit') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($index as $i)
                            <tr>
                                <td>{{ trans('admin.'.$i->key) }}</td>
                              @if ($i->type == "image")
                                <td><img src="{{ asset('uploads/'.$i->value) }}" style="height:100px;" ></td>
                              @else
                                <td>{!! str_limit($i->value, $limit = 250, $end = '...') !!}</td>
                              @endif
                                <td>
                                    <a href="{{ route('settings.edit', [$i->id]) }}" class="btn btn-secondary btn-min-width mr-1 mb-1"><i class="ft-edit"></i> {{ trans('admin.edit') }}</a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                          <th>{{ trans('admin.key') }}</th>
                          <th>{{ trans('admin.value') }}</th>
                          <th>{{ trans('admin.edit') }}</th>
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
