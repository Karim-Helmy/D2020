@extends('admin.layouts.app')
@section('styles')
    @if (session()->get('locale') == "ar")
        <link rel="stylesheet" type="text/css" href="{{ asset('backend/app-assets/css-rtl/pages/chat-application.css')}}">
    @else
        <link rel="stylesheet" type="text/css" href="{{ asset('backend/app-assets/css/pages/chat-application.css')}}">
    @endif
@endsection
@section('content')
    <div class="chat-application">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $username ?? "wwwwwww" }}</h4>
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
                </div>
            </div>
        </div>
        <section class="chat-app-window">
            <div class="badge badge-default mb-1"></div>
            <div class="chats">
                <div class="chats">

                    @foreach ($messages as $message)
                        <div {{ $message['type'] != "send" ? 'class=chat-left' : "class=chat" }}>
                            <div class="chat-avatar">
                                <a class="avatar" data-toggle="tooltip" href="#"  {{ $message['type'] != "send" ? 'data-placement=right' : "data-placement=left" }} title=""
                                data-original-title="">
                                @if($message['type'] == "send")
                                    <img src="{{ asset('backend/app-assets/images/icons/download.jpg')}}" alt="admin"/>
                                @else
                                    <img src="{{ asset('backend/app-assets/images/icons/user.png')}}" alt="user"/>
                                @endif
                            </a>
                        </div>
                        <div class="chat-body">
                            <div class="chat-content">
                                <p style="font-size:20px;">{{ $message['subject'] }}</p>
                                <hr />
                                <p>{{ $message['message'] }}</p>
                                <br />
                                <p class="time" style="text-align:inherit">{{ $message['date'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
</div>
<br /><br />
<section class="chat-app-form">
    <form class="chat-app-input d-flex" action="{{ aurl('/messages/send/'.$sender_id) }}" method="post">
        @csrf
        <fieldset class="form-group position-relative has-icon-left col-2 m-0">
            <input type="text" class="form-control" id="iconLeft4" name="subject" placeholder="{{ trans('admin.subject') }}" value="{{ old('subject') }}">
            <div class="form-control-position control-position-right">
                <i class="la la-envelope"></i>
            </div>
        </fieldset>
        <fieldset class="form-group position-relative has-icon-left col-9 m-0">
            <input type="text" class="form-control" id="iconLeft4" name="message" placeholder="{{ trans('admin.message') }}" value="{{ old('message') }}">
            <div class="form-control-position control-position-right">
                <i class="la la-envelope"></i>
            </div>
        </fieldset>
        <fieldset class="form-group position-relative has-icon-left col-1 m-0">
            <button type="submit" class="btn btn-info"><i class="la la-paper-plane-o d-lg-none"></i>
                <span class="d-none d-lg-block">{{ trans("admin.send") }}</span>
            </button>
        </fieldset>
    </form>
</section>
@endsection
@section('scripts')
    <script src="{{ asset('backend/app-assets/js/scripts/chat-application.js')}}" type="text/javascript"></script>
@endsection
