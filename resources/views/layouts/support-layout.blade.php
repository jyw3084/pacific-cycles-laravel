<!DOCTYPE html>
<html lang="en">

<head>
  @include('include.head')
  <link rel="stylesheet" type="text/css" href="/css/support.css?{{ time() }}">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
    crossorigin="" />
  @livewireStyles
  <style>
    #carry {
      display: none;
    }
    #if {
      display: none;
    }
    #reach {
      display: none;
    }
    #supportive {
      display: none;
    }
  </style>
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
<script src="{{'/js/support.js'}}"></script>
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
<script>
	$(function() {
		$('.pc-link').on('click', function(){
      var id = $(this).data('type');
      $('.accordion').hide();
      $('#'+id).show();
			$(this).siblings('.pc-link').removeClass('pc-link-active');
			$(this).addClass('pc-link-active');
		})
	});
</script>
</html>
