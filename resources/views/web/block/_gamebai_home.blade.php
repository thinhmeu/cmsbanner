@php $is_home = getCurrentController() == 'home' @endphp
<div class="col-12 mb-2 game-bai pt-4">
    @if($is_home)<h2 class="row font-22 font-weight-600 mb-4 text-black3 position-relative pl-3">TOP GAME ĐỔI THƯỞNG</h2>@endif
    @php $i = 0 @endphp
    @foreach($data as $value)
        @if(empty($value->post->optional)) @continue @endif
        @php $content = json_decode($value->post->optional); @endphp
        <div class="row border rounded shadow bg-white py-3 text-center mb-4 position-relative">
            <span class="medal position-absolute text-white text-center font-14 rounded"><?=$i+1?></span>
            <div class="col-4 col-lg-2 d-flex justify-content-center mb-3">
                <a href="{{ getUrlPost($value->post) }}" rel="nofollow" class="game-bai-thumb">
                    @if(IS_MOBILE)
                        {!! get_thumbnail($content->logo, 80, 80, 'img-fluid', $content->name, 'fixed', true) !!}
                    @else
                        {!! get_thumbnail($content->logo, 200, 200, 'img-fluid', $content->name, 'fixed', true) !!}
                    @endif
                </a>
            </div>
            <div class="col-8 col-lg-4 my-3 border-lg-left">
                <h3 class="font-weight-600 text-black3 font-20 text-left text-lg-center">{{ $content->name }}</h3>
                <div class="font-14 text-black3 d-flex d-lg-none">{!! initRating($value->post->slug) !!}</div>
                <p class="mb-0 font-14 text-black3 d-none d-lg-block">{!! $content->description !!}</p>
            </div>
            <div class="col-4 border-left border-right my-3 d-none d-lg-block">
                <div class="font-14 text-black3 d-flex justify-content-center">{!! initRating($value->post->slug) !!}</div>
                <a href="/gift-code/" rel="nofollow" class="text-decoration-none">
                    <span class="font-weight-600 font-14 text-red1">Nhận gift Code</span>
                    {!! get_thumbnail('/web/images/hot.gif', 22, 11, 'img-fluid', 'hot', 'fixed', true, false) !!}
                </a>
            </div>
            <div class="{{$is_home ? 'font-16' : 'font-14 px-2 px-lg-1'}} col-12 col-lg-2 d-flex flex-row flex-lg-column justify-content-center py-0 py-lg-2">
                <a href="{{ $content->link }}" target="_blank" rel="nofollow"
                   class="bg-green1 rounded-pill text-decoration-none py-2 mb-lg-2 flex-grow-1 flex-lg-grow-0 mr-2 mr-lg-0 text-white font-14">
                    <span class="icon-checkmark-outline"></span> Cược ngay</a>
                <a href="{{ getUrlPost($value->post) }}" rel="nofollow"
                   class="bg-grey1 rounded-pill text-decoration-none py-2 flex-grow-1 flex-lg-grow-0 text-white font-14">
                    <span class="icon-arrow-outline-right"></span> Xem review</a>
            </div>
        </div>
        @php $i++ @endphp
    @endforeach
</div>
