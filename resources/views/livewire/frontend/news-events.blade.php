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
      <li class="breadcrumb-item breadcrumb-item-active">{{ trans('frontend.news.title') }}</li>
    </ol>
  </div>
</nav>
<div class="container">
  <div class="row" id="news">
    <div class="col-md-12 mt-5 mb-5">
      <span class="headings"> {{ trans('frontend.news.news') }} <div class="Line"></div></span>
      <br>
      <div class="row mb-5">
        @foreach ($news as $k => $v)

        <div class="col-md-4 mt-5">
          <div class="card" style="width: 100%; border: none;">
            <img src="{{Storage::url($v->image)}}" class="card-img-top" alt="...">
            <div class="mt-3">
              <h6>{{date('Y/m/d', strtotime($v->created_at))}}</h6>
              <h5 class="card-title pc-regular-text">{{$v->title}}</h5>
              <p class="card-text pc-p">{{ mb_substr(strip_tags($v->description), 0, 60) }}...</p>
              <a href="/news-events/news/{{$v->id}}" class="pc-link-active">{{ trans('frontend.home.read_more') }} ></a>
            </div>
          </div>
        </div>

        @endforeach
      </div>
      <nav aria-label="pagination">
        {{ $news->links() }}
      </nav>
    </div>
  </div>

  <div class="row" id="event">
    <div class="col-md-12 mt-5 mb-5">
      <span class="headings"> {{ trans('frontend.news.events') }} <div class="Line"></div></span>
      <br>
      <div class="row mb-5">
        @foreach ($events as $event)

        <div class="col-md-4 mt-5">
          <div class="card"
            style="width: 100%; border: none; background: url('{{Storage::url($event->image)}}'); background-size: cover;">
            <div class="overlay"></div>
            <div class="card-body">
              <h6>{{date('Y/m/d', strtotime($event->start_date))}}</h6>
              <h5>{{$event->title[$lang] ?? ''}}</h5>
              <a href="{{URL::to('news-events/event/'.$event->id)}}" class="readmoreevents btn btn-primary">{{ trans('frontend.news.register') }}
                <span style="margin-left: 10px;">></span></a>
            </div>
          </div>
        </div>

        @endforeach
      </div>
      <nav aria-label="pagination">
        {{ $events->links() }}
      </nav>
    </div>

  </div>

</div>
