<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
    <head>
       @include('include.head')
        <link rel="stylesheet" type="text/css" href="{{asset('/css/store.css?').date('YmdHis')}}">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
  integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
  crossorigin=""/>

        @livewireStyles
    </head>
    <body>
        @livewire('frontend.header')
            @yield('content')
        @livewire('frontend.footer')


    </body>
    @livewireScripts
    <script src="{{'/js/jquery/dist/jquery.min.js'}}"></script>
    <script src="{{'/js/bootstrap/bootstrap.min.js'}}"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
    <script>
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

            $('#store-filter').on('click', function(){
                $('#category-filter').addClass('test');

                if($('#collapsibleNavbar').hasClass('show'))
                {
                    $('#collapsibleNavbar').removeClass('show');
                    $('#overlayHeader').css('display','none');

                }
                if($('#category-filter').hasClass('test')){
                    $('#category-filter').fadeOut();
                    $('#category-filter').removeClass('test');
                    $('#store-filter').fadeOut();
                }
            });

            $('body').delegate('.filters', 'click', function(){
                $('#category-filter').fadeIn();
                $('#store-filter').fadeIn();
                $('#category-filter').removeClass('test');
            });
            $('body').delegate('.closeFilter', 'click', function(){
                $('#category-filter').fadeOut();
                $('#store-filter').fadeOut();

            });

        });

    </script>
</html>
