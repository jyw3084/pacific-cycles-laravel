<div class="news-header">
  <div class="overlay"></div>
  <div class="container">
    <div class="news-header-container">
      <h1>{{ trans('frontend.news.title') }}</h1>
    </div>
  </div>
</div>
<nav aria-label="breadcrumb" class="breadcrumb">
  <div class="container">
    <ol class="d-flex">
      <li class="breadcrumb-item"><a href="/">{{ trans('frontend.Home') }}</a></li>
      <li class="breadcrumb-item"><a href="/news-events">{{ trans('frontend.news.title') }}</a></li>
      <li class="breadcrumb-item breadcrumb-item-active">{{ trans('frontend.news.event_detail') }}</li>
    </ol>
  </div>
</nav>

<div class="container">
  <div class="row">
    <div class="col-md-12  mb-5">
      <div class="row">

        <div class="col-md-12 ">
          <div class="card" style="width: 100%; border: none;">
            <img src="{{Storage::url($event->image)}}" class="card-img-top" alt="...">
            <div class="card-body">
              <span class="md-text pc-text-color">{{$event->created_at->format('Y m d')}}</span>
              <br>
              <h5 class="card-title pc-regular-text mt-5">{{$event->title[$lang] ?? ''}}</h5>
              <p class="card-text pc-pdetail">{{$event->description[$lang] ?? ''}}</p>
              <br>
              <a href="/news-events/event/{{$event->id}}/register" class="btn btn-primary mt-5 mb-5">{{ trans('frontend.news.register') }}</a>
            </div>

          </div>
        </div>

      </div>
    </div>

    <br>
    <br>
    <br>
    <br>


  </div>
</div>
