@extends('admin.layouts.app')


@section('content')
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">{{ $title }}</h3>
        <div class="row breadcrumbs-top d-inline-block">
          <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ aurl("/attachments/create") }}">{{ trans('admin.add attachments') }}</a>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="content-body">
      <!-- HTML5 export buttons table -->
      <!-- Column selectors table -->
      <section id="attachment-grid" class="card">
          <div class="card-header">
            <h4 class="card-title">{{ trans('admin.all attachments') }}</h4>
            <form id="search" action="{{ aurl('/attachments') }}" method="get" >
                <br />
                <input type="text" placeholder="{{ trans('admin.title') }}" id="keyword" value="{{ request()->keyword }}" class="form-control" style="width:25%; display:inline;" name="keyword" />
                <select name="category" class="form-control"  style="width:25%; display:inline; margin:20px;">
                    <option value="">{{ trans('admin.category') }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ (request()->category == $category->id) ? "selected" : "" }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                <select name="stage" class="form-control"  style="width:25%; display:inline; margin:20px;">
                    <option value="">{{ trans('admin.stage') }}</option>
                    @foreach ($stages as $stage)
                        <option value="{{ $stage->id }}" {{ (request()->stage == $stage->id) ? "selected" : "" }}>{{ $stage->name }}</option>
                    @endforeach
                </select>
                <br />
                <button class="btn btn-success btn-min-width mr-1 mb-1" type="submit" style="display:inline; margin:20px auto;"></i>{{ trans('admin.search') }}</button>
           </form>
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
          <div class="card-content">
            <div class="card-body">
              <div class="card-deck-wrapper">
                <div class="card-header">
                  <h4 class="card-title">{{ trans('admin.attachments') }}</h4>
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
                <div class="card-deck">
                @foreach ($index as $attachment)
                    <div class="border-grey border-lighten-2 col-md-3">
                        <div class="card-img-top embed-responsive-item">
                            <a href="{{ asset('uploads/'.$attachment->attachments) }}"><div class="fonticon-wrap"><i style="font-size: 15.4rem;" class="la la-file"></i></div></a>
                        </div>
                        <div class="card-body px-0">
                            <h4 class="card-title">{{ $attachment->title }}</h4>
                            <a href="{{ aurl('/attachments/edit/'.$attachment->id) }}" class="btn btn-secondary btn-min-width mr-1 mb-1"><i
                            class="ft-edit"></i> {{ trans('admin.edit') }}</a>
                            <!-- Delete Button -->
                            <form id="form-id{{ $attachment->id  }}" action="{{ route('attachments.destroy', [$attachment->id]) }}" style="display:inline;" method="post">
                                         @csrf
                                         @method('DELETE')
                           </form>
                           <a href="#" onclick="document.getElementById('form-id{{ $attachment->id }}').submit();" class="btn btn-danger btn-min-width btn-glow mr-1 mb-1" ><i
                                   class="ft-delete"></i> {{ trans('admin.delete') }}</a>
                            <!-- Category Name -->
                           <p class="btn btn-success btn-min-width btn-glow mr-1 mb-1" style="cursor:default;">{{ $attachment->category->name }}</p>
                           <!-- Stage Name -->
                           <p class="btn btn-success btn-min-width btn-glow mr-1 mb-1" style="cursor:default;">{{ $attachment->stage->name }}</p>
                            </div>
                        </div>
                @endforeach

                </div>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
                  <div class="pagination" style="margin:10px auto">
                      {{ $index->appends(request()->except('page'))->render() }}
                  </div>
        </section>
      <!--/ Column selectors table -->
</div>
@endsection
