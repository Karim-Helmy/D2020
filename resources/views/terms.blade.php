@extends('layouts.app')
@section('breadcrumb')
    <div class="wrap-title-page">
              <div class="container">
                <div class="row">
                  <div class="col-xs-12">
                    <h1 class="ui-title-page">{{ trans('admin.Privacy policy') }}</h1>
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
                        <li class="active">{{ trans('admin.Privacy policy') }}</li>
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
     <section class="about rtd">
           <div class="container">
             <div class="row">
               <div class="col-md-12">
                 @if (session()->get('locale') == "ar")   {!! GetSetting('terms_ar') !!} @else {!! GetSetting('terms_en') !!} @endif
               </div>
             </div>
             <!-- end row -->
           </div>
           <!-- end container -->
         </section>
         <!-- end about -->
@endsection
