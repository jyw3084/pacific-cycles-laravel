<div>
  <div class="navbar aside_nav navbar-expand-lg">
    <a class="brand {{ Request::routeIs('mybike') ? 'active' : '' }} {{ Request::routeIs('register.bike.with.qr') ? 'active' : '' }} {{ Request::routeIs('bike.details') ? 'active' : '' }}"
      href="{{ route('mybike') }}"><span>{{ trans('frontend.dashboard.my_bikes') }}</span>
    </a>
    <button class="navbar-toggler" data-toggle="collapse" data-target="#nav">
      <i class="fa fa-angle-down"></i>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav">
        <li><a
            class="{{ Request::routeIs('myaccount') ? 'active' : '' }} {{ Request::is('myaccount.edit') ? 'active' : '' }}"
            href="{{ route('myaccount') }}"><span>{{ trans('frontend.dashboard.my_account') }}</span></a></li>
        <li><a
            class="{{ Request::routeIs('myorder') ? 'active' : '' }} {{ Request::is('myorderdetails') ? 'active' : '' }}"
            href="{{URL::to('my-orders')}}"><span>{{ trans('frontend.dashboard.my_order') }}</span></a></li>
        <li><a class="{{ Request::routeIs('mycoupons') ? 'active' : '' }}" href="{{URL::to('my-coupons')}}"><span>{{ trans('frontend.dashboard.my_coupons') }}</span></a></li>
        <li><a class="{{ Request::routeIs('mycredits') ? 'active' : '' }}" href="{{URL::to('my-credits')}}"><span>{{ trans('frontend.dashboard.my_credits') }}</span></a></li>
        <li><a href="https://youtube.com/user/pacificcycles" target="_blank"><span>{{ trans('frontend.dashboard.tuborial_videos') }}</span></a></li>
        <li><a href="{{URL::to('dealer')}}" target="_blank"><span>{{ trans('frontend.dashboard.service_location') }}</span></a></li>
        <li><a href="{{URL::to('contact')}}" target="_blank"><span>{{ trans('frontend.dashboard.contact_us') }}</span></a></li>
      </ul>
    </div>
  </div>
</div>
