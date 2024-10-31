<div class="dealer-header" style="
    background: url({{ Storage::url($banner->image) }});
    background-position: center;">
  <div class="overlay"></div>
  <div class="container">
    <div class="dealer-header-container">
        <h1>Dealers</h1>
    </div>
  </div>
</div>
<div>
  <nav aria-label="breadcrumb" class="breadcrumb-1 p-3 b-about">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
          <div class="dealer-desktop-view">
            <a href="/dealer" class="text-light mr-5 pc-link-active">{{ trans('frontend.dealer.where') }}</a>
            <a href="/dealer/apply" class="text-light ml-5 pc-link">{{ trans('frontend.dealer.apply') }}</a>
          </div>
          <div class="dropdown-mobile-view">
            <a id="dealermobile" href="#" class="text-light pc-link-active">{{ trans('frontend.dealer.where') }} <i class="fa fa-angle-down"></i></a>
            
            <div id="dealerdropdown">
              <a href="/dealer/apply" class="text-light pc-link">{{ trans('frontend.dealer.apply') }}</a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </nav>

  <nav aria-label="breadcrumb" class="breadcrumb-2">
    <div class="container">
      <ol class="breadcrumb breadcrumb-2">
        <li class="breadcrumb-item breadcrumb-2-items"><a href="/">{{ trans('frontend.Home') }}</a></li>
        <li class="breadcrumb-item breadcrumb-2-items"><a href="/dealer">{{ trans('frontend.dealer.title') }}</a></li>
        <li class="breadcrumb-item breadcrumb-2-items breadcrumb-2-item-active">{{ trans('frontend.dealer.where') }}</li>
      </ol>
    </div>
  </nav>
  <div class="container min-vh-100 mb-10">
    <h2 class="headings text-nowrap mt-5">{{ trans('frontend.dealer.where') }} <span class="v-line"></span>
    </h2>
    <div class="row mt-3 where-to-buy-content">
      <div class="col-lg-4">
        <div class="form-group">
          <div class="input-group">
            <input type="text" name="location" class="mycustominput form-control" id="location" value="{{ __('frontend.dealer.global') }}">
            <button id="searchBtn" class="btn-light btn" type="submit"><i class="fa fa-search"></i></button>
            {{-- 
            <button class="btn-light btn" type="submit"><i class="fa fa-paper-plane-o"></i></button>--}}
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <select name="radius" class="mycustominput form-control" id="radius">
              <option value="20">5</option>
              <option value="19">10</option>
              <option value="18">20</option>
              <option value="17">30</option>
              <option value="16">40</option>
              <option value="15">50</option>
              <option value="14">75</option>
              <option value="13">100</option>
              <option value="12">125</option>
              <option value="11">150</option>
              <option value="10">200</option>
              <option value="9">250</option>
              <option value="8">300</option>
            <option class="custom-option" value="1" selected=>Radius:600 km</option>
            </select>
          </div>
        </div>
        {{-- 
        <div class="form-group">
          <div class="input-group">
            <input type="number" name="category" class="mycustominput form-control" id="category" wire:model="category"
              placeholder="Category :">
          </div>
        </div>--}}
        <hr>
        <div id="search_results">
          @foreach($dealers as $k => $dealer)
          @if($k == 0)
          <div class="item_result p-2 pr-3 {{strtolower($dealer['name'])}} {{ strtolower($dealer['country'])}} {{ strtolower($dealer['address'])}}" data-name="{{$dealer['name']}}" data-address="{{ $dealer['country']}} {{ $dealer['address']}}" data-phone="{{$dealer['phone']}}" data-long="{{$dealer['Long']}}" data-lat="{{$dealer['Lat']}}">
            @else
            <div class="mt-2 item_result p-2 pr-3 {{strtolower($dealer['name'])}} {{ strtolower($dealer['country'])}} {{ strtolower($dealer['address'])}}" data-name="{{$dealer['name']}}" data-phone="{{$dealer['phone']}}" data-address="{{ $dealer['country']}} {{ $dealer['address']}}" data-long="{{$dealer['Long']}}" data-lat="{{$dealer['Lat']}}">
              @endif
              <div class="m-1 col-md-12">
                {{ $dealer['name']}}
              </div>
              <div class="m-1 col-md-12">
                {{ $dealer['country']}} {{ $dealer['address']}}
              </div>
              <div class="m-1 col-md-12">
                <i class="fa fa-phone fa-1x" aria-hidden="true"></i><span class="text-nowrap text-primary"> {{$dealer['phone']}}</span>
              </div>
              @if($dealer['website'])
              <div class="m-1 col-md-12">
                <i class="fa fa-globe fa-1x" aria-hidden="true"></i><span style="word-wrap: break-word;" class="text-primary"><a
                    href="{{ $dealer['website']}}"> {{$dealer['website']}}</a></span>
              </div>
              @endif
              <div class="m-1 col-md-12">
                <i class="fa fa-envelope-o fa-1x" aria-hidden="true"></i><span class="text-nowrap text-primary"><a
                    href="{{ $dealer['email']}}"> {{ $dealer['email']}}</a><span>
              </div>
              <div class="m-1 col-md-12 text-right">
                {{ $dealer['radius']}} 
              </div>
            </div>
            @endforeach
          </div>
        </div>
        <div class="col-lg-8" id='mapid'></div>
      </div>
    </div>
  </div>
