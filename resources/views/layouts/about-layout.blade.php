<!DOCTYPE html>
<html lang="en">

<head>
  @include('include.head')
  <link rel="stylesheet" type="text/css" href="/css/about.css?{{ time() }}">
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
<script type="text/javascript">
    $(document).ready(function(){

        $('#aboutmobile').on('click',function(){
          $('#aboutdropdown').toggle();
        });

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
    });
</script>
</html>
