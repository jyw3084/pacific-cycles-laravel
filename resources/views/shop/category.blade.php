@extends('layouts.store-layout')
<div class="store-header" @if(isset($banner)) style="
    background: url({{ Storage::url($banner->image) }});
    background-position: center;" @endif>
    <div class="container">
        <div class="store-header-container">
            <h1>{{ trans('frontend.store.title') }}</h1>
        </div>
    </div>
</div>
@section('content')
    <div>
        <nav aria-label="breadcrumb" class="store-breadcrumb">
            <div class="container">
                <ol class="breadcrumb breadcrumb-2">
                    <li class="breadcrumb-item breadcrumb-2-items"><a href="/">{{ trans('frontend.Home') }}</a></li>
                    <li class="breadcrumb-item breadcrumb-2-items"><a href="/store">{{ trans('frontend.store.title') }}</a></li>
                    <li class="breadcrumb-item breadcrumb-2-items breadcrumb-2-item-active" style="color:#000000 !important;">{{ $title }}</li>
                </ol>
            </div>
        </nav>
        <div class="container min-vh-100 category-view">

    {{-- ALL products --}}
    <div class="row mt-5">
        <div class="col-12">
            <div id="package-deals-div">
                <h3 class="dealer-title mt-5 mb-5">{{ $title }}<span> |</span></h3>
            </div>
        </div>
        <div class="col-12">
            <button class="btn filters px-4" data-toggle="modal" data-target="#filter-div"><i class="fa fa-bars"
                                                                                              aria-hidden="true"></i> <span>{{ trans('frontend.store.filter') }}</span></button>
        </div>

        @if($products->count() > 0)
            @foreach($products as $product)
                <div class="col-md-3 p-3 mt-3">
                    <a href="/store/products/{{ $product->product_code }}">
                        <img src="{{isset($product->images[0]) ? \Illuminate\Support\Facades\Storage::url($product->images[0]) : ''}}" width="100%"/>
                    </a>
                    <h6 class="text-primary mt-4">{{ $product->category->name ?? '' }} - {{ $product->bike_model->bike_model ?? ''}}</h6>
                    <h5 class="product-name" style="width: 100%;">{{ $product->product_name }} </h5>
                    <h5 class="product-price"><span class="float-left">$ {{ $product->price }}</span></h5>
                    <a href="/store/products/{{ $product->product_code }}"
                       class="pl-0 text-primary show-now float-right">{{ trans('frontend.store.shop_now') }} ></a>
                    <div style="clear: both;"></div>
                    <h6 class="product-desc"> {{$product->description}}</h6>
                    @if(isset($_GET['priority']) && $_GET['priority']==1)<p style="color:red">Priority: {{ $product->pry }}</p>@endif
                </div>
            @endforeach
            <div class="col-md-12 py-5">
                <nav aria-label="pagination">
                    <div class="pagination justify-content-center">

                    {{ $products->links() }}

                    </div>
                </nav>
            </div>
        @else
            <div class="col-md-12">
                <p class="mt-3 text-center text-muted">{{ trans('frontend.store.no_items') }}</p>
            </div>
        @endif

    </div>
        </div>
@endsection

@include('include.shop-filter')
