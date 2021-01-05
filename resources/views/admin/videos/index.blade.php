@extends('admin.layouts.app')


@section('content')
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">{{ $title }}</h3>
        <div class="row breadcrumbs-top d-inline-block">
          <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ aurl("/videos/create") }}">{{ trans('admin.add videos') }}</a>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="content-body">
      <!-- HTML5 export buttons table -->
      <!-- Column selectors table -->
      <section id="video-grid" class="card">
          <div class="card-header">
            <h4 class="card-title">{{ trans('admin.all videos') }}</h4>
            <form id="search" action="{{ aurl('/videos') }}" method="get" >
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
                  <h4 class="card-title">{{ trans('admin.videos') }}</h4>
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
                @foreach ($index as $video)
                    <div class="border-grey border-lighten-2 col-md-3">
                        <div class="card-img-top embed-responsive embed-responsive-item embed-responsive-16by9">
                            <iframe class="gallery-thumbnail" src="https://www.youtube.com/embed/{{ explode('v=', $video->link)[1] }}?rel=0&amp;controls=0&amp;showinfo=0"></iframe>
                        </div>
                        <div class="card-body px-0">
                            <h4 class="card-title">{{ $video->title }}</h4>
                            <a href="{{ aurl('/videos/edit/'.$video->id) }}" class="btn btn-secondary btn-min-width mr-1 mb-1"><i
                            class="ft-edit"></i> {{ trans('admin.edit') }}</a>
                            <!-- Delete Button -->
                            <form id="form-id{{ $video->id  }}" action="{{ route('videos.destroy', [$video->id]) }}" style="display:inline;" method="post">
                                         @csrf
                                         @method('DELETE')
                           </form>
                           <a href="#" onclick="document.getElementById('form-id{{ $video->id }}').submit();" class="btn btn-danger btn-min-width btn-glow mr-1 mb-1" ><i
                                   class="ft-delete"></i> {{ trans('admin.delete') }}</a>
                            <!-- Category Name -->
                           <p class="btn btn-success btn-min-width btn-glow mr-1 mb-1" style="cursor:default;">{{ $video->category->name }}</p>
                           <!-- Stage Name -->
                           <p class="btn btn-success btn-min-width btn-glow mr-1 mb-1" style="cursor:default;">{{ $video->stage->name }}</p>
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
@section('scripts')
    {{-- <script type="text/javascript">
    $(document).on('submit','#search',function(event){
    	event.preventDefault();
    	$.ajax({
    			url: $(this).attr("action"),
    			method: $(this).attr("method"),
    			data:{
    				keyword   : $('#keyword').val(),
    			},
    			success: function(response){
    				$('body').html(response);
    			},
    		})
    })
    </script> --}}
@endsection
