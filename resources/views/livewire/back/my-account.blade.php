<div>
    <div class="body_container">
        <!-- body nav start -->
        <div class="body_nav">
            <ul>
                <li><a href="/">{{ trans('frontend.Home') }}</a></li>
                <li><a href="{{ URL::to('my-bike') }}">{{ trans('frontend.dashboard.title') }}</a></li>
                <li>{{ trans('frontend.dashboard.my_account') }}</li>
            </ul>
        </div>
        <!-- body nav start -->

        <div class="heading_title">
            <h2>{{ trans('frontend.dashboard.my_account') }}</h2>
        </div>
        <div class="product_card_area">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                    <label>
                    {{ trans('frontend.store.name') }}
                    </label>
                    <div class="details_text">
                    {{$user->name}}
                    </div>
                    <label>
                        Email
                    </label>
                    <div class="details_text">
                    {{$user->email}}
                    </div>
                    <label>
                    {{ trans('frontend.store.phone') }}
                    </label>
                    <div class="details_text">
                    {{$user->phone_number}}
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                    <label>
                    {{ trans('frontend.store.address') }}
                    </label>
                    <div class="details_text">
                    {{ $address = $user->Address ? $user->Address.', '.$user->CountryCode : ''}}
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">

                </div>
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-md-6">
                            <a class="btn edit_button" href="{{route('myaccount.edit')}}">{{ trans('frontend.dashboard.edit') }}</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="product_card_area">
            <div class="row">
                <div class="col-xl-12 col-lg-4 col-md-12 col-sm-12">
                    <label>
                    {{ trans('frontend.dashboard.pwd') }}
                    </label>
                    <div class="details_text">
                        *********
                    </div>
                </div>
                <div class="col-xl-12 col-lg-4 col-md-12 col-sm-12">
                    <div class="edit_button1"></div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-md-6">
                            <a class="btn edit_button" href="{{route('password.change')}}">{{ trans('frontend.dashboard.edit') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
