@extends(TEMPLATE)
@section('content')
    <main class="container mt-4">
        <div class="d-flex flex-wrap">
            @if(!empty($breadCrumb))
                {!! getBreadcrumb($breadCrumb) !!}
            @endif
            <div class="col-12 col-lg-8 px-0">
                <h2 class="font-20 text-black2 font-weight-bold text-uppercase mb-0">{{ $oneItem->title }}</h2>
                @if(!empty($cse))
                    {!!$cse!!}
                @endif

                @if(IS_AMP)
                    @if(strpos(Request::root(), env('DOMAIN')) === false)
                        @php $src = '//doithuongthecao.test' @endphp
                    @else
                        @php $src = url('/') @endphp
                    @endif
                    <amp-list width="auto" height="{{count($post) * 113}}" layout="fixed-height"
                              src="{{$src}}/ajax-load-more-post-amp?category_id={{$oneItem->id}}&limit={{$limit}}&page={{$page}}" binding="refresh"
                              load-more="manual" load-more-bookmark="next">
                        <template type="amp-mustache">
                            <article class="d-flex mb-4">
                                <div class="col-4 px-0">
                                    <a href="@{{ slug }}" rel="nofollow">
                                        <amp-img src="@{{ thumbnail }}" alt="@{{ title }}" layout="responsive" width="269" height="187"></amp-img>
                                    </a>
                                </div>
                                <div class="col-8 pl-3 pr-0">
                                    <h3 class="text-title mb-3">
                                        <a rel="nofollow" class=" text-decoration-none text-black1" href="@{{ slug }}">@{{ title }}</a>
                                    </h3>
                                </div>
                            </article>
                        </template>
                        <div fallback>
                            FALLBACK
                        </div>
                        <div placeholder>
                            Đang tải...
                        </div>
                        <amp-list-load-more load-more-failed>
                            ERROR
                        </amp-list-load-more>
                        <amp-list-load-more load-more-end>
                            <p class="text-center">Đã tải hết</p>
                        </amp-list-load-more>
                        <amp-list-load-more load-more-button>
                            <!-- My custom see more button -->
                            <div class="category-load-more text-center overflow-hidden">
                                <button class="d-block border px-5 py-2 font-11 text-decoration-none view-more load-more position-relative mx-auto">XEM THÊM</button>
                            </div>
                        </amp-list-load-more>
                    </amp-list>
                @else
                    <section class="row" id="ajax_content">
                        @foreach($post as $value)
                            <article class="col-12 d-flex mb-4">
                                <div class="col-3 px-0 mb-3">
                                    <a href="{{ getUrlPost($value) }}">
                                        {!! get_thumbnail($value->thumbnail, 269, 187, 'img-fluid', $value->title, 'responsive', true) !!}
                                    </a>
                                </div>
                                <div class="col-9 pl-3 pr-0">
                                    <h3 class="mb-3">
                                        <a class="font-weight-500 text-decoration-none text-black3 font-14 font-lg-22" href="{{ getUrlPost($value) }}">{!! $value->title !!}</a>
                                    </h3>
                                    <div class="text-grey4 font-13 d-none d-lg-block max-line-2">{!! !empty($value->description) ? $value->description : get_limit_content(trim($value->content), 200) !!}</div>
                                </div>
                            </article>
                        @endforeach
                    </section>
                @endif

                @if(!IS_AMP)
                <div class="category-load-more text-center overflow-hidden">
                    <a href="javascript:;" class="d-block border px-5 py-2 font-11 text-decoration-none view-more load-more position-relative mx-auto">XEM THÊM</a>
                </div>
                @endif
                <h1 class="font-36 font-weight-bold mt-5">{!! $oneItem->meta_title !!}</h1>
                <section class="mb-5 w-100 post-content overflow-auto" style="height: 200px">
                    {!! $oneItem->content !!}
                </section>
            </div>
            @include('web.block._sidebar')
        </div>
    </main>
@endsection
