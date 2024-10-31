<div>

<div class="store-header" id="cart-header">

</div>
  <nav aria-label="breadcrumb" class="breadcrumb-1 p-3">
    <div class="container">
      <ol class="breadcrumb mb-0 breadcrumb-1">
        <li class="breadcrumb-item"><a href="#">{{ trans('frontend.Home') }}</a></li>
        <li class="breadcrumb-item"><a href="/store">{{ trans('frontend.store.title') }}</a></li>
        <li class="breadcrumb-item"><a href="/store/@if($product->category->slug != '' || $product->category->slug != null)category/{{ $product->category->slug ?? '' }}@endif">{{ $product->category->name ?? '' }}</a></li>
        <li class="breadcrumb-item breadcrumb-item-active" style="color: #fff !important;">{{ $product->product_name ?? $product->name }}</li>
      </ol>
    </div>
  </nav>
  <div class="container">
    <div class="row mt-5">
      <div class="col-md-8">
        <?php $images = $product->images[0] ?? ''; ?>
        <img src="{{ $main_img ?? Storage::url($images)}}" alt="" width="100%">
        <div id="productimagemobilecontainer" class="row mt-5 pb-5">
          <div class="col-md-8 panel">
            <div class="row d-flex">
              @if($product->images)
              @foreach($product->images as $k => $image)
              <div class="col-md-3" wire:click="change_main_img('{{ $image }}')">
                @if($k == 0)
                <div class="overlay"></div>
                @endif
                  <img src="{{Storage::url($image)}}" alt="" width="100%" height="inherit">
              </div>
              @endforeach
              @endif
            </div>
          </div>
          <div class="col-md-4">

          </div>
        </div>

      </div>
      <div class="col-md-4">
        <div class="col-md-12">
          <h3 class="product-product-name">{{ $product->product_name ?? $product->name }}</h3>
        </div>
        <div class="col-md-12 price mt-3">
          <h5 class="product-product-price">{{ $product->currencies->code ?? '' }} {{ $product->price }} <span> +{{ trans('frontend.store.tax') }}</span></h5>
        </div>
        <div class="col-md-12 mt-3">
          <span class="title-text-p">{{ $product->description }}</span>
        </div>
        @if(!empty($product->type))
        <div class="col-md-12 mt-4">
          <button class="btn p-0" wire:click="add2wishlist">
            <i class="fa {{ $in_my_wishlist ? 'fa-heart': 'fa-heart-o' }} text-warning"></i> {{ trans('frontend.store.add2wishlist') }}
          </button>

        </div>
        <div class="col-md-12 mt-4">
          <?php
            $lang = app()->getLocale();
            $products = \App\Models\Products::where([['product_name', $product->product_name], ['locale', $lang]])->get();
          ?>
          @if($products[0]->color != 'n/a')
          <p class="title-text-p">Color</p>
          @endif
          @foreach($products as $k => $v)
          @if($v->color != 'n/a')
          <?php
            $css = null;
            switch($v->color)
            {
                case 'white':
                  $css = 'btn-light';
                  break;
                case 'black':
                  $css = 'btn-dark';
                  break;
                case 'red':
                  $css = 'btn-danger';
                  break;
                case 'blue':
                  $css = 'btn-blue';
                  break;
                case 'yellow':
                  $css = 'btn-warning';
                  break;
            }
          ?>
          <button type="button" class="btn {{$css}} btn-circle-preset ml-2" @if(!$css) style="background-color:{{ $v->color }}" @endif wire:click="select_product('{{$v->id}}')"></button>
          @endif
          @endforeach

        </div>
        @endif
        <form wire:submit.prevent="addToCart">
        <div class="col-md-12 mt-5 p-0">
          <div class="col-md-10 form-group">
            <label class="fontwlight">{{ trans('frontend.store.ships_to') }}</label>
            <select class="form-control" id="sel1" wire:change="changeEvent($event.target.value)" required>
              <option></option>
              @foreach($area as $k => $v)
              <option "{{ $v->country }}">{{ $v->country }}</option>
              @endforeach
            </select>
          </div>
        </div>

        @if($show_add2cart)
        <div class="col-md-12 mt-4">
            <div class="row">
                <div class="col-md-4 form-group">
                <label class="fontwlight">{{ trans('frontend.store.quantity') }}</label>
                <input type="number" min="1" class="form-control" wire:model="quantity" value="1" />
                </div>
                <div class="col-md-8">
                <label class="text-white">.</label>
                {{-- data-toggle="modal" data-target="#success_tic" --}}
                @if($product->quantity > 0)
                <button type="submit" id="btnSubmit" class="mybtn btn btn-warning" data-toggle="modal" data-target="#add2cart"> {{ trans('frontend.store.add2cart') }}</button>
                @else
                <a class="mybtn btn btn-secondary mt-4">{{ trans('frontend.store.out_of_stock') }}</a>
                @endif
                </div>

            </div>

        </div>
        @endif
        @if($show_vendor)
        <div class="col-md-12 pt-5">
            <h3>{{ $vendor['name'] }}</h3>
            <div class="row pt-2">

                <div class="col-md-4"><span class="title-text-p">E-mail</span></div>
                <div class="col-md-8"><span class="title-text-p">{{ $vendor['email'] }}</span></div>
                <div class="col-md-4"><span class="title-text-p">{{ trans('frontend.store.phone') }}</span></div>
                <div class="col-md-8"><span class="title-text-p">{{ $vendor['phone'] }}</span></div>
                <div class="col-md-4"><span class="title-text-p">{{ trans('frontend.store.address') }}</span></div>
                <div class="col-md-8"><span class="title-text-p">{{ $vendor['address'] }}</span></div>

            </div>

        </div>
        @endif

        </form>

      </div>
    </div>

    <div id="productimagecontainer" class="row mt-5 pb-5">
      <div class="col-md-8 panel">
        <div class="row d-flex">
          @if($product->images)
          @foreach($product->images as $k => $image)
          <div class="col-md-3" wire:click="change_main_img('{{ $image }}', '{{ $k }}')">
            @if($overlay == $k)
            <div class="overlay"></div>
            @endif
              <img class="product_img" src="{{Storage::url($image)}}" alt="" width="100%" height="inherit">
          </div>
          @endforeach
          @endif
        </div>
      </div>
      <div class="col-md-4">

      </div>
    </div>

    @if(!empty($packages))
    <div class="row mt-5" id="packageContainer">
      <div class="col-md-12 mb-3">
        <h4 class="packagedeal">{{ trans('frontend.store.package_of_product') }}</h4>
      </div>
      @foreach($packages as $k => $v)
      <?php $package = \App\Models\Products::find($v);
      if($package):
      ?>
      <div class="col-md-3">
        <button class="btn">
        <?php $images = $package->images[0] ?? ''; ?>
          <a href="/store/products/{{$package->product_code}}" target="_blank"><img src="{{Storage::url($images)}}" alt="" width="100%" height="inherit"></a>
        </button>
      </div>
      <?php endif;?>
      @endforeach
    </div>
    @endif

    {!! $product->content !!}
    </div>
    {!! $product->specifications !!}

    @if(!empty($product->accessories[0]))
    <div class="container" id="section7">
          <div class="row mt-5">
            <div class="col-md-12 mb-3">
              <h1>{{ trans('frontend.store.accessories') }}</h1>
            </div>
            @foreach($product->accessories as $k => $v)
            <?php $accessory = \App\Models\Products::find($v); ?>
            <div class="col-md-3">
              <button class="btn">
                <?php $image = $accessory->images[0] ?? ''; ?>
                <a href="/store/products/{{$accessory->product_code}}"><img src="{{Storage::url($image)}}" alt="" width="100%" height="inherit"></a>
              </button>
            </div>
            @endforeach
          </div>
    </div>
    @endif

    <div wire:ignore.self class="modal fade" id="add2cart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ trans('frontend.store.add2cart') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
            <div class="modal-body">
                    <p>{{ trans('frontend.store.add2cart_content') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{ trans('frontend.store.close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
