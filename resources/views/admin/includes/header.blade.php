<!-- fixed-top-->
<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-dark navbar-shadow">
  <div class="navbar-wrapper">
    <div class="navbar-header">
      <ul class="nav navbar-nav flex-row">
        <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
        <li class="nav-item mr-auto">
          <a class="navbar-brand" href="{{ aurl('/index') }}">
            <img class="brand-logo" alt="D2020 logo" src="{{ asset('backend/app-assets/images/logo/logo.png')}}" style="width: 80px;margin-top: 10px">
            <h3 class="brand-text">{{ trans('admin.Cpanel') }}</h3>
          </a>
        </li>
        <li class="nav-item d-md-none">
          <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="la la-ellipsis-v"></i></a>
        </li>
      </ul>
    </div>
    <div class="navbar-container content">
      <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="nav navbar-nav mr-auto float-left">
          <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>
        </ul>
        <ul class="nav navbar-nav float-right">
          <!-- Language -->
          <li class="dropdown dropdown-language nav-item"><a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              @if (session()->get('locale') == "ar")
                  <i class="flag-icon flag-icon-sa"></i>
              @else
                  <i class="flag-icon flag-icon-us"></i>
              @endif
              <span class="selected-language"></span></a>
              <div class="dropdown-menu" aria-labelledby="dropdown-flag"><a class="dropdown-item" href="{{ url("setlocale/en") }}"><i class="flag-icon flag-icon-us"></i> English</a>
                <a class="dropdown-item" href="{{ url("setlocale/ar") }}"><i class="flag-icon flag-icon-sa"></i> Arabic</a>
              </div>
          </li>
          <!-- messages -->
          <li class="dropdown dropdown-notification nav-item">
           <a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon ft-mail">             </i></a>
           <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
             <li class="dropdown-menu-header">
               <h6 class="dropdown-header m-0">
                 <span class="grey darken-2">{{ trans('admin.visit_messages') }}</span>
               </h6>
               <span class="notification-tag badge badge-default badge-warning float-right m-0"> {{CountMessageVisitors()}} {{ trans('admin.new') }}</span>
             </li>
             <li class="scrollable-container media-list w-100">
                 @foreach (GetMessageVisitors() as $con)
               <a href="{{ aurl("/contacts/show/".$con->id) }}">
                 <div class="media">
                   <div class="media-left">
                     <span class="avatar avatar-sm avatar-online rounded-circle">
                       <img src="{{ asset('backend/app-assets/images/portrait/small/avatar-s-19.png')}}" alt="avatar"><i></i></span>
                   </div>
                   <div class="media-body">
                     <h6 class="media-heading">{{ $con->subject }}</h6>
                     <p class="notification-text font-small-3 text-muted">{{ $con->name }}</p>
                     <small>
                       <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">{{ $con->created_at->format('d M Y, h:i') }}</time>
                     </small>
                   </div>
                 </div>
               </a>
               @endforeach
             </li>
             <li class="dropdown-menu-footer"><a class="dropdown-item text-muted text-center" href="{{ aurl("/contacts") }}">{{ trans('admin.show all messages') }}</a></li>
           </ul>
         </li>

         <!-- messages supervisors -->
         <li class="dropdown dropdown-notification nav-item">
          <a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon ft-mail">             </i></a>
          <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
            <li class="dropdown-menu-header">
              <h6 class="dropdown-header m-0">
                <span class="grey darken-2">{{ trans('admin.messages') }}</span>
              </h6>
              <span class="notification-tag badge badge-default badge-warning float-right m-0"> {{CountMessageAdmin()}} {{ trans('admin.new') }}</span>
            </li>
            <li class="scrollable-container media-list w-100">
                @foreach (GetMessageAdmin() as $con)
              <a href="{{ aurl("/messages/show/".$con['sender_id']) }}">
                <div class="media">
                  <div class="media-left">
                    <span class="avatar avatar-sm avatar-online rounded-circle">
                      <img src="{{ asset('backend/app-assets/images/portrait/small/avatar-s-19.png')}}" alt="avatar"><i></i></span>
                  </div>
                  <div class="media-body">
                    <h6 class="media-heading">{{ $con->subject }}</h6>
                    <p class="notification-text font-small-3 text-muted">{{ $con->name }}</p>
                    <small>
                      <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">{{ $con->created_at->format('d M Y, h:i') }}</time>
                    </small>
                  </div>
                </div>
              </a>
              @endforeach
            </li>
            <li class="dropdown-menu-footer"><a class="dropdown-item text-muted text-center" href="{{ aurl("/contacts") }}">{{ trans('admin.show all messages') }}</a></li>
          </ul>
        </li>
          <!-- profile -->
          <li class="dropdown dropdown-user nav-item">
            <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
              <span class="mr-1">{{ trans('admin.hello') }}
                <span class="user-name text-bold-700">{{auth()->guard('webAdmin')->user()->username}}</span>
              </span>
              <span class="avatar avatar-online">
                <img src="{{ asset('backend/app-assets/images/portrait/small/avatar-s-19.png')}}" alt="avatar"><i></i></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ aurl('/admins/user') }}"><i class="la la-group"></i> {{ trans('admin.edit admins') }}</a>
              <div class="dropdown-divider"></div><a class="dropdown-item" href="{{ aurl('/logout') }}"><i class="ft-power"></i> {{ trans('login.logout') }}</a>
            </div>
          </li>

        </ul>
      </div>
    </div>
  </div>
</nav>
<!-- ////////////////////////////////////////////////////////////////////////////-->
