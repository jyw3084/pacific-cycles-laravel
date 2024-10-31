<!DOCTYPE html>
<html lang="en">

<head>
    @include('include.head')
  <link rel="stylesheet" type="text/css" href="/css/dealer.css?{{ time() }}">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.css"></link>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.Default.css"></link>
  <link rel="stylesheet" href="{{ asset('css/sweet-alert.css') }}">
  <style>
    .item_result {
      cursor: pointer;
    }
  </style>
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
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/leaflet.markercluster.js"></script>

<script>
  $(document).ready(function() {
    $('#dealermobile').on('click',function(){
      $('#dealerdropdown').toggle();
    });

    @if(session()->get('message'))
        swal({
            title: "{{ session('code') == 200 ? trans('frontend.dashboard.success') : '' }}!",
            text: "{{ session('message') }}",
            type: "success",
            timer: 1500
        });
    @endif

    $('#change_lang').on('click', function(){

        window.location.href = '{{ App::getLocale() == 'zh-TW' ? URL::to('set-locale/en'):URL::to('set-locale/zh-TW') }}';
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
    $('#searchBtn').on('click', function(){
      var search = $('#location').val();
      $(".item_result").each(function(){
          var element = $(this).attr('class');
          var searchIf = hasClass(element, search.toLowerCase());
          if(search == '' || search == ' '){
            $(this).removeClass('displaynone');
            $(this).addClass('displayblock');
          } else{
            if(searchIf == true){
              $(this).removeClass('displaynone');
              $(this).addClass('displayblock');
            } else{
              $(this).removeClass('displayblock');
              $(this).addClass('displaynone');
            }
          }

      });
      makeMap(rad, center, false, search);
    });

    $('.item_result').on('click', function(){
        var lat = $(this).data('lat');
        var lng = $(this).data('long');
        var name = $(this).data('name');
        var address = $(this).data('address');
        var phone = $(this).data('phone');
        var rad = $('#radius').val();
        makeMap(rad, [lat,lng], '<h1>'+name+'</h1><p>'+address+'<br>'+phone+'</p>');
    })

    function hasClass(element, cls) {
        return (' ' + element + ' ').indexOf(' ' + cls) > -1;
    }

  })

  $('#where-to-buy-btn').click(function() {

    $('#mapid').remove();
    $.getScript('https://unpkg.com/leaflet@1.7.1/dist/leaflet.js', function() {
      $('.where-to-buy-content').append(
        `<div class="col-md-8" id='mapid' style='width: 100%; height: 100%;'></div>`
      );
      navigator.geolocation.getCurrentPosition(showPosition);
    });


  });

  var rad = $('#radius').val();
  var lat = 23.939599878268883;
  var lng = 120.9472773223739;
  var center = [lat,lng];
  makeMap(rad, center);

  function makeMap(rad, center, open = false, search = false){
      $('#mapid').remove();
      $('.where-to-buy-content').append(
        `<div class="col-md-8" id='mapid' style='width: 100%; height: auto;'></div>`
      );
      var map = L.map('mapid', {
        center: center,
        zoom: rad
      });

      @if(request()->segment(2) == null)
      @foreach($dealers as $k => $dealer)
      var string = '{{ strtolower($dealer['name'])}} {{ strtolower($dealer['country'])}} {{ strtolower($dealer['address'])}}';
      if(search && string.match(search.toLowerCase()) != null)
      {
        L.marker([{{$dealer['Lat']}},{{$dealer['Long']}}]).addTo(map)
          .bindPopup('<h1>{{ $dealer['name']}}</h1><p>{{ $dealer['country']}} {{ $dealer['address']}}<br>{{$dealer['phone']}}</p>')
      }
      else if(search == false)
      {
        L.marker([{{$dealer['Lat']}},{{$dealer['Long']}}]).addTo(map)
          .bindPopup('<h1>{{ $dealer['name']}}</h1><p>{{ $dealer['country']}} {{ $dealer['address']}}<br>{{$dealer['phone']}}</p>')
      }
      @endforeach
      @endif

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);
      if(open)
        map.openPopup(open, center, { elevation: 500 });
  }

  function successGPS(position) {
    const lat = position.coords.latitude;
    const lng = position.coords.longitude;
    center = [lat, lng];

    var rad = $('#radius').val();
    setInterval(makeMap(rad, center),1000);
  };

  navigator.geolocation.getCurrentPosition(successGPS);
</script>

</html>
