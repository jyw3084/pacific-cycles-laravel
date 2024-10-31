<footer class="page-footer font-small" style="min-height: 100px">
    <div class="container">
        <div class="row pt-5">
@if($footer && $footer->content)
{!! $footer->content !!}
@endif
        </div>
        <div class="row pb-5 pt-4">
            <div class="col-md-3 offset-9 text-right">
                @if($footer && $footer->facebook_url!="")
                    <a href="{{ $footer->facebook_url }}"><div class="social-icon"><i class="icofont-facebook"></i></div></a>
                @endif
                    @if($footer && $footer->instagram_url!="")
                        <a href="{{ $footer->instagram_url }}"><div class="social-icon"><i class="icofont-instagram"></i></div></a>
                    @endif
                    @if($footer && $footer->youtube_url!="")
                        <a href="{{ $footer->youtube_url }}"><div class="social-icon"><i class="icofont-youtube-play"></i></div></a>
                    @endif
            </div>
        </div>
        <div class="footer-copyright text-center py-5 pb-5">&copy; 2021 PACIFIC CYCLES ALL RIGHT RESERVED</div>
    </div>
</footer>
