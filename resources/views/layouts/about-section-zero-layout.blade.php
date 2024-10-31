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

</html>
