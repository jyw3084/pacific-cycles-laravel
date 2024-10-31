<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="lang" content="{{ str_replace(['en', 'zh-TW'], ['en_US', 'zh_TW'], app()->getLocale()) }}">
@if(request()->segment(1) == 'store')
    {!! $head->head ?? '' !!}
@else
    {!! $head->content ?? '' !!}
@endif
<link rel="stylesheet" type="text/css" href="{{asset('/css/bootstrap.min.css')}}">
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
<link rel="stylesheet" type="text/css" href="/css/style.css?{{ time() }}">
<link rel="stylesheet" type="text/css" href="/icofont/icofont.min.css?{{ time() }}">

