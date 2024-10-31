<!DOCTYPE html>
<html lang="en">
    <head>
       @include('include.head')
        <link rel="stylesheet" type="text/css" href="/css/home-layout.css?{{ time() }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        
        @livewireStyles
    </head>
    <body>
       @livewire('frontend.header')
       @yield('content')

        <!-- Footer -->
       @livewire('frontend.footer')
  <!-- Footer -->


    </body>
    @livewireScripts
    <script src="{{'/js/jquery/dist/jquery.min.js'}}"></script>
    <script src="{{'/js/bootstrap/bootstrap.min.js'}}"></script>
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

            $('#homeContainer').on('click',function(){
                if($('#collapsibleNavbar').hasClass('show'))
                {
                    $('#collapsibleNavbar').removeClass('show');
                    $('#overlayHeader').css('display','none');

                }
            });
            $('body').delegate('.container', 'click', function(){
                if($('#collapsibleNavbar').hasClass('show'))
                {
                    $('#collapsibleNavbar').removeClass('show');

                }
            });

            $('#change_lang').on('click', function(){

                window.location.href = '{{ App::getLocale() == 'zh-TW' ? URL::to('set-locale/en'):URL::to('set-locale/zh-TW') }}';
            });
        });
    </script>
  <!--   <script src="https://www.paypalobjects.com/api/checkout.js"></script>
    <script>
        $("#proceed_to_checkout_btn").click(function() {
            $('html, body').animate({
                scrollTop: $("#discount_section").offset().top
            }, 1500);
        });

        $('.add_to_favorites').click(function (){
            // if($(this).hasClass('fa-heart-o')){
                $(this).removeClass('fa fa-heart-o');
                $(this).addClass('fa fa-heart');
            // }
            // if($(this).hasClass('fa-heart')){
            //     $(this).removeClass('fa fa-heart');
            //     $(this).addClass('fa fa-heart-o');
            // }

        })
        function payment_page(){
            $('html, body').animate({
                scrollTop: $("#select_payment_page").offset().top
            }, 400);

            var paypal_client_id = 'AUrmXex2qarslr5v9remcne46ZYcfh001pJKJi82Po4pdNQQlu3PxzmcnTc7xfRTYizrrxTZLAyaBhn9';

            setTimeout(() => {
                paypal.Button.render({
                        // Configure environment
                        env: 'sandbox',
                        client: {
                            sandbox: paypal_client_id,
                            production: 'demo_production_client_id'
                        },
                        // Customize button (optional)
                        locale: 'en_US',
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
                                total: '1.2',
                                currency: 'USD'
                                }
                            }]
                            });
                        },
                        // Execute the payment
                        onAuthorize: function(data, actiowns) {
                            return actions.payment.execute().then(function() {
                            // Show a confirmation message to the buyer
                            window.alert('Thank you for your purchase!');

                            //sample return url
                            window.location.href = 'http://127.0.0.1:8000/shopping/order-complete';
                            });
                        },

                        onError: function(err) {
                            window.alert('An error occurred during the transaction');
                        }
                    }, '#pay_btn');
            }, '1200');


                    $('#paypal-chk').change(function() {
                        if(this.checked) {
                            $("#proceed_payment_btn").css("pointer-events", "none");
                            $('#credit_card-chk').prop('checked', false)
                        }
                        else{
                            $('#paypal-button').css("display", "none");
                            $("#proceed_payment_btn").css("pointer-events", "auto");
                            $("#proceed_payment_btn").css("cursor", "pointer");
                        }

                    });

                    $('#credit_card-chk').change(function() {
                        if(this.checked) {
                            $('#paypal-chk').prop('checked', false)
                            $('#paypal-button').css("display", "none");
                            $("#proceed_payment_btn").css("pointer-events", "auto");
                            $("#proceed_payment_btn").css("cursor", "pointer");
                        }

                    });

        }
    </script> -->
</html>
