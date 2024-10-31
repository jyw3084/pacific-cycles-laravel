<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>My Bikes</title>
  <link rel="shortcut icon" href="{{ asset('backend/images/favicon/favicon.png') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{ asset('backend/vendors/scrollbar/perfect-scrollbar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/sweet-alert.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/style/style.css') }}">
  @livewireStyles()

</head>

<body>
  <!-- brand navbar start -->
  <nav class="fixed-top">
    <div class="navbar_brand">
      <a class="brand" href="/">
        <img src="{{ asset('backend/images/logo/logo.png') }}" alt="Pacific Cycles">
      </a>
      <div class="navbar_info">
        <select name="select_language" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
            <option value="/set-locale/en" @if(app()->getLocale() == 'en') selected @endif>ENG</option>
            <option value="/set-locale/zh-TW" @if(app()->getLocale() == 'zh-TW') selected @endif>繁體中文</option>
        </select>
        <a href="{{ URL::to('logout') }}">{{ trans('frontend.dashboard.logout') }}</a>
      </div>
    </div>
  </nav>
  <!-- brand navbar end -->

  <!-- aside nav start -->
  @livewire('back.aside-nav')
  <!-- aside nav end -->

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
  <script src="{{ asset('backend/vendors/scrollbar/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('js/sweet-alert.min.js') }}"></script>
  <script src="{{ asset('backend/js/app.js') }}"></script>
  <script src="https://www.paypalobjects.com/api/checkout.js"></script>
  <script>
    $(function() {

        /*My Coupons filter*/
        $('.filter').click(function(e) {
            e.preventDefault();
            $('.filter').removeClass('active');
            $(this).addClass('active');
            var filter = $(this).attr('filter');
            if (filter != '') {
                $('.filterlist .filters').hide();
                $('.filterlist').find('.' + filter).show();
            } else {
                $('.filterlist .filters').show();
            }
        });

        $('.bike_cancel').on('click', function(){
            var id = $(this).data('id');
            swal({
              title: "{{ trans('frontend.dashboard.sure_cancel') }}?",
              text: "{{ trans('frontend.dashboard.cannot_be_restored') }}！",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "{{ trans('frontend.dashboard.confirm') }}",
              cancelButtonText: "{{ trans('frontend.dashboard.cancel') }}",
              closeOnConfirm: false
            },
            function(){
              swal("{{ trans('frontend.dashboard.success') }}!", "{{ trans('frontend.dashboard.bike_cancelled') }}", "success");
              $('#do_cancel_'+id).trigger('click');
            });
        })

        $('.order_cancel').on('click', function(){
            var id = $(this).data('id');
            swal({
              title: "{{ trans('frontend.dashboard.sure_cancel') }}?",
              text: "{{ trans('frontend.dashboard.cannot_be_restored') }}！",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "{{ trans('frontend.dashboard.confirm') }}",
              cancelButtonText: "{{ trans('frontend.dashboard.cancel') }}",
              closeOnConfirm: false
            },
            function(){
              swal("{{ trans('frontend.dashboard.success') }}!", "{{ trans('frontend.dashboard.order_cancelled') }}", "success");
              $('#do_cancel_'+id).trigger('click');
            });
        })

        $('.order_return').on('click', function(){
            var id = $(this).data('id');
            swal({
              title: "{{ trans('frontend.dashboard.sure_return') }}?",
              text: "{{ trans('frontend.dashboard.cannot_be_restored') }}！",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "{{ trans('frontend.dashboard.confirm') }}",
              cancelButtonText: "{{ trans('frontend.dashboard.cancel') }}",
              closeOnConfirm: false
            },
            function(){
              swal("{{ trans('frontend.dashboard.success') }}!", "{{ trans('frontend.dashboard.return_request') }}", "success");
              $('#do_return_'+id).trigger('click');
            });
        })

        @if (\Session::has('message'))
            swal({
                title: "{{ session('code') == 200 ? trans('frontend.dashboard.success') : '' }}!",
                text: "{{ session('message') }}",
                type: "{{ session('code') == 200 ? 'success' : 'warning' }}",
                {{ session('code') == 200 ? 'timer: 1500' : '' }}
            });
        @endif
        @if(request()->get('d') == 'message')
        var scrollHeight = $('html, body').prop("scrollHeight");
        $('html, body').animate({scrollTop: scrollHeight}, 1500);
        $('[name="message"]').focus();
        @endif

        @if(request()->is('extend/*'))
        Livewire.on('checked', () => {
        var paypal_client_id = 'AUrmXex2qarslr5v9remcne46ZYcfh001pJKJi82Po4pdNQQlu3PxzmcnTc7xfRTYizrrxTZLAyaBhn9';
        var paypalAmount = $('#paypalAmount').val();
        var paypalCurrency = $('#paypalCurrency').val();
            setTimeout(() => {
                paypal.Button.render({
                        // Configure environment
                        env: 'sandbox',
                        client: {
                            sandbox: paypal_client_id,
                            production: 'demo_production_client_id'
                        },
                        // Customize button (optional)
                        locale: '{{ str_replace(['en', 'zh-TW'], ['en_US', 'zh_TW'], app()->getLocale()) }}',
                        style: {
                            size: 'responsive',
                            color: 'gold',
                            shape: 'rect',
                            tagline: false
                        },

                        // Enable Pay Now checkout flow (optional)
                        commit: true,

                        // Set up a payment
                        payment: function(data, actions) {
                            return actions.payment.create({
                            transactions: [{
                                amount: {
                                total: paypalAmount,
                                currency: paypalCurrency
                                }
                            }]
                            });
                        },
                        // Execute the payment
                        onAuthorize: function(data, actions) {
                            return actions.payment.execute().then(function() {
                            // Show a confirmation message to the buyer
                                var ProductNo = $('#ProductNo').val();
                                var year = $('#year').val();
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    type: 'POST',
                                    url: '/extend/pay',
                                    data: {ProductNo:ProductNo, BuyWarrantyYear:year},
                                    dataType: 'json',
                                    success: function(data) {
                                        if(data.code == 200)
                                        {
                                            swal({
                                                title: "{{ trans('frontend.dashboard.success') }}!",
                                                text: "{{ trans('frontend.dashboard.BuyWarrantySuccess') }}",
                                                type: "success",
                                                timer: 1500
                                            });
                                            setTimeout(function () {
                                                window.location.href = '/my-bike'
                                            }, 1505);
                                        }
                                    }
                                })
                            });
                        },

                        onError: function(err) {
                            window.alert('An error occurred during the transaction');
                        }
                    }, '#paypal-btn');
            }, '1000');
        })
        @endif
    });
  </script>
</body>

</html>
