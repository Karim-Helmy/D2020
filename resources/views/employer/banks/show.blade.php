@extends('admin.layouts.app')
@section('content')
    <div class="col-md-12 order-1 order-md-2">
  <div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="card">
                <div class="card-content">
                  <div class="card-body">
                        <h3>{!! $bank->title !!}</h3>
                        @foreach (explode('|', $bank->answers) as $answer)
                            <!--Single answer-->
                            <p>
                                <label class="checkbox_wrapper">
                                    <input type="radio" class="custom-control-input" name="q-1" {{ $answer == $bank->best_answer ? "checked" : "" }}>
                                    <span class="checkLabel"></span>
                                </label>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" disabled name="customRadio" id="customRadio{{$loop->index}}" {{ $answer == $bank->best_answer ? "checked" : "" }}>
                                    <label class="custom-control-label" for="customRadio{{$loop->index}}">{{ $answer }}</label>
                                </div>

                            </p>
                        @endforeach
                  </div>
                </div>
              </div>
    </div>
  </div>
</div>
@endsection
