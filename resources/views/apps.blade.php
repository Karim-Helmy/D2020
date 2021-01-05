@extends('layouts.app')
@section('styles')
<style media="screen">
.padding {
    padding: 7rem !important;
    margin-left: 50px
}
.intro .buttons a.btn {
margin: 10px;
padding: 10px 30px
}

.btn {
position: relative;
display: inline-block;
outline: none;
color: #fff;
text-decoration: none;
text-transform: uppercase;
letter-spacing: 1px;
font-weight: 400;
text-shadow: 0 0 1px rgba(255, 255, 255, 0.3);
font-size: 14px;
font-weight: 700
}

.animated h2 {
text-align: center;
color: #fff
}

.animated p {
text-align: center;
color: #fff
}

.buttons {
text-align: center
}
</style>
@endsection
@section('breadcrumb')
    <div class="wrap-title-page">
              <div class="container">
                <div class="row">
                  <div class="col-xs-12">
                    <h1 class="ui-title-page">{{ trans('admin.apps') }}</h1>
                  </div>
                </div>
              </div>
              <!-- end container-->
            </div>
            <!-- end wrap-title-page -->

            <div class="section-breadcrumb">
              <div class="container">
                <div class="row">
                  <div class="col-xs-12">
                    <div class="wrap-breadcrumb clearfix">
                      <ol class="breadcrumb">
                        <li>
                          <a href="{{ url('/index') }}"
                            ><i class="icon stroke icon-House"></i
                          ></a>
                        </li>
                        <li class="active">{{ trans('admin.apps') }}</li>
                      </ol>
                    </div>
                  </div>
                </div>
                <!-- end row -->
              </div>
              <!-- end container -->
            </div>
@endsection
@section('content')
         <!-- end about -->
         <section class="about rtd">
             <div class="container">
                 <div class="row">
                     <div class="col-md-12 text-center rounded-circle" style="background:#011627; border-radius:25px;">
                         <h2 style="color:#FFF; font-size:22px;" class="about__title wow fadeInUp animated" data-wow-duration="2s" data-wow-delay="0.2s" style="visibility: visible; animation-duration: 2s; animation-delay: 0.2s; animation-name: fadeInUp;" alt="parent">
                             <br /><br />
                             {{ trans('admin.app_parents') }}
                         </h2>
                         <h3 class="about__title-inner">
                             <img src="{{ asset('homepage/parent.png') }}" style="height:200px;" class="wow fadeInUp animated" data-wow-duration="2s" data-wow-delay="0.2s" style="visibility: visible; animation-duration: 2s; animation-delay: 0.2s; animation-name: fadeInUp;" alt="parent">
                             <div class="buttons">
                                 <a target="_blank" href="https://play.google.com/store/apps/details?id=com.besteam.parentapp" class="btn btn-success btn-lg" data-wow-duration="2s" data-wow-delay="0.2s" style="visibility: visible; animation-duration: 2s; animation-delay: 0.2s; animation-name: fadeInLeft;"><i class="fa fa-android fa-2x"></i> Download<br> <small style="color:#FFF">Android version 1.1</small></a>
                                 <a target="_blank" href="https://apps.apple.com/us/app/id1492239258" class="btn btn-info btn-lg wow fadeInRight animated" data-wow-duration="3s" data-wow-delay="0.3s" style="visibility: visible; animation-duration: 3s; animation-delay: 0.3s; animation-name: fadeInRight;"><i class="fa fa-apple fa-2x"></i> Download <br> <small style="color:#FFF">iOs version 1.1</small></a>
                             </div>
                         </h3>
                     </div>
                     <!-- end col -->
                     <!-- end col -->
                 </div>
                 <!-- end row -->
             </div>
             <!-- end container -->
         </section>

@endsection
