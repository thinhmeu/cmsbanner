@if(!IS_AMP)
    <div class="d-flex flex-wrap align-items-center justify-content-center">
        <input class="d-none" type="range" value="{{ !empty($avg) ? $avg : 5 }}" step="0.25" id="{{ $url }}">
        <div class="rateit d-inline-block position-relative" data-rateit-backingfld="#{{ $url }}" data-rateit-resetable="false"
             data-rateit-ispreset="true" data-rateit-min="0" data-rateit-max="5" data-rateit-mode="font" data-rateit-icon=""
             data-url="{{ $url }}"
            @if(!empty($readonly)) data-rateit-readonly="true" @endif >
        </div>
        <span class="danhgia ml-1">
                <span class="avg-rate">{{ $avg }}</span>/<span>5</span> - (<span class="count-rate">{{ $count }}</span> bình chọn)</span>
        </span>
    </div>
@else
    @php $random = "rd".random_int(0, 1000) @endphp
    <form id="star{{$random}}"
          method="post"
          action-xhr="//doithuongthecao.com/rating/rating" class="d-flex flex-wrap align-items-center date"
          target="_blank"
          on="submit-success:AMP.setState({ '{{$random}}avg': event.response.avg, '{{$random}}count': event.response.count, '{{$random}}messageRating': event.response.message})">
        <fieldset class="star">
            @for ($i = 5; $i >= 1; $i--)
            <input name="star"
                   type="radio"
                   id="{{$random.$i}}"
                   value="{{$i}}"
                   {{$i == (int)$avg ? 'checked="checked"' : ''}}
                   on="change:star{{$random}}.submit">
            <label for="{{$random.$i}}"
                   title="{{$i}} stars" class="mb-0">☆</label>
            @endfor
        </fieldset>
        <input type="text" name="url" value="{{$url}}" hidden>

        <span class="text-gray2 ml-2">
        <span [text]="{{$random}}avg ? {{$random}}avg : {{$avg}}">{{$avg}}</span>/5 - (<span [text]="{{$random}}count ? {{$random}}count : {{$count}}">{{$count}}</span> bình chọn)
    </span>
        @if(!empty($showMessage))
            <div submit-success class="w-10">
                <template type="amp-mustache">
                    <p>@{{message}}</p>
                </template>
            </div>
            <div submit-error class="w-10">
                <template type="amp-mustache">
                    Đã xảy ra lỗi
                </template>
            </div>
        @endif
    </form>
@endif
@if($schema)
    <script type="application/ld+json"> {
        "@context": "https://schema.org/",
        "@type": "AggregateRating",
        "ratingValue": "{{ $avg }}",
        "bestRating": "5",
        "ratingCount": "{{ $count }}",
        "itemReviewed": {
            "@type": "CreativeWorkSeries",
            "name": "{{ $title }}"
        }
    }
</script>
@endif
