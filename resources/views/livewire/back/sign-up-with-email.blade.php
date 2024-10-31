    <div>
        <div class="body_container">
            <!-- body nav start -->
            <div class="body_nav">
                <ul>
                    <li><a href="/">{{ trans('frontend.Home') }}</a></li>
                    <li><a href="{{ URL::to('my-bike') }}">{{ trans('frontend.dashboard.title') }}</a></li>
                    <li>{{ trans('frontend.dashboard.SignUp') }}</li>
                </ul>
            </div>
            <!-- body nav start -->
    
            <div class="heading_title">
                <h2>{{ trans('frontend.dashboard.CreateAccount') }}</h2>
            </div>
            <div class="signup_with_email_phone">
              <div class="row">
                <div class="col-sm-auto p-2">
                  <a class="btn btn-link" href="{{route('signup.with.email')}}">{{ trans('frontend.dashboard.SignUpEmail') }}</a>
                </div>
                <div class="sign_up_phone_num col p-2">
                  <a class="btn" href="{{route('signup.with.phone')}}">{{ trans('frontend.dashboard.SignUpPhone') }}</a>
                </div>
              </div>
            </div>
            <div class="login_area">
                <form method="post" action="{{URL::to('api/ajax?type=registerEmail')}}" id="signUpEmail">
                    <div class="row">
                        <div class="col-xl-6 col-lg-8 col-md-8 col-sm-12">
                            <div class="form-group">
                                <label>
                                    E-mail
                                </label>
                                <input type="email" name="email" autocomplete="off" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>
                                {{ trans('frontend.dashboard.pwd') }}
                                </label>
                                <input type="password" name="password" id="password" autocomplete="off" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>
                                {{ trans('frontend.dashboard.confirm_pwd') }}
                                </label>
                                <input type="password" name="confirmpassword" id="confirmpassword" autocomplete="off" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>
                                {{ trans('frontend.store.name') }}
                                </label>
                                <input type="text" id="firstname" name="name" autocomplete="off" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>
                                {{ trans('frontend.dashboard.birthday') }}
                                </label>
                                <input type="date" id="birthday" name="Birthday" autocomplete="off" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>
                                {{ trans('frontend.store.address') }}
                                </label>
                                <input type="text" id="address" name="Address" autocomplete="off" class="form-control">
                            </div>
                            <div class="row remove_row_margin">
                                <div class="col-xl-9 col-lg-9 col-md-8 col-sm-6">
                                    <label>
                                    {{ trans('frontend.dashboard.captcha') }}
                                    </label>
        
                                    <input type="text" class="form-control" id="captcha" name="captcha" />
                                </div>
                                <div id="captcha" class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                    <input type="text" readonly="true" id="recaptcha" value="{{$captcha}}" />
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-3 col-sm-12">
                            &nbsp;
                        </div>
                        
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">&nbsp;</div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                          <label for="check1" class="signup_checkbox"><input id="check1" class="box" type="checkbox"> {!! trans('frontend.dashboard.i_accept') !!}</label>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                            &nbsp;
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                          <div class="row remove_row_margin">
                            <div class="col-md-7">
                              <input type="submit" name="signup" value="{{ trans('frontend.dashboard.sign_up') }}" class="btn btn-primary" />
                            </div>
                          </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    