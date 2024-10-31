<nav class="navbar navbar-expand-xl navbar-light p-2 pb-1">
  <!-- Brand -->

  <a class="navbar-brand ml-3" href="{{ URL::to('/') }}">
      <img src="{{asset('img/logo.png')}}" alt="">
      <div class="logo-txt">Cycles</div>
  </a>
  <div id="overlayHeader"></div>

  <!-- Toggler/collapsibe Button -->
  <div class="shopcart d-xl-none ml-auto">
      <ul class="nav navbar-nav navbar-right">
          <li class="nav-item px-3 py-3 cart">
              <a class="nav-link text-light cart" href="{{ URL::to('shopping/cart') }}"><img src="{{asset('img/icon-cart.png')}}" alt=""><span class="badge badge-pill badge-light ml-1">{{ $cart }}</span></a>
          </li>
      </ul>
  </div>
  <button id="btnNavbar" class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Navbar links -->
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <div id="collapse-header">
    <a class="navbar-brand ml-3" href="{{ URL::to('/') }}">
        <img src="{{asset('img/logo.png')}}" alt="">
        <div class="logo-txt">Cycles</div>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      &times;
    </button>
    </div>

    <ul class="navbar-nav mr-auto">
      @foreach($headers as $k => $v)
      @if(count($v->childs) > 0)
      <li class="nav-item dropdown px-3 py-3">
        <a class="nav-link dropdown-toggle text-light" href="#" data-toggle="dropdown" aria-haspopup="true"
          aria-expanded="false">{{$v->title}}</a>
        <div class="dropdown-menu">
          @foreach($v->childs as $child)
          <a class="dropdown-item" href="{{ URL::to($child->link) }}">{{$child->title}}</a>
          @endforeach
        </div>
      </li>
      @else
      <li class="nav-item px-3 py-3 @if(Request::is($v->link) || Request::is($v->link.'/*')) nav-active @endif">
        <a class="nav-link text-light" href="{{ URL::to($v->link) }}">{{$v->title}}</a>
      </li>
      @endif
      @endforeach
    </ul>
    <ul class="nav navbar-nav navbar-right">
        <li class="nav-item px-3 py-3 store @if(Request::is('store') || Request::is('store/*') || Request::is('shopping/*')) nav-active @endif">
            <a class="nav-link text-light" href="{{ URL::to('store') }}">{{ trans('frontend.store.title') }}</a>
        </li>

        <li class="nav-item px-3 py-3 cart d-none d-xl-block">
            <a class="nav-link text-light cart" href="{{ URL::to('shopping/cart') }}"><img class='mobile-invert' src="{{asset('img/icon-cart.png')}}" alt=""><span class="badge badge-pill badge-light ml-1">{{ $cart }}</span></a>
        </li>
        @if(auth()->user())
        <!-- Mobile logged in user -->
        <li class="nav-item px-3 py-3 user d-xl-none">
          <a class="mr-3" href="{{ URL::to('my-bike') }}">{{ trans('frontend.dashboard.title') }}</a>
          <span>|</span>
          <a class="ml-3" href="{{ URL::to('logout') }}">{{ trans('frontend.logout') }}</a>
        </li>
        <!-- END -->
        <!-- Desktop logged in user -->
        <li class="nav-item dropdown px-0 py-3 user d-none d-xl-block">
            <a class="nav-link dropdown-toggle text-light" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class='mobile-invert user-pic'src="{{asset('img/icon-user.png')}}" alt=""></a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ URL::to('my-bike') }}">{{ trans('frontend.dashboard.title') }}</a>
                <a class="dropdown-item" href="{{ URL::to('logout') }}">{{ trans('frontend.logout') }}</a>
            </div>
        </li>
        <!-- END -->
        @else
        <!-- Mobile sign in/sign up -->
        <li class="nav-item dropdown px-3 py-3 user d-xl-none">
          <a class="mr-3" href="{{ URL::to('login') }}">{{ trans('frontend.signin') }}</a>
          <span>|</span>
          <a class="ml-3" href="{{ URL::to('signup-with-email') }}">{{ trans('frontend.signup') }}</a>
        </li>
        <!-- END -->
        <!-- Desktop sign in/sign up -->
        <li class="nav-item dropdown px-0 py-3 user d-none d-xl-block">
            <a class="nav-link dropdown-toggle text-light" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class='mobile-invert user-pic' src="{{asset('img/icon-user.png')}}" alt=""></a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ URL::to('login') }}">{{ trans('frontend.signin') }}</a>
                <a class="dropdown-item" href="{{ URL::to('signup-with-email') }}">{{ trans('frontend.signup') }}</a>
            </div>
        </li>
        <!-- END -->
        @endif

        <!-- Mobile language selector -->
        <li class="nav-item px-3 py-3 language d-xl-none">
          <a class="mr-3 {{ App::getLocale() == 'en' ? 'active':'' }}" href="{{ URL::to('set-locale/en') }}">English</a>
          <span>|</span>
          <a class="ml-3 {{ App::getLocale() == 'zh-TW' ? 'active':'' }}" href="{{ URL::to('set-locale/zh-TW') }}">繁體中文</a>
        </li>
        <!-- END -->

        <!-- Desktop language selector -->
        <li class="nav-item dropdown px-3 py-3 language d-none d-xl-block">
            <a class="nav-link dropdown-toggle text-light" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ App::getLocale() == 'en' ? 'ENG':'中文' }}</a>
            <div class="dropdown-menu">
                <a class="dropdown-item {{ App::getLocale() == 'en' ? 'active':'' }}" @if(App::getLocale() == 'zh-TW') href="#" data-lang="en" data-toggle="modal" data-target="#changeLang" @endif>English</a>
                <a class="dropdown-item {{ App::getLocale() == 'zh-TW' ? 'active':'' }}" @if(App::getLocale() == 'en') href="#" data-lang="zh-TW" data-toggle="modal" data-target="#changeLang" @endif>繁體中文</a>
            </div>
        </li>
        <!-- END -->

    </ul>
  </div>

</nav>

<div wire:ignore.self class="modal fade" id="changeLang" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('frontend.change_lang') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
        <div class="modal-body">
                <p>{{ trans('frontend.change_lang_content') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">{{ trans('frontend.store.close') }}</button>
                <button type="button" class="btn btn-danger close-modal" id="change_lang" data-dismiss="modal">{{ trans('frontend.yes') }}</button>
            </div>
        </div>
    </div>
</div>
