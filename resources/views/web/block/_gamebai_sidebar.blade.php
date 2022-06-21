<div class="col-12 mb-2 game-bai">
    <h2 class="row font-20 font-weight-600 mb-4 text-black3 position-relative pl-3">TOP GAME ĐỔI THƯỞNG</h2>
    @php $i = 0 @endphp
    @foreach($game_bai as $value)
        @if(empty($value->post->optional)) @continue @endif
        @php $content = json_decode($value->post->optional); @endphp
        <div class="row border rounded shadow bg-white py-3 px-2 text-center mb-4 position-relative d-block">
            <span class="medal position-absolute text-white text-center font-14 rounded"><?=$i+1?></span>
            <a href="{{ getUrlPost($value->post) }}" rel="nofollow">
                {!! get_thumbnail($content->logo, 80, 80, 'img-fluid mb-2', $content->name, 'fixed') !!}
            </a>
            <h3 class="font-weight-bold text-black3 font-18 mb-2">{{ $content->name }}</h3>
            <h3 class="font-14 text-black3 font-16 mb-0">{!! $content->description !!}</h3>
            <div class="font-14 text-black3 d-flex justify-content-center">{!! initRating($value->post->slug) !!}</div>
            <a href="/gift-code/" rel="nofollow" class="text-decoration-none">
                <span class="font-weight-600 font-14 text-red1">Nhận gift Code</span>
                {!! get_thumbnail('/web/images/hot.gif', 22, 11, 'img-fluid', 'hot', 'fixed', false, false) !!}
            </a>
            @if(!IS_MOBILE && !IS_AMP)
            <div class="col-12 d-flex flex-row mt-2">
                <a href="{{ $content->link }}" target="_blank" rel="nofollow"
                   class="col mr-1 bg-green1 rounded text-decoration-none px-4 py-2 text-white font-14">Cược ngay</a>
                <a href="{{ getUrlPost($value->post) }}" rel="nofollow"
                   class="col ml-1 bg-green1 rounded text-decoration-none px-4 py-2 text-white font-14">Xem review</a>
            </div>
            @else
            <div class="col-12 mt-2">
                <a href="{{ $content->link }}" target="_blank" rel="nofollow"
                   class="col mx-1 my-2 bg-green1 rounded-pill text-decoration-none px-4 py-2 text-white font-14 d-block">Cược ngay</a>
                <a href="{{ getUrlPost($value->post) }}" rel="nofollow"
                   class="col mx-1 my-2 bg-green1 rounded-pill text-decoration-none px-4 py-2 text-white font-14 d-block">Xem review</a>
            </div>

            @endif
        </div>
        @php $i++ @endphp
    @endforeach
</div>
