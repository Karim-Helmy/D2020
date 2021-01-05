@extends('admin.layouts.app')
@section('content')
    <div class="col-md-12 order-1 order-md-2">
  <div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="card">
                <div class="card-content">
                  <div class="card-body">
                        <h3 style="color:#FF0000">{!! $exam->title !!}</h3>
                        <br />
                        @foreach ($exam->examBank as $key => $bank)
                            <h3>{!! $bank->bank->title !!}</h3>
                            @foreach (explode('|',$bank->bank->answers) as $key => $answer)
                            <!--Single answer-->
                            <p>
                                <label class="checkbox_wrapper">
                                    <input type="radio" class="custom-control-input" name="q-1">
                                    <span class="checkLabel"></span>
                                </label>
                                {{ $answer }}
                            </p>
                            @endforeach
                            <br /><br />
                        @endforeach
                  </div>
                </div>
              </div>
    </div>
  </div>
</div>
@endsection
