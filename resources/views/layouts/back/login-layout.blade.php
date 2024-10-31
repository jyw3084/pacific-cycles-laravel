<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>{{ trans('frontend.dashboard.login') }}</title>
  <link rel="shortcut icon" href="{{ asset('backend/images/favicon/favicon.png') }}">
  <link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="{{ asset('backend/vendors/scrollbar/perfect-scrollbar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/sweet-alert.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/style/style.css?'.date('YmdHis')) }}">
  @if(request()->route()->named('login'))
  <style>
      .showSweetAlert .confirm {
          display: none;
      }
  </style>
  @endif
  @livewireStyles()
</head>

<body>
    <!-- brand navbar start -->
    <nav class="fixed-top">
        <div class="navbar_brand">
            <a class="brand" href="/">
                <img src="{{ asset('backend/images/logo/logo.png') }}" alt="">
            </a>
            <div class="navbar_info">
                <select name="select_language" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                    <option value="/set-locale/en" @if(app()->getLocale() == 'en') selected @endif>ENG</option>
                    <option value="/set-locale/zh-TW" @if(app()->getLocale() == 'zh-TW') selected @endif>繁體中文</option>
                </select>
                <a href="{{ url('/') }}">{{ trans('frontend.dashboard.logout') }}</a>
            </div>
        </div>
    </nav>
    <!-- brand navbar end -->


    <!-- aside nav start -->
    {{-- @livewire('back.aside-nav') --}}
    <!-- aside nav end -->
    <input type="hidden" id="baseurl" value="{{URL::to('/')}}" />
    @yield('content')

    <!-- body container end -->

    <!-- footer area start -->
    <footer class="footer_area">
        <p>© 2021 PACIFIC CYCLES, ALL RIGHTS RESERVED</p>
    </footer>
    <!-- footer area end -->

    @livewireScripts()
    <script src="{{'/js/jquery/dist/jquery.min.js'}}"></script>
    <script src="{{'/js/bootstrap/bootstrap.min.js'}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="{{ asset('backend/vendors/scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('backend/js/jquery-ui.js') }}"></script>
    <script src="{{ asset('backend/js/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('backend/js/validation/jquery.additional-methods.min.js') }}"></script>
    <script src="{{ asset('backend/js/jquery.form.js') }}"></script>
    <script src="{{ asset('js/sweet-alert.min.js') }}"></script>
    <script src="{{ asset('backend/js/app.js') }}"></script>
    @if(Request::url() == URL::to('/signup-with-email'))
    <script>
        $(document).ready(function(){
            var signUpEmail = $("#signUpEmail");
                signUpEmail.validate({
                    onkeyup: function (element) {
                        this.element(element);
                    },
                    onfocusout: function (element) {
                        this.element(element);
                    },
                    rules: {
                        email: {
                            required: true,
                            email: true
                        },
                        name: {
                            required: true,
                        },
                        Address: {
                            required: true,
                        },
                        Birthday: {
                            required: true,
                        },
                        password: {
                            required: true,
                            minlength: 7
                        },
                        confirmpassword: {
                            equalTo: "#password",
                            required: true,
                        },
                        captcha: {
                            equalTo: "#recaptcha",
                            required: true,
                        }
                    },
                    messages: {
                        email: {
                            required: "{{__('frontend.required')}}",
                            email: "{{__('frontend.email')}}"
                        },
                        name: "{{__('frontend.required')}}",
                        Address: "{{__('frontend.required')}}",
                        Birthday: "{{__('frontend.required')}}",
                        password: {
                            required: "{{__('frontend.required')}}",
                            minlength: "{{__('frontend.minlength')}}"
                        },
                        confirmpassword: {
                            required: "{{__('frontend.required')}}",
                            equalTo: "{{__('frontend.incorrect_password')}}"
                        },
                        captcha: {
                            required: "{{__('frontend.required')}}",
                            equalTo: "{{__('frontend.incorrect')}}"
                        },
                        code: {
                            equalTo: "{{__('frontend.incorrect')}}"
                        }
                    }
                });

                var options = {
                    beforeSubmit: function () {
                        if($('#check1').is(':checked')) { 
                            
                        } else{
                            swal('','{{ __('frontend.dashboard.agree_terms') }}','warning')
                            return false;
                        }
                    },
                    success: function (response) {
                        if(response == 'success'){
                            swal({   
                                title: '{{ __('frontend.dashboard.register_complate') }}',   
                                text: '{{ __('frontend.dashboard.thankyou') }}',
                                type: "success",   
                                showCancelButton: false,    
                                closeOnConfirm: false 
                            }, function(){   
                                window.location.href = '/login'
                            });
                        } else{
                            swal('{{ __('frontend.dashboard.SignUpFail') }}','','error')
                        }
                        
                    }
                };
                signUpEmail.ajaxForm(options);
        });
    </script>
    @elseif(Request::url() == URL::to('/signup-with-phone'))
    <script>
        $(document).ready(function(){
            var signUpPhone = $("#signUpPhone");
                signUpPhone.validate({
                    onkeyup: function (element) {
                        this.element(element);
                    },
                    onfocusout: function (element) {
                        this.element(element);
                    },
                    rules: {
                        phone_number: {
                            required: true,
                        },
                        name: {
                            required: true,
                        },
                        Address: {
                            required: true,
                        },
                        Birthday: {
                            required: true,
                        },
                        password: {
                            required: true,
                            minlength: 7
                        },
                        confirmpassword: {
                            equalTo: "#password",
                            required: true,
                        },
                        captcha: {
                            equalTo: "#recaptcha",
                            required: true,
                        },
                        code: {
                            equalTo: '#recode'
                        }
                    },
                    messages: {
                        phone_number: "{{__('frontend.required')}}",
                        name: "{{__('frontend.required')}}",
                        Address: "{{__('frontend.required')}}",
                        Birthday: "{{__('frontend.required')}}",
                        password: {
                            required: "{{__('frontend.required')}}",
                            minlength: "{{__('frontend.minlength')}}"
                        },
                        confirmpassword: {
                            required: "{{__('frontend.required')}}",
                            equalTo: "{{__('frontend.incorrect_password')}}"
                        },
                        captcha: {
                            required: "{{__('frontend.required')}}",
                            equalTo: "{{__('frontend.incorrect')}}"
                        },
                        code: {
                            equalTo: "{{__('frontend.incorrect')}}"
                        }
                    }
                });

                var options = {
                    beforeSubmit: function () {
                        if($('#check1').is(':checked')) { 
                            
                        } else{
                            swal('','{{ __('frontend.dashboard.agree_terms') }}','warning')
                            return false;
                        }
                    },
                    success: function (response) {
                        if(response == 'success'){
                            swal({   
                                title: '{{ __('frontend.dashboard.register_complate') }}',   
                                text: '{{ __('frontend.dashboard.thankyou') }}',
                                type: "success",   
                                showCancelButton: false,    
                                closeOnConfirm: false 
                            }, function(){   
                                window.location.href = '/login'
                            });
                        } else{
                            swal('{{ __('frontend.dashboard.SignUpFail') }}','','error')
                        }
                        
                    }
                };
                signUpPhone.ajaxForm(options);

                $('#sendCode').on('click',function(){
                    var BASE_URL = $('#baseurl').val();
                    var phone_number = $('#phone_number').val();
                    if(phone_number == '' || phone_number == ' '){
                        swal('','{{ __('frontend.dashboard.input_phone') }}','error')
                    } else{

                        settime();

                        $.ajax({
                            url : BASE_URL+'/api/ajax?type=sendCode',
                            type : 'POST',
                            data : { phone_number: phone_number},
                            success : function(data){
                                swal('Success','{{ __('frontend.dashboard.ckech_phone') }}','success');
                                $('#recode').val(data);
                            }
                        });  
                    }
                    
                });

                var countdown = 60;
                function settime() {
                    if (countdown == 0) {
                        $('#sendCode').prop('disabled', false);
                        $('#sendCode').text('Send Code');
                        countdown = 60;
                        return;
                    } else {
                        $('#sendCode').prop('disabled', true);
                        $('#sendCode').text('Resend (' + countdown +')');
                        countdown--;
                    }
                    setTimeout(function () {
                        settime()
                    }, 1000)
                }
        });

    </script>
    @elseif(Request::url() == URL::to('/forget-password'))
    <script>
        $(document).ready(function(){
            var forgotPass = $("#forgotPass");
                forgotPass.validate({
                    onkeyup: function (element) {
                        this.element(element);
                    },
                    onfocusout: function (element) {
                        this.element(element);
                    },
                    rules: {
                        username: {
                            required: true,
                        },
                        captcha: {
                            equalTo: "#recaptcha",
                            required: true,
                        }
                    },
                    messages: {
                        username: "{{__('frontend.required')}}",
                        captcha: {
                            required: "{{__('frontend.required')}}",
                            equalTo: "{{__('frontend.incorrect')}}"
                        }
                    },
                });

                var options = {
                    beforeSubmit: function () {
                        
                    },
                    success: function (response) {
                        if(response == 'success'){
                            swal({   
                                title: '{{ __('frontend.dashboard.success') }}',   
                                text: '{{ __('frontend.dashboard.check_password') }}',
                                type: "success",   
                                showCancelButton: false,    
                                closeOnConfirm: false 
                            }, function(){   
                                window.location.href = '/login'
                            });

                        } else{
                            swal('Error','','error')
                        }
                        
                    }
                };
                forgotPass.ajaxForm(options);
        });
    </script>
    @endif
    <script>
        $(document).ready(function(){
            $("#loginform").validate({
                submitHandler : function(form) {
                    swal({   
                        title: '{{ __('frontend.dashboard.PleaseWait') }}',   
                        text: '{{ __('frontend.dashboard.DataConfirmation') }}',
                        type: "success",   
                        timer: 30000,
                        showCancelButton: false,
                        showConfirmButton: false
                    });
                    form.submit();
                },
                messages: {
                    username: "{{__('frontend.required')}}",
                    password: "{{__('frontend.required')}}",
                },
            });
        @if (\Session::has('code'))
            @if(auth()->user())
            swal({
                title: "{{ trans('frontend.dashboard.success') }}!",
                text: "{{ trans('frontend.dashboard.login_success') }}",
                type: "success",
                timer: 1500
            });
            setTimeout(function () {
                window.location.href = '/my-bike'
            }, 1505);
            @else
            
            $('.confirm').attr("style", "display: inline !important");
            swal({
                title: '',   
                text: '{{ __('frontend.dashboard.login_fail') }}',
                type: "error",   
                timer: 30000,
                showCancelButton: false,    
                closeOnConfirm: false 
            }, function(){   
                window.location.href = '/login'
            });

            @endif
        @endif
        });
    </script>
</body>

</html>
