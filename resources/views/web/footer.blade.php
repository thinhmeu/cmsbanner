<footer class="container-fluid px-0">
    <div class="bg-blue4 py-5">
        <div class="container social-footer d-flex justify-content-center mb-3">
            <a href="{{getSiteSetting('site_pinterest')}}" rel="nofollow" class="mx-2 text-white bg-black6 rounded-circle p-3 d-flex align-items-center justify-content-center" target="_blank">
                <span class="icon-pinterest-p"></span>
            </a>
            <a href="{{getSiteSetting('site_twitter')}}" rel="nofollow" class="mx-2 text-white bg-black6 rounded-circle p-3 d-flex align-items-center justify-content-center" target="_blank">
                <span class="icon-twitter"></span>
            </a>
            <a href="{{getSiteSetting('site_reddit')}}" rel="nofollow" class="mx-2 text-white bg-black6 rounded-circle p-3 d-flex align-items-center justify-content-center" target="_blank">
                <span class="icon-reddit"></span>
            </a>
            <a href="{{getSiteSetting('site_tumblr')}}" rel="nofollow" class="mx-2 text-white bg-black6 rounded-circle p-3 d-flex align-items-center justify-content-center" target="_blank">
                <span class="icon-tumblr"></span>
            </a>
            <a href="{{getSiteSetting('site_instagram')}}" rel="nofollow" class="mx-2 text-white bg-black6 rounded-circle p-3 d-flex align-items-center justify-content-center" target="_blank">
                <span class="icon-instagram"></span>
            </a>
        </div>
        <div class="container text-white text-center">
            <a href="/" class="font-14 font-weight-600 text-uppercase text-white text-decoration-none mr-3">TRANG CHỦ</a>
            @if(!empty($MenuFooter))
                @foreach($MenuFooter as $value)
                    @if(checklinkOut($value['url']))
                        <a href="{{ $value['url'] }}" class="font-14 font-weight-600 text-uppercase text-white text-decoration-none mr-3" rel="nofollow" target="_blank">{{ $value['name'] }}</a>
                    @else
                        <a href="{{ getUrlLink($value['url']) }}" class="font-14 font-weight-600 text-uppercase text-white text-decoration-none mr-3">{{ $value['name'] }}</a>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
    <div class="bg-black5 py-3">
        <div class="container font-13 text-grey3 text-center">
            <span class="d-inline-block">{!! getSiteSetting('site_copyright') !!}</span>
            <a href="/">
                {!! get_thumbnail('/web/images/dmca-badge-w100-2x1-04.png', 100, 50, '', 'DMCA.com Protection Status', 'fixed', true, false) !!}
            </a>
            <span>Liên hệ quảng cáo: <a rel="nofollow" href="mailto:{{getSiteSetting('site_email')}}">{{getSiteSetting('site_email')}}</a></span>
        </div>
    </div>
</footer>

<div class="fixed-bottom catfish d-none d-lg-flex flex-column align-items-center mx-auto">
    {!! getBannerPc('catfish-pc') !!}
</div>
<div class="fixed-bottom d-block d-lg-none">
    {!! getBannerMobile('catfish-mobile') !!}
</div>
<div class="float-left position-fixed left-0 " style="top:110px;z-index:9999;">
    {!! getBannerPc('float-left') !!}
</div>
<div class="float-right position-fixed right-0 " style="top:110px;z-index:9999;">
    {!! getBannerPc('float-right') !!}
</div>
