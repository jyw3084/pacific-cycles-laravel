<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.head')
  <link rel="stylesheet" type="text/css" href="/css/contact.css?{{ time() }}">
  <link rel="stylesheet" href="{{ asset('css/sweet-alert.css') }}">
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
<script src="{{ asset('js/sweet-alert.min.js') }}"></script>
<script type="text/javascript">
      $(document).ready(function(){

        @if (\Session::has('message'))
            swal({
                title: "{{ session('code') == 200 ? trans('frontend.dashboard.success') : '' }}!",
                text: "{{ session('message') }}",
                type: "{{ session('code') == 200 ? 'success' : 'warning' }}",
                timer: 1500
            });
        @endif
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
