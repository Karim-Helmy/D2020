@extends('admin.layouts.app')
@section('content')
       <div id="user-profile">
         <div class="row">
           <div class="col-12">
             <div class="card profile-with-cover">
               <div class="card-img-top img-fluid bg-cover height-300" style="background: url('{{asset('backend/img/profile.jpg')}}') 50%;"></div>
               <div class="media profil-cover-details w-100">
                 <div class="media-body pt-3 px-2">
                   <div class="row">
                       <div class="col">
                         <h3 class="card-title" style="font-weight:bold; font-size:20px;">{{ $package->name }}</h3>
                       </div>
                     <div class="col text-right">
                       <a href="{{ aurl('/packages/edit/'.$package->id) }}" class="btn btn-primary"><i class="la la-edit"></i> {{ trans('admin.edit') }}</a>
                       &nbsp;
                       <span class="btn btn-danger">{{ trans('admin.price') }} : {{ $package->price }}</span>
                     </div>
                   </div>
                 </div>
               </div>
               <nav class="navbar navbar-light navbar-profile align-self-end">
                 <button class="navbar-toggler d-sm-none" type="button" data-toggle="collapse" aria-expanded="false"
                 aria-label="Toggle navigation"></button>
           </div>
         </div>
       </div>

          <!-- eCommerce statistic -->
          <div class="row">

              <div class="col-xl-3 col-lg-6 col-12">
                  <div class="card pull-up">
                      <div class="card-content">
                          <div class="card-body">
                              <div class="media d-flex">
                                  <div class="media-body text-left">
                                      <h3 class="warning">{{ $subsciber_all }}</h3>
                                          <h6>{{ trans("admin.subsciber_all") }}</h6>
                                  </div>
                                  <div>
                                      <i class="la la-credit-card warning fa-3x float-right"></i>
                                  </div>
                              </div>
                              <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                  <div class="progress-bar bg-gradient-x-warning" role="progressbar" style="width: 100%" aria-valuenow="80" aria-valuemin="0"
                                       aria-valuemax="100"></div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-xl-3 col-lg-6 col-12">
                  <div class="card pull-up">
                      <div class="card-content">
                          <div class="card-body">
                              <div class="media d-flex">
                                  <div class="media-body text-left">
                                      <h3 class="success">{{ $subsciber_agree }}</h3>
                                          <h6>{{ trans("admin.subsciber_agree") }}</h6>
                                  </div>
                                  <div>
                                      <i class="la la-credit-card success la-3x float-right"></i>
                                  </div>
                              </div>
                              <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                  <div class="progress-bar bg-gradient-x-success" role="progressbar" style="width: 100%" aria-valuenow="80" aria-valuemin="0"
                                       aria-valuemax="100"></div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-xl-3 col-lg-6 col-12">
                  <div class="card pull-up">
                      <div class="card-content">
                          <div class="card-body">
                              <div class="media d-flex">
                                  <div class="media-body text-left">
                                      <h3 class="danger">{{ $subsciber_waiting }}</h3>
                                          <h6>{{ trans("admin.subsciber_waiting") }}</h6>
                                  </div>
                                  <div>
                                      <i class="la la-credit-card danger fa-3x float-right"></i>
                                  </div>
                              </div>
                              <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                  <div class="progress-bar bg-gradient-x-danger" role="progressbar" style="width: 100%" aria-valuenow="80" aria-valuemin="0"
                                       aria-valuemax="100"></div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-xl-3 col-lg-6 col-12">
                  <div class="card pull-up">
                      <div class="card-content">
                          <div class="card-body">
                              <div class="media d-flex">
                                  <div class="media-body text-left">
                                      <h3 class="info">{{ $subsciber_today }}</h3>
                                          <h6>{{ trans("admin.subsciber_today") }}</h6>
                                  </div>
                                  <div>
                                      <i class="la la-credit-card info fa-3x float-right"></i>
                                  </div>
                              </div>
                              <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                                  <div class="progress-bar bg-gradient-x-info" role="progressbar" style="width: 100%" aria-valuenow="80" aria-valuemin="0"
                                       aria-valuemax="100"></div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

          </div>
          <!--/ eCommerce statistic -->

       <!-- Accordion section start -->
          <section id="accordion">
            <div class="row">
              <!-- Accordion Default -->
              <div class="col-xl-12 col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title" style="font-weight:bold; font-size:20px;">{{ trans('admin.details') }}</h4>
                </div>
                <div class="card-content">
                  <div class="card-body">
                    <ul class="nav nav-tabs nav-underline no-hover-bg">
                      <!-- Details -->
                      <li class="nav-item">
                        <a class="nav-link active" id="base-tab31" data-toggle="tab" aria-controls="tab31" href="#tab31" aria-expanded="true">{{ trans('admin.details') }}</a>
                      </li>
                      <!-- Agreement Subscription -->
                      <li class="nav-item">
                        <a class="nav-link" id="base-tab32" data-toggle="tab" aria-controls="tab32" href="#tab32" aria-expanded="false">{{ trans('admin.subsciber_agree') }}</a>
                      </li>
                      <!-- Appending Subscription -->
                      <li class="nav-item">
                        <a class="nav-link" id="base-tab33" data-toggle="tab" aria-controls="tab33" href="#tab33" aria-expanded="false">{{ trans('admin.subsciber_waiting') }}</a>
                      </li>
                    </ul>
                    <div class="tab-content px-1 pt-1">
                      <!-- Start Details -->
                      <div role="tabpanel" class="tab-pane active" id="tab31" aria-expanded="true" aria-labelledby="base-tab31">
                          <ul class="list-group list">
                              @foreach ($package->option as $option)
                                  <li class="list-group-item">
                                      <h3 class="name">{{ $option->pivot->value}}</h3>
                                      <p class="born">{{ $option->name}}</p>
                                  </li>
                              @endforeach

                          </ul>
                      </div>
                      <!-- End Details -->
                      <!-- Start Approved subscriptions -->
                      <div class="tab-pane" id="tab32" aria-labelledby="base-tab32">
                          <table class="table table-striped table-bordered dataex-html5-selectors">
                            <thead>
                              <tr>
                                  <th>{{ trans('admin.name') }}</th>
                                  <th>{{ trans('admin.email') }}</th>
                                  <th>{{ trans('admin.mobile') }}</th>
                                  <th>{{ trans('admin.address') }}</th>
                                  <th>{{ trans('admin.domain') }}</th>
                                  <th>{{ trans('admin.school') }}</th>
                                  <th>{{ trans('admin.date') }}</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($subscibers_agree_get as $subsciber)
                                    <tr>
                                        <td>{{ $subsciber->name }}</td>
                                        <td>{{ $subsciber->email }}</td>
                                        <td>{{ $subsciber->phone }}</td>
                                        <td>{{ $subsciber->address }}</td>
                                        <td>{{ $subsciber->domain }}</td>
                                        <td>{{ $subsciber->school }}</td>
                                        <td>{{ $subsciber->created_at }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                              <tr>
                                  <th>{{ trans('admin.name') }}</th>
                                  <th>{{ trans('admin.email') }}</th>
                                  <th>{{ trans('admin.mobile') }}</th>
                                  <th>{{ trans('admin.address') }}</th>
                                  <th>{{ trans('admin.domain') }}</th>
                                  <th>{{ trans('admin.school') }}</th>
                                  <th>{{ trans('admin.date') }}</th>
                              </tr>
                            </tfoot>
                          </table>
                      </div>
                      <!-- End Approved subscriptions -->
                      <!-- Start Pending subscriptions -->
                     <div class="tab-pane" id="tab33" aria-labelledby="base-tab33">
                          <table class="table table-striped table-bordered dataex-html5-selectors">
                            <thead>
                              <tr>
                                  <th>{{ trans('admin.name') }}</th>
                                  <th>{{ trans('admin.email') }}</th>
                                  <th>{{ trans('admin.mobile') }}</th>
                                  <th>{{ trans('admin.address') }}</th>
                                  <th>{{ trans('admin.domain') }}</th>
                                  <th>{{ trans('admin.school') }}</th>
                                  <th>{{ trans('admin.date') }}</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($subscibers_waiting_get as $subsciber)
                                    <tr>
                                        <td>{{ $subsciber->name }}</td>
                                        <td>{{ $subsciber->email }}</td>
                                        <td>{{ $subsciber->phone }}</td>
                                        <td>{{ $subsciber->address }}</td>
                                        <td>{{ $subsciber->domain }}</td>
                                        <td>{{ $subsciber->school }}</td>
                                        <td>{{ $subsciber->created_at }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                              <tr>
                                  <th>{{ trans('admin.name') }}</th>
                                  <th>{{ trans('admin.email') }}</th>
                                  <th>{{ trans('admin.mobile') }}</th>
                                  <th>{{ trans('admin.address') }}</th>
                                  <th>{{ trans('admin.domain') }}</th>
                                  <th>{{ trans('admin.school') }}</th>
                                  <th>{{ trans('admin.date') }}</th>
                              </tr>
                            </tfoot>
                          </table>
                      </div>
                    <!-- End Pending subscriptions -->


                    </div>
                  </div>
                </div>
              </div>
            </div>
              <!-- Sortable -->

            </div>
          </section>
          <!-- // Accordion section end -->
      </div>
@endsection
