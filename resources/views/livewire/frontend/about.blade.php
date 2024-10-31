<div class="about-header" style="background-image: url({{ Storage::url($banner->image) }});">
  <div class="overlay"></div>
  <div class="container">
    <div class="about-header-container">
      <h1>{{ trans('frontend.about_us') }}</h1>
    </div>
  </div>
</div>
<div>
  <nav aria-label="breadcrumb" class="breadcrumb-1 b-about p-3">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 offset-lg-3" style="text-align: center">
          <div class="about-desktop-view">
            @foreach($category as $k => $v)
            <a class="text-light pc-link{{ Request::is($v->link) ? '-active mx-3' : ' mx-3' }}"
              href="{{ URL::to($v->link) }}">{{ strtoupper($v->title) }}</a>
            @endforeach
          </div>
          <div class="dropdown-mobile-view">
            @foreach($category as $k => $v)
            @if(Request::is($v->link))
            <a id='aboutmobile' class="text-light pc-link{{ Request::is($v->link) ? '-active' : '' }}"
              href="#">{{ strtoupper($v->title) }} <i class="fa fa-angle-down"></i></a>
            @else
            <div id="aboutdropdown">
              <a class="text-light pc-link{{ Request::is($v->link) ? '-active' : '' }}"
              href="{{ URL::to($v->link) }}">{{ strtoupper($v->title) }}</a>
            </div>
            @endif
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </nav>


  <nav aria-label="breadcrumb" class="breadcrumb-2">
    <div class="container">
      <ol class="breadcrumb breadcrumb-2">
        <li class="breadcrumb-item breadcrumb-2-items"><a href="/">{{ trans('frontend.Home') }}</a></li>
        <li class="breadcrumb-item breadcrumb-2-items"><a href="/about">{{ trans('frontend.about_us') }}</a></li>
        <li class="breadcrumb-item breadcrumb-2-items breadcrumb-2-item-active">{{ $content->title }}</li>
      </ol>
    </div>
  </nav>
  {!! $content->content !!}
</div>
