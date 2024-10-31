<div id="homeContainer">
  @if($splash_area)
  <section id="section1"
    style="background-image: url({{ Storage::url($splash_area->background_img) }});">
    <div class="overlay"></div>
    <div class="section1Container">
      <div class="section1Content">
        <h1>
          {{ $splash_area->title }}
        </h1>
        <p>{{ $splash_area->content }}</p>
        <a href="{{ $splash_area->link }}" class="btn btn-primary">{{ trans('frontend.home.learn_more') }}</a>
      </div>
    </div>
  </section>
  @endif


  @if($featureBikes->count() > 0)
  <section id="section3">
    <h1 class="headings mt-5">{{ trans('frontend.home.feature_bike') }}<span class="Line"></span></h1>
    <div class="row">

      @foreach($featureBikes as $featureBike)
      <div class="col-lg-6 p-3 d-flex flex-column justify-content-between">
        <a href="/store/products/{{ $featureBike->product_code }}"><img src="{{ $featureBike->images[0] ? Storage::url($featureBike->images[0]):'' }}" class="img-fluid" /></a>
        <div class="product_meta">
          <h6 class="text-primary mt-4">{{ $featureBike->category->name ?? ''}} - {{ $featureBike->bike_model->bike_model ?? ''}}</h6>
          <h5 class="product-name">{{ $featureBike->product_name }} </h5>
          <h6> {{ $featureBike->description }}</h6>
        </div>
      </div>
      @endforeach

    </div>
  </section>
  @else

  @endif
      @if($about_area)
          <section id="section7"
                   style="background-image: url({{ Storage::url($about_area->background_img) }});">
              <div class="section7Container">
                  <div class="section7Content">
                      <h1 class="first">{{ $about_area->title }} |</h1>
                      <h1 class="second">{{ $about_area->subtitle }}</h1>
                      <p>{{ $about_area->content }}</p>
                      <a class="btn btn-primary" href="{{ $about_area->link }}">{{ trans('frontend.home.learn_more') }}</a>
                  </div>
              </div>
          </section>
      @endif

      <section id="section5">
          <div class="row">
              <div class="col-lg-12 mt-5">
                  <h1 class="headings"> {{ trans('frontend.home.news') }} <div class="Line"></div>
                  </h1>
                  <a href="/news-events" class="pc-link-active">{{ trans('frontend.home.view_more') }} <span
                          style="margin-left: 10px;"> > </span></a>
                  <div class="row">
                      @if($news)
                          @foreach ($news as $news)

                              <div class="col-lg-4 offset-lg-0 col-md-10 offset-md-1 mt-5">
                                  <div class="card" style="width: 100%; border: none;">
                                      <img src="{{Storage::url($news->image);}}" class="card-img-top" alt="...">
                                      <div class="mt-3">
                                          <h6>{{date('Y/m/d', strtotime($news->created_at))}}</h6>
                                          <h5 class="card-title pc-regular-text">{{ $news->title }}</h5>
                                          <p>{{ mb_substr(strip_tags($news->description), 0, 60) }}...</p>
                                          <a href="/news-events/news/{{ $news->id }}" class="pc-link-active">{{ trans('frontend.home.read_more')
                  }} ></a>
                                      </div>
                                  </div>
                              </div>

                          @endforeach
                      @else
                          <div class="col-12">
                              <p class="mt-3 text-center text-muted">{{ trans('frontend.home.no_items') }}</p>
                          </div>
                      @endif
                  </div>

              </div>

          </div>
      </section>
      <section id="section6">
          <div class="row">
              <div class="col-lg-12 mt-5">
                  <h1 class="headings"> {{ trans('frontend.home.events') }} <div class="Line"></div>
                  </h1>
                  <a href="/news-events" class="pc-link-active">{{ trans('frontend.home.view_more') }} <span
                          style="margin-left: 10px;"> > </span></a>
                  <div class="row">
                      @if(count($events) != 0)

                          @foreach ($events as $event)

                              <div class="col-lg-4 offset-lg-0 col-md-10 offset-md-1 mt-5">
                                  <div class="card"
                                       style="width: 100%; border: none; background: url('{{Storage::url($event->image);}}'); background-size: cover;">
                                      <div class="overlay"></div>
                                      <div class="card-body">
                                          <h6>{{date('Y/m/d', strtotime($event->start_date))}}</h6>
                                          <h5>{{$event->title[$lang]}}</h5>
                                          <a href="/news-events/event/{{ $event->id }}" class="readmoreevents btn btn-primary">{{
                  trans('frontend.news.register') }} <span style="margin-left: 10px;">></span></a>
                                      </div>
                                  </div>
                              </div>

                          @endforeach
                      @else
                          <div class="col-12">
                              <p class="mt-3 text-center text-muted">{{ trans('frontend.home.no_items') }}</p>
                          </div>
                      @endif
                  </div>

              </div>

          </div>

      </section>

      <section id="section2">
          <div class="row">
              @if($register_area)
                  <div class="col-md-6 section2ContentLeft">
                      <div class="section2mainContent"
                           style="background: url({{ Storage::url($register_area->background_img) }});">
                          <h1>{{$register_area->title}}</h1>
                          <a class="btn btn-primary btn-section2" href="{{ $register_area->link }}">{{ trans('frontend.home.register') }}</a>
                      </div>
                  </div>
              @endif
              @if($warranty_area)
                  <div class="col-md-6 section2ContentRight">
                      <div class="section2mainContent"
                           style="background: url({{ Storage::url($warranty_area->background_img) }});">
                          <h1>{{$warranty_area->title}}</h1>
                          <a class="btn btn-primary btn-section2" href="{{ $warranty_area->link }}">{{
            trans('frontend.home.learn_more') }}</a>

                      </div>
                  </div>
              @endif
          </div>
      </section>

  @if($dealer_area)
  <section id="section4"
    style="background-image: url({{ Storage::url($dealer_area->background_img) }});">
    <div class="section4Container">
      <div class="section4Content">
        <h1>{{ $dealer_area->title }}</h1>
        <p>{{ $dealer_area->content }}</p>
        <a class="btn btn-primary" href="{{ $dealer_area->link }}">{{ trans('frontend.home.learn_more') }}</a>
      </div>
    </div>
  </section>
  @endif


</div>
