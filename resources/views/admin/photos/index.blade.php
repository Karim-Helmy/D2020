@extends('admin.layouts.app')


@section('content')
    <div class="content-header row">
      <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
        <h3 class="content-header-title mb-0 d-inline-block">{{ $title }}</h3>
        <div class="row breadcrumbs-top d-inline-block">
          <div class="breadcrumb-wrapper col-12">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ aurl("/photos/create") }}">{{ trans('admin.add photo') }}</a>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="content-body">
      <!-- HTML5 export buttons table -->
      <!-- Column selectors table -->
      <section id="image-grid" class="card">
                <div class="card-header">
                  <h4 class="card-title">{{ trans('admin.Photos') }}</h4>
                  <form id="search" action="{{ aurl('/photos') }}" method="get" >
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
                    <div class="card-text">
                    </div>
                  </div>
                  <div class="card-body  my-gallery" itemscope="" itemtype="http://schema.org/ImageGallery" data-pswp-uid="1">


                    @foreach ($index->chunk(4) as $chunk)
                    <div class="card-deck-wrapper">
                      <div class="card-deck mt-1">
                          @foreach ($chunk as $photo)
                              <figure class="card card-img-top border-grey border-lighten-2" itemprop="associatedMedia" itemscope="" itemtype="http://schema.org/ImageObject">
                                  <a href="{{ asset('uploads/'.$photo->image) }}" itemprop="contentUrl"  data-size="480x360">
                                      <img class="gallery-thumbnail card-img-top" src="{{ asset('uploads/'.$photo->image) }}" itemprop="thumbnail" alt="Image description" style="height:300px !important;" >
                                  </a>
                                  <div class="card-body px-0">
                                      <h4 class="card-title">{{ $photo->title }} </h4>
                                  </div>
                                   <figcaption itemprop="caption description">
                                       <!-- Edit Button -->
                                       <a href="{{ aurl('/photos/edit/'.$photo->id) }}" class="btn btn-secondary btn-min-width mr-1 mb-1"><i
                                       class="ft-edit"></i> {{ trans('admin.edit') }}</a>
                                       <!-- Delete Button -->
                                       <form id="form-id{{ $photo->id  }}" action="{{ route('photos.destroy', [$photo->id]) }}" style="display:inline;" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                      </form>
                                      <a href="#" onclick="document.getElementById('form-id{{ $photo->id }}').submit();" class="btn btn-danger btn-min-width btn-glow mr-1 mb-1" ><i
                                              class="ft-delete"></i> {{ trans('admin.delete') }}</a>
                                    <!-- Category Name -->
                                     <p class="btn btn-success btn-min-width btn-glow mr-1 mb-1" style="cursor:default;">{{ $photo->category->name ?? "" }}</p>
                                     <!-- Stage Name -->
                                     <p class="btn btn-success btn-min-width btn-glow mr-1 mb-1" style="cursor:default;">{{ $photo->stage->name ?? "" }}</p>
                                   </figcaption>
                              </figure>
                              {{-- <a href="{{ aurl('/photos/edit/'.$photo->id) }}" class="btn btn-secondary btn-min-width mr-1 mb-1"><i
                                  class="ft-edit"></i> {{ trans('admin.edit') }}</a> --}}
                          @endforeach
                      </div>
                    </div>
                    @endforeach


                  </div>
                  <!-- Root element of PhotoSwipe. Must have class pswp. -->
                  <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
                    <!-- Background of PhotoSwipe.
                   It's a separate element as animating opacity is faster than rgba(). -->
                    <div class="pswp__bg"></div>
                    <!-- Slides wrapper with overflow:hidden. -->
                    <div class="pswp__scroll-wrap">
                      <!-- Container that holds slides.
                      PhotoSwipe keeps only 3 of them in the DOM to save memory.
                      Don't modify these 3 pswp__item elements, data is added later on. -->
                      <div class="pswp__container" style="transform: translate3d(0px, 0px, 0px);">
                        <div class="pswp__item" style="display: block; transform: translate3d(-2131px, 0px, 0px);"><div class="pswp__zoom-wrap" style="transform: translate3d(712px, 263px, 0px) scale(0.451694);"><img class="pswp__img" src="" style="opacity: 1; width: 1063px; height: 797px;"></div></div>
                        <div class="pswp__item" style="transform: translate3d(0px, 0px, 0px);"><div class="pswp__zoom-wrap" style="transform: translate3d(1228.84px, 139.047px, 0px) scale(0.340991);"><img class="pswp__img pswp__img--placeholder" src="" style="width: 1063px; height: 797px; display: none;"><img class="pswp__img" src="" style="display: block; width: 1063px; height: 797px;"></div></div>
                        <div class="pswp__item" style="display: block; transform: translate3d(2131px, 0px, 0px);"><div class="pswp__zoom-wrap" style="transform: translate3d(712px, 263px, 0px) scale(0.451694);"><img class="pswp__img" src="" style="opacity: 1; width: 1063px; height: 797px;"></div></div>
                      </div>
                      <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
                      <div class="pswp__ui pswp__ui--fit pswp__ui--hidden">
                        <div class="pswp__top-bar">
                          <!--  Controls are self-explanatory. Order can be changed. -->
                          <div class="pswp__counter">1 / 4</div>
                          <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                          <button class="pswp__button pswp__button--share" title="Share"></button>
                          <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                          <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                          <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                          <!-- element will get class pswp__preloader-active when preloader is running -->
                          <div class="pswp__preloader">
                            <div class="pswp__preloader__icn">
                              <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                          <div class="pswp__share-tooltip"></div>
                        </div>
                        <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                        </button>
                        <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                        </button>
                        <div class="pswp__caption">
                          <div class="pswp__caption__center">
                            <h4 class="card-title">Card title 1</h4>
                            <p class="card-text">This is a longer card with supporting text below.</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ PhotoSwipe -->
                <div class="clearfix"></div>
						<div class="pagination" style="margin:10px auto">
							{{ $index->appends(request()->except('page'))->render() }}
						</div>
              </section>
      <!--/ Column selectors table -->
</div>
@endsection
