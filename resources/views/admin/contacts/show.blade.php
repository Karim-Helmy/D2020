@extends('admin.layouts.app')

@section('content')
<style>
body.vertical-layout.vertical-menu-modern.menu-expanded .content, body.vertical-layout.vertical-menu-modern.menu-expanded .footer{
    margin:0 !important;
}
</style>
    <div class="app-content content">
      <div class="content-right">
        <div class="content-wrapper">
          <div class="content-header row">
          </div>
          <div class="content-body">
            <div class="card email-app-details d-none d-lg-block">
              <div class="card-content">
                <div class="media-list">
                  <div id="headingCollapse1" class="card-header p-0">
                    <a data-toggle="collapse" href="#collapse1" aria-expanded="true" aria-controls="collapse1"
                    class="collapsed email-app-sender media border-0 bg-blue-grey bg-lighten-5">
                      <div class="media-left pr-1">
                        <span class="avatar avatar-md">
                          <img class="media-object rounded-circle" src="{{ asset('backend/app-assets/images/portrait/small/avatar-s-1.png')}}"
                          alt="Generic placeholder image">
                        </span>
                      </div>
                      <div class="media-body w-100">
                        <h6 class="list-group-item-heading">{{ $show->name }}</h6>
                        <p class="list-group-item-text">{{ $show->subject }}
                          <span class="float-right text muted">{{ $show->created_at }}</span>
                        </p>
                      </div>
                    </a>
                  </div>
                  <div id="collapse1" role="tabpanel" aria-labelledby="headingCollapse1" class="card-collapse collapse"
                  aria-expanded="true">
                    <div class="card-content">
                      <div class="card-body">
                        <p>{{ $show->email }}</p>
                      </div>
                    </div>
                  </div>
                  <div id="headingCollapse2" class="card-header p-0">
                    <a data-toggle="collapse" href="#collapse2" aria-expanded="false" aria-controls="collapse2"
                    class="email-app-sender media border-0">
                      <div class="media-left pr-1">
                        <span class="avatar avatar-md">
                          <img class="media-object rounded-circle" src="{{asset('backend/app-assets/images/portrait/small/avatar-s-7.png')}}"
                          alt="Generic placeholder image">
                        </span>
                      </div>
                      <div class="media-body w-100">
                        <h6 class="list-group-item-heading">{{ $show->subject }}</h6>
                        <p class="list-group-item-text">{{ $show->email }}
                        </p>
                      </div>
                    </a>
                  </div>
                  <div id="collapse2" role="tabpanel" aria-labelledby="headingCollapse2" class="card-collapse"
                  aria-expanded="false">
                    <div class="card-content">
                      <div class="email-app-text card-body">
                        <div class="email-app-message">
                          <p>{{ $show->message }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="email-app-text-action card-body">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
@endsection
