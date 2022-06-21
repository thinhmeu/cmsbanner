@php
$ver = '0.16';
$is_home = getCurrentController() == 'home';
@endphp
<!DOCTYPE html>
<html lang="vi-VN">
<head>
    <meta charset="utf-8">
    <meta name="google-site-verification" content="IKyCttAzwuVVR98eTaFE7GwGsQP5dMNapEEBfn92IfE" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @if(!empty($seo_data['index']))
    <meta name="robots" content="{{$seo_data['index']}}">
    @else
    <meta name="robots" content="index, follow">
    @endif
    <title>{{$seo_data['meta_title'] ?? ''}}</title>
    @if(!empty($seo_data['meta_keyword']))
        <meta name="keywords" content="{{$seo_data['meta_keyword']}}">
    @endif
    <meta name="description" content="{{$seo_data['meta_description'] ?? ''}}">
    <link rel="canonical" href="{{$seo_data['canonical'] ?? ''}}" />

    @if(!empty($seo_data['amphtml']))
        <link rel="amphtml" href="{{$seo_data['amphtml']}}">
    @endif

    <meta name="google-site-verification" content="aEco6iEgzSJvRYBE6LCh75TMZHKoyJMIjat-lz7EzVg" />

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="{{$seo_data['meta_title'] ?? ''}}" />
    <meta name="twitter:description" content="{{$seo_data['meta_description'] ?? ''}}" />
    <meta name="twitter:site" content="@doithuongthecao" />
    <meta name="twitter:url" content="{{$seo_data['canonical'] ?? ''}}" />
    @if(!empty($seo_data['site_image']))
        <meta name="twitter:image" content="{{url($seo_data['site_image'])}}" />
    @endif

    <!-- facebook meta tag -->
    <meta property="og:url" content="{{$seo_data['canonical'] ?? ''}}" />
    @if($is_home)
    <meta property="og:type" content="website" />
    @else
    <meta property="og:type" content="article" />
    @endif
    <meta property="og:title" content="{{$seo_data['meta_title'] ?? ''}}" />
    <meta property="og:description" content="{{$seo_data['meta_description'] ?? ''}}" />
    @if(!empty($seo_data['site_image']))
        <meta name="og:image" content="{{url($seo_data['site_image'])}}" />
    @endif
    @if(!empty($seo_data['published_time']))
        <meta property="article:published_time" content="{{$seo_data['published_time']}}" />
    @endif
    @if(!empty($seo_data['modified_time']))
        <meta property="article:modified_time" content="{{$seo_data['modified_time']}}" />
    @endif
    @if(!empty($seo_data['updated_time']))
        <meta property="article:updated_time" content="{{$seo_data['updated_time']}}" />
    @endif
    {!! getSiteSetting('meta_head') ?? '' !!}
    
    <link rel="shortcut icon" href="{{ url('web/images/favicon.png?1') }}" />
    <link rel="apple-touch-icon" href="{{ url('web/images/favicon.png?1') }}" />

    <!-- jQuery library -->
    {{--<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>--}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        <?php $css = file_get_contents('web/css/main.css');
        echo $css?>
    </style>
    @if(!empty($schema))
        {!!$schema!!}
    @endif
</head>
<body>
@include('web.header')
@yield('content')
@include('web.footer')

@php
    if (IS_MOBILE)
        $popup = getBanner('popup-mobile');
    else
        $popup = getBanner('popup-pc');
@endphp
@if ($popup)
    <div id="adsModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-none" style="background: none;">
                <div class="modal-body mx-auto">
                    @if(IS_MOBILE)
                    <div class="text-center" style="max-width: 300px">{!! $popup !!}</div>
                    @else
                    <div class="text-center">{!! $popup !!}</div>
                    @endif
                    <span style="right: 14px;width: 30px;background: rgba(169,169,169,0.8);" class="d-none p-2 position-absolute text-center" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></span>
                </div>
            </div>
        </div>
    </div>
@endif

<script defer src="/web/js/non-critical.min.js?{{ $ver }}"></script>

{{--search cse--}}
<script defer src="https://cse.google.com/cse.js?cx=0909411d6db053d12"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-199950707-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-199950707-1');
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function () {
            loadFbComment();
        }, 3000);
    });
    function loadFbComment() {
        let js = document.createElement('script');
        js.src = 'https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v10.0';
        js.async = '';
        js.crossorigin = 'anonymous';
        js.nonce = 'qmS9eb4o';
        document.body.appendChild(js);
    }
    document.addEventListener('DOMContentLoaded', function() {
        let key = 'checkPopunder';
        let executed = false;

        if (!sessionStorage.getItem(key)) {
            sessionStorage.setItem(key, 1);
            $(document).on('click', 'a:not([target="_blank"][rel="nofollow"])', function(e) {
                if (!executed) {
                    executed = true;
                    e.preventDefault();
                    openTab(this);
                }
            });
        }
    });
    function openTab(_this) {
        _this = $(_this);
        let currentUrl = _this.attr('href');
        let urlAds = "<?= getBanner('popunder') ?>";
        if (urlAds !== '') {
            window.open(urlAds,"_blank");
            window.location = currentUrl;
            /*window.open(currentUrl,"_blank");
            window.location = urlAds;*/
        } else {
            window.location = currentUrl;
        }
    }
</script>
</body>
</html>
