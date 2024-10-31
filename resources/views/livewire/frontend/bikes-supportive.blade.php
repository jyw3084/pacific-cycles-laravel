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
            <a class="text-light pc-link mr-5" href="/bikes/folding">{{ strtoupper($category[0]->name) }}</a>
            <a class="text-light pc-link ml-5 mr-5" href="/bikes/ebike">{{ strtoupper($category[1]->name) }}</a>
            <a class="text-light pc-link-active ml-5" href="/bikes/supportive">{{ strtoupper($category[2]->name) }}</a>
      </div>
      <div class="dropdown-mobile-view">
            <a class="text-light pc-link-active ml-5" href="#" id="mobileBikeTitle">{{ strtoupper($category[2]->name) }} <i class="fa fa-angle-down"></i></a>
            <div id="bikedropdown">
              <a class="text-light pc-link mr-5" href="/bikes/folding">{{ strtoupper($category[0]->name) }}</a>
              <a class="text-light pc-link ml-5 mr-5" href="/bikes/ebike">{{ strtoupper($category[1]->name) }}</a>
            </div>

      </div>
    </div>
  </nav>

  <nav aria-label="breadcrumb" class="breadcrumb-2">
    <div class="container">
      <ol class="breadcrumb breadcrumb-2">
        <li class="breadcrumb-item breadcrumb-2-items"><a href="/">{{ trans('frontend.Home') }}</a></li>
        <li class="breadcrumb-item breadcrumb-2-items"><a href="/bikes/folding">{{ trans('frontend.bikes.title') }}</a>
        </li>
        <li class="breadcrumb-item breadcrumb-2-items breadcrumb-2-item-active">{{ $content->name }}</li>
      </ol>
    </div>
  </nav>
  <div id="category" class="container" style="margin-bottom: 8rem;">
    <div class="">
      <div class="mt-5">
        <div class="mb-5">
          <span class="headings">{{ $content->name }}<div class="v-line"></div></span>
        </div>
        <div>
          <span class="headings">{{$content->title}}</span>
          <p class="mt-4">{!! nl2br($content->content) !!}
          </p>
        </div>
      </div>
      {!! $content->items !!}
    </div>
  </div>
</div>
