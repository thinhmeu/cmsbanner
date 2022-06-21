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
            @if($key >= 1) @php break @endphp @endif
            <article class="col-12 col-lg-4 d-flex flex-wrap mb-3">
                <div class="col-lg-12 px-0 position-relative">
                    <a href="{{getUrlPost($value)}}" title="{{$value->title}}" rel="nofollow">{!! get_thumbnail($value->thumbnail, '', '', 'img-fluid', $value->title) !!}</a>
                    <a class="category-badge font-10 font-weight-600 text-white text-uppercase bg-black1 px-2 py-1 position-absolute left-0 bottom-0 text-decoration-none" href="{{ getUrlCate($value->category[0]) }}" title="{{$value->category[0]->title}}">{{$value->category[0]->title}}</a>
                </div>
                <h3 class="col-lg-12 font-21 font-weight-500 px-0 mt-2">
                    <a href="{{getUrlPost($value)}}" title="{{$value->title}}" class="text-black3 text-decoration-none box-news-title">{{$value->title}}</a>
                </h3>
                <p class="text-grey4 font-13 max-line-5">{!!  $value->content->description ?? get_limit_content($value->content, 200) !!}</p>
            </article>
            @php unset($post[$key]) @endphp
        @endforeach
        <div class="col-12 col-lg-8 px-0 pl-lg-2 d-flex flex-wrap">
            @foreach($post as $key => $value)
                @if($key >= 9) @php break @endphp @endif
                <article class="col-12 col-lg-6 d-flex flex-wrap mb-3">
                    <div class="col-4 px-0 position-relative">
                        <a href="{{getUrlPost($value)}}" title="{{$value->title}}" rel="nofollow">{!! get_thumbnail($value->thumbnail, '', '', 'img-fluid', $value->title) !!}</a>
                    </div>
                    <h3 class="col-8 font-14 font-weight-500 pl-3 pr-0">
                        <a href="{{getUrlPost($value)}}" title="{{$value->title}}" class="text-black3 text-decoration-none box-news-title">{{$value->title}}</a>
                    </h3>
                </article>
            @endforeach
        </div>
    </div>
</section>
