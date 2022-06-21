@php
    $category = $post;
    $post = $post->item;
@endphp

<section class="box-news-{{ $color }} mb-3">
    <div class="mb-4 sidebar-title border-title-blue4 w-100">
        <h2 class="mb-0 font-14">
            <a href="{{ getUrlLink($category->url) }}" class="text-white text-decoration-none d-inline-block bg-blue4 position-relative pt-2 pb-1 pl-2 pr-4">{{ $category->name }}</a>
        </h2>
    </div>
    <div class="row">
        @foreach($post as $key => $value)
            @if($key >= 5) @php break @endphp @endif
            <article class="col{{IS_MOBILE ? '-12' : ''}} d-flex flex-wrap mb-3">
                <div class="col-4 col-lg-12 px-0 position-relative">
                    <a href="{{getUrlPost($value)}}" title="{{$value->title}}" rel="nofollow">{!! get_thumbnail($value->thumbnail, '', '', 'img-fluid', $value->title) !!}</a>
                    <a class="category-badge font-10 font-weight-600 text-white text-uppercase bg-black1 px-2 py-1 position-absolute left-0 bottom-0 text-decoration-none" href="{{ getUrlCate($value->category[0]) }}" title="{{$value->category[0]->title}}">{{$value->category[0]->title}}</a>
                </div>
                <h3 class="col-8 col-lg-12 font-14 font-lg-15 font-weight-500 pl-3 pl-lg-0 pr-0 mt-0 mt-lg-2">
                    <a href="{{getUrlPost($value)}}" title="{{$value->title}}" class="text-black3 text-decoration-none box-news-title max-line-2">{{$value->title}}</a>
                </h3>
            </article>
            @php unset($post[$key]) @endphp
        @endforeach
    </div>
    <div class="row">
        @foreach($post as $key => $value)
            <article class="col{{IS_MOBILE ? '-12' : ''}} d-flex flex-wrap mb-3">
                <div class="col-4 col-lg-12 px-0 position-relative">
                    <a href="{{getUrlPost($value)}}" title="{{$value->title}}" rel="nofollow">{!! get_thumbnail($value->thumbnail, '', '', 'img-fluid', $value->title) !!}</a>
                    <a class="category-badge font-10 font-weight-600 text-white text-uppercase bg-black1 px-2 py-1 position-absolute left-0 bottom-0 text-decoration-none" href="{{ getUrlCate($value->category[0]) }}" title="{{$value->category[0]->title}}">{{$value->category[0]->title}}</a>
                </div>
                <h3 class="col-8 col-lg-12 font-14 font-lg-15 font-weight-500 pl-3 pl-lg-0 pr-0 mt-0 mt-lg-2">
                    <a href="{{getUrlPost($value)}}" title="{{$value->title}}" class="text-black3 text-decoration-none box-news-title max-line-2">{{$value->title}}</a>
                </h3>
            </article>
        @endforeach
    </div>
</section>
