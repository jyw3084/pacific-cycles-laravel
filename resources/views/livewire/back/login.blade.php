<style>
    .social {
        width:100%;
        height:auto;
        border-radius: 7.5px;
        border: solid 1px #000;
        object-fit: contain;
        font-size: 10px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: 1.13px;
        color: #000;
    }
    .social img{
        height:33px;
        margin: 15px 10px;
        width:auto;
    }
    .social span{
        font-size:18px;
    }
    .body_container .login_area .facebook {
        width: 25px;
        height: 25px;
        margin: 0 20px 0 0;
        object-fit: contain;
    }
    .body_container .login_area .google {
        width: 25px;
        height: 25px;
        margin: 0 20px 0 0;
        object-fit: contain;
    }

    .forget-pass {
        font-family: Montserrat;
        font-size: 13px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 2;
        letter-spacing: normal;
        color: #f99b0c;
}

    .Dont-have-an-accoun {
        font-family: Montserrat;
        font-size: 13px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 2;
        letter-spacing: normal;
        color: #000;
    }
    .Dont-have-an-accoun .tx-1 {
        color: #f99b0c;
    }

</style>
<div>
    <div class="body_container">
        <!-- body nav start -->
        <div class="body_nav">
            <ul>
                <li><a href="#">{{ trans('frontend.Home') }}</a></li>
                <li><a href="#">{{ trans('frontend.dashboard.title') }}</a></li>
                <li>{{ trans('frontend.dashboard.login') }}</li>
            </ul>
        </div>
        <!-- body nav start -->

        <div class="heading_title">
            <h2>{{ trans('frontend.dashboard.login2') }}</h2>
        </div>
        <div class="login_area">
            <form id="loginform" method="POST" action='{{URL::to("login")}}'>
            @csrf
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                    <form action="">
                        <div class="form-group">
                            <label>
                            {{ trans('frontend.dashboard.account') }}
                            </label>
                            <input id="username" name="username" type="text" name="email" autocomplete="off" class="form-control" placeholder="Email / {{ trans('frontend.dashboard.phone_number') }}" required>
                        </div>
                        <div class="form-group">
                            <label>
                            {{ trans('frontend.dashboard.pwd') }}
                            </label>
                            <input type="password" name="password" id="password" autocomplete="off" class="form-control" placeholder="{{ trans('frontend.dashboard.pwd') }}" required>
                        </div>
                        <div id="register-link" class="text-right">
                            <a href="/forget-password" class="forget-pass">{{ trans('frontend.dashboard.forgot') }}?</a>
                        </div>
                        <!-- <div class="form-group">
                            <label>
                                驗證碼
                            </label>
                            <input type="text" style="padding: 20px margin: 30px" class="form-control" placeholder="01928323723">
                        </div> -->
                    </form>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                    &nbsp;
                </div>

                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                    <div>
                        <label>{{ trans('frontend.dashboard.login_with') }}</label>
                    </div>
                    <div class="row">

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <a class="btn social" href="auth/facebook">
                              <img src="img/facebook/facebook.png">
                              <span>Facebook</span>
                            </a>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <a class="btn social" href="auth/google">
                              <img src="img/google/google.png">
                              <span>Google</span>
                            </a>
                        </div>
                    </div>

                </div>
                <div class="col-xl-6 col-lg-4 col-md-12 col-sm-12">
                    &nbsp;
                </div>
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                  <button class="btn btn-primary my-4 w-100">{{ trans('frontend.dashboard.login') }}</button>
                  <div id="register-link" class="text">
                    <a href="signup-with-email" class="Dont-have-an-accoun">{{ trans('frontend.dashboard.no_account') }}? <span class="tx-1">{{ trans('frontend.dashboard.signup') }}</span></a>
                  </div>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
