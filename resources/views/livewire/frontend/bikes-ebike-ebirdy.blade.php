<div class="bikes-header" style="
    background-image: url({{ Storage::url($banner->image) }});">
  <div class="overlay"></div>
  <div class="container">
    <div class="bikes-header-container">
      <h1>{{ trans('frontend.bikes.our_bikes') }}</h1>
    </div>
  </div>
</div>
<div>
  <nav aria-label="breadcrumb" class="breadcrumb-1 b-about p-3">
    <div class="container" style="text-align: center">
      <div class="bikes-desktop-view">
        <a class="text-light pc-link mr-5" href="/bikes/folding">FOLDING BIKES</a>
        <a class="text-light pc-link-active ml-5 mr-5" href="/bikes/ebike">E-BIKES</a>
        <a class="text-light pc-link ml-5" href="/bikes/supportive">SUPPORTIVE</a>
      </div>
      <div class="dropdown-mobile-view">
        <a class="text-light pc-link-active ml-5" href="#" id="mobileBikeTitle">E-BIKES <i class="fa fa-angle-down"></i></a>
        <div id="bikedropdown">
          <a class="text-light pc-link mr-5" href="/bikes/folding">FOLDING BIKES</a>
          <a class="text-light pc-link ml-5 mr-5" href="/bikes/supportive">SUPPORTIVE</a>
        </div>
      </div>
    </div>
  </nav>

  <nav aria-label="breadcrumb" class="breadcrumb-2">
    <div class="container">
      <ol class="breadcrumb breadcrumb-2">
        <li class="breadcrumb-item breadcrumb-2-items"><a href="/">{{ trans('frontend.Home') }}</a></li>
        <li class="breadcrumb-item breadcrumb-2-items"><a href="/bikes/folding">{{ trans('frontend.bikes.title') }}</a></li>
        <li class="breadcrumb-item breadcrumb-2-items"><a href="/bikes/ebike">{{ $category }}</a></li>
        <li class="breadcrumb-item breadcrumb-2-items breadcrumb-2-item-active">{{ $bike_model }}</li>
      </ol>
    </div>
  </nav>
  {!! $header !!}

  <div class="container" style="margin-bottom: 8rem;">
    <div id="products">
      <div class="row">
        @if($products)
            @foreach($products as $k => $v)
              <div class="col-md-6 p-3 d-flex flex-column justify-content-between">
                <?php $image = $v->images[0] ?? ''; ?>
                <a href="{{URL::to('/store/products/'.$v->product_code)}}"><img src="{{Storage::url($image)}}" class="img-fluid" alt="{{$v->product_name}}"></a>
                <div class="product_meta">
                  <h6 class="text-primary mt-4"> {{$category}} </h6>
                  <div class="d-flex justify-content-between mb-3">
                    <h5 class="pc-regular-text">{{$v->product_name}}</h5>
                    <h5 class="pc-regular-text">{{number_format($v->price,2)}}</h5>
                  </div>
                  <p>{{$v->description}}</p>
                  <a href="{{URL::to('/store/products/'.$v->product_code)}}" class="btn btn-link">{{ trans('frontend.home.learn_more') }} <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                </div>
              </div>
            @endforeach
        @endif
      </div>
       <nav aria-label="pagination">
        {{ $products->links() }}
      </nav>
    </div>
  {!! $footer !!}

  </div>
</div>
</div>
