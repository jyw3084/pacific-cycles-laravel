@if($banner)
    <div class="store-header" style="
        background: url({{ Storage::url($banner->image) }});
        background-position: center;">
        <div class="container">
            <div class="store-header-container">
                <h1>{{ trans('frontend.store.title') }}</h1>
            </div>
        </div>
    </div>
@endif
<div>
    <nav aria-label="breadcrumb" class="store-breadcrumb">
        <div class="container">
            <ol class="breadcrumb breadcrumb-2">
                <li class="breadcrumb-item breadcrumb-2-items"><a href="#">{{ trans('frontend.Home') }}</a></li>
                <li class="breadcrumb-item breadcrumb-2-items breadcrumb-2-item-active"><a href="/store" style="color:#000000 !important;">{{ trans('frontend.store.title') }}</a></li>
            </ol>
        </div>
    </nav>
    <div class="container min-vh-100">
        <!-- models -->
        @if(count($displayModels) > 0)
          @foreach($displayModels as $modelArr)
                <div class="row">
                    <div class="col-12">
                        <div id="package-deals-div">
                            <h3 class="dealer-title mt-5 mb-5">{{ $modelArr['model'] }}<span> |</span></h3>
                        </div>
                    </div>

                    @if(count($modelArr['products']) > 0)
                        @foreach($modelArr['products'] as $product)
                            <div class="col-md-4 p-3 mt-3">
                                <a href="/store/products/{{$product->product_code}}">
                                    <img src="{{isset($product->images[0]) ? Storage::url($product->images[0]) : ''}}" width="100%" />
                                </a>
                                <h6 class="text-primary mt-4"></h6>
                                <h5 class="product-name">{{$product->product_name}} </h5>
                                <h5 class="product-price"><span class="float-right">$ {{$product->price}}</span></h5>
                                <div style="clear:both;"></div>
                                <h6 class="product-desc"> {{$product->description}}</h6>
                                <a href="/store/products/{{$product->product_code}}" class="pl-0 btn text-primary show-now">{{ trans('frontend.store.shop_now') }} ></a>
                            </div>
                        @endforeach
                    @endif
                    <div class="col-md-12 py-5">
                        <nav aria-label="pagination">
                            @if($modelArr['slug']!=null)
                            <a href="/store/category/{{ $modelArr['slug'] }}" class="btn btn-primary float-right">{{ trans('frontend.store.see_more') }}</a>
                            @endif
                        </nav>
                    </div>
                </div>
            @endforeach
          @endif

         <!-- categories -->
                @if(count($displayCats) > 0)
                    @foreach($displayCats as $catArr)
                        <div class="row">
                            <div class="col-12">
                                <div id="package-deals-div">
                                    <h3 class="dealer-title mt-5 mb-5">{{ $catArr['category'] }}<span> |</span></h3>
                                </div>
                            </div>


                            @if(count($catArr['products']) > 0)
                                @foreach($catArr['products'] as $product)
                                    <div class="col-md-4 p-3 mt-3">
                                        <a href="/store/products/{{$product->product_code}}">
                                            <img src="{{isset($product->images[0]) ? Storage::url($product->images[0]) : ''}}" width="100%" />
                                        </a>
                                        <h6 class="text-primary mt-4"></h6>
                                        <h5 class="product-name">{{$product->product_name}} </h5>
                                        <h5 class="product-price"><span class="float-right">$ {{$product->price}}</span></h5>
                                        <div style="clear:both;"></div>
                                        <h6 class="product-desc"> {{$product->description}}</h6>
                                        <a href="/store/products/{{$product->product_code}}" class="pl-0 btn text-primary show-now">{{ trans('frontend.store.shop_now') }} ></a>
                                    </div>
                                @endforeach
                            @endif

                            <div class="col-md-12 py-5">
                                <nav aria-label="pagination">
                                    @if($catArr['slug']!=null)
                                        <a href="/store/category/{{ $catArr['slug'] }}" class="btn btn-primary float-right">{{ trans('frontend.store.see_more') }}</a>
                                    @endif
                                </nav>
                            </div>
                        </div>
                    @endforeach
                   @endif

    </div>
</div>
