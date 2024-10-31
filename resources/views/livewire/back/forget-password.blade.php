<div>
    <div class="body_container login_container">
        <!-- body nav start -->
        <div class="body_nav">
            <ul>
                <li><a href="/">{{ trans('frontend.Home') }}</a></li>
                <li><a href="{{ URL::to('my-bike') }}">{{ trans('frontend.dashboard.title') }}</a></li>
                <li><a href="{{route('login')}}">{{ trans('frontend.dashboard.login') }}</a></li>
                <li>{{ trans('frontend.dashboard.forgot') }}</li>
            </ul>
        </div>
        <!-- body nav start -->

        <div class="heading_title">
            <h2>{{ trans('frontend.dashboard.forgot') }}</h2>
        </div>
        <form method="post" action="{{URL::to('api/ajax?type=forgotPassword')}}" id="forgotPass">

            <div class="login_area">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-8 col-sm-12">
                        <div class="form-group">
                            <label class="labelcolor">
                                {{ trans('frontend.dashboard.account') }}
                            </label>
                            <input type="text" name="username" id="username" autocomplete="off" class="form-control"
                                placeholder="Email / {{ trans('frontend.dashboard.phone_number') }}">
                        </div>
                        <div class="form-group row">
                            <div class="col-xl-9 col-lg-9 col-md-8 col-sm-6">
                                <label class="labelcolor">
                                    {{ trans('frontend.dashboard.captcha') }}
                                </label>
                                <input id="captcha" name="captcha" type="text" class="form-control">
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                <input type="text" readonly="true" id="recaptcha" value="{{$captcha}}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="login_area">
                <div class="row">
                    <div class="col-xl-5 col-lg-5 col-md-6 col-sm-12">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                    <button type="submit"
                                        class="btn btn-primary w-100 mb-3">{{ trans('frontend.dashboard.confirm') }}</button>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                    <button type="button" class="btn btn-primary w-100 mb-3"
                                        onclick="location.href = '/my-bike';">{{ trans('frontend.dashboard.cancel') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>





    </div>
</div>
</div>
