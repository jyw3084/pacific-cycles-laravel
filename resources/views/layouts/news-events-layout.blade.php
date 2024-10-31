<!DOCTYPE html>
<html lang="en">

<head>
  @include('include.head')
  <link rel="stylesheet" type="text/css" href="{{asset('/css/news-events.css')}}">
  <link rel="stylesheet" href="{{ asset('css/sweet-alert.css') }}">
  @livewireStyles
</head>

<body>
  @livewire('frontend.header')
    <input type="hidden" id="baseurl" value="{{URL::to('/')}}" />
  @yield('content')
  @livewire('frontend.footer')
</body>

@livewireScripts
<script src="{{'/js/jquery/dist/jquery.min.js'}}"></script>
<script src="{{'/js/bootstrap/bootstrap.min.js'}}"></script>
<script src="{{ asset('backend/js/jquery-ui.js') }}"></script>
<script src="{{ asset('backend/js/validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('backend/js/validation/jquery.additional-methods.min.js') }}"></script>
<script src="{{ asset('backend/js/jquery.form.js') }}"></script>
<script src="{{ asset('js/sweet-alert.min.js') }}"></script>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#overlayHeader').on('click',function(){
            if($('#collapsibleNavbar').hasClass('show'))
            {
                $('#collapsibleNavbar').removeClass('show');
                $('#overlayHeader').css('display','none');
                $('#btnNavbar').addClass('collapsed')
            }
        });
        $('.navbar-toggler').on('click', function(){
            var toggle = $(this);
            if(toggle.hasClass('collapsed')){
                $('#overlayHeader').css('display','block');
            }else{
                $('#overlayHeader').css('display','none');
            }
        });

        $('#change_lang').on('click', function(){

            window.location.href = '{{ App::getLocale() == 'zh-TW' ? URL::to('set-locale/en'):URL::to('set-locale/zh-TW') }}';
        });

        @if(Route::current()->getName() == 'event-register')
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
                                var event_id = $('#event_id').val();
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    type: 'POST',
                                    url: '/event/pay',
                                    data: {event_id:event_id},
                                    dataType: 'json',
                                    success: function(data) {
                                        if(data.code == 200)
                                        {
                                            swal({
                                                title: "{{ trans('frontend.dashboard.success') }}!",
                                                text: "{{ trans('frontend.news.success') }}",
                                                type: "success",
                                                timer: 1500
                                            });
                                            setTimeout(function () {
                                                window.location.href = '/news-events'
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
            }, '1200');

        $('#cc').click(function(){
            var element = document.getElementById('complete-btn');
            element.classList.remove("d-none");
            var element = document.getElementById('complete-btn-paypal');
            element.classList.add("d-none");
            var element = document.getElementById('complete-btn-bank');
            element.classList.add("d-none");
            var cc = $('#cc-detail');
            cc.removeClass("d-none");
            $('#cc-detail input').prop('required', true);
        });
        $('#bank').click(function(){
            var element = document.getElementById('cc-detail');
            element.classList.add("d-none");
            var element = document.getElementById('complete-btn');
            element.classList.add("d-none");
            var element = document.getElementById('complete-btn-bank');
            element.classList.remove("d-none");
            var element = document.getElementById('complete-btn-paypal');
            element.classList.add("d-none");
            $('#cc-detail input').prop('required', false);
        });
        $('#paypal').click(function(){
            var element = document.getElementById('cc-detail');
            element.classList.add("d-none");
            var element = document.getElementById('complete-btn');
            element.classList.add("d-none");
            var element = document.getElementById('complete-btn-bank');
            element.classList.add("d-none");
            var element = document.getElementById('complete-btn-paypal');
            element.classList.remove("d-none");
        });
        @endif

        @if (\Session::has('message'))
            swal({
                title: "{{ session('code') == 200 ? trans('frontend.dashboard.success') : '' }}!",
                text: "{{ session('message') }}",
                type: "{{ session('code') == 200 ? 'success' : 'warning' }}",
                {{ session('code') == 200 ? 'timer: 1500' : '' }}
            });
        @endif
        @if (\Session::has('pay'))
            $([document.documentElement, document.body]).animate({
                scrollTop: $("form").offset().top
            }, 500);
        @endif

    });
</script>

</html>
