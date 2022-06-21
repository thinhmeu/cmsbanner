@extends(TEMPLATE)
@section('content')
{{--    add lib fb cmt non amp--}}
    @if(!IS_AMP)
    <div id="fb-root"></div>
    <script async crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v11.0&appId=268051391138932&autoLogAppEvents=1" nonce="gYEerjW0"></script>
    @endif

    @include('web.block._schema_post')

    <main class="container mt-4">
        <div class="d-flex flex-wrap">
            <div class="col-12 col-lg-8 px-0 mb-5">
                @if(!empty($breadCrumb))
                    {!! getBreadcrumb($breadCrumb) !!}
                @endif
                <h1 class="font-weight-bold font-24">{!! $oneItem->title !!}</h1>
                <div class="d-flex justify-content-between mb-3 flex-column flex-lg-row">
                    @if(!empty($category))
                        <div class="d-flex align-items-center">
                            <a href="{{ getUrlCate($category) }}" class="text-black6 text-uppercase font-10 bg-grey7 py-1 px-2 category-tag text-decoration-none">{{ $category->title }}</a>
                        </div>
                    @endif
                    <div class="text-black6">
                        {!! initRating($oneItem->slug, 0, $oneItem->title, true) !!}
                    </div>
                </div>
                @if(!empty($optional->name))
                    <div class="d-flex flex-wrap border rounded shadow bg-white py-3 py-lg-0 px-0 px-lg-3 text-center mb-4 position-relative">
                        <div class="col-12 col-lg-9 d-flex mb-2 mt-0 my-lg-3 flex-column flex-lg-row">
                            <a href="{{ getUrlPost($oneItem) }}" class="d-lg-flex align-items-lg-center" rel="nofollow">
                                {!! get_thumbnail($optional->logo, 80, 80, '', "$optional->name - $optional->description", 'fixed') !!}
                            </a>
                            <div class="ml-lg-5 text-center text-lg-left py-3">
                                <h3 class="font-weight-600 text-black3 font-16">{{ $optional->name }} - {{ $optional->description }}</h3>
                                <div class="font-15 text-black3 d-flex d-lg-block justify-content-center">{!! initRating($oneItem->slug) !!}</div>
                            </div>
                        </div>
                        @if(!IS_MOBILE && !IS_AMP)
                        <div class="col-12 col-lg-3 d-flex flex-row flex-lg-column justify-content-center py-0 py-lg-2 pl-lg-0">
                            <a href="{{ $optional->link }}" class="bg-green1 rounded-pill text-decoration-none px-lg-4 py-2 mb-lg-2 flex-grow-1 flex-lg-grow-0 mr-2 mr-lg-0 text-white">Tải ngay</a>
                            <a href="{{ getUrlPost($oneItem) }}" class="bg-green1 rounded-pill text-decoration-none px-lg-4 py-2 flex-grow-1 flex-lg-grow-0 text-white">Chơi ngay</a>
                        </div>
                        @else
                        <div class="col-12 py-0 py-lg-2 pl-lg-0">
                            <a href="{{ $optional->link }}" class="col-12 bg-green1 rounded-pill text-decoration-none px-lg-4 py-2 text-white d-block my-2">Tải ngay</a>
                            <a href="{{ getUrlPost($oneItem) }}" class="col-12 bg-green1 rounded-pill text-decoration-none px-lg-4 py-2 text-white d-block my-2">Chơi ngay</a>
                        </div>
                        @endif
                    </div>
                @endif
                <section class="font-15">
                    {!! $oneItem->description !!}
                </section>
                <section class="mb-5 w-100 text-black3 post-content">
                    @if(IS_AMP)
                        {!! add_amp_to_url(tableOfContent($oneItem->content)) !!}
                    @else
                        {!! tableOfContent($oneItem->content) !!}
                    @endif
                </section>
                @if(!empty($user->author))
                <section class="w-100 p-3 d-flex" style="background-color: #f6f6f6">
                    {!! get_thumbnail($user->thumbnail, 120, 120, '') !!}
                    <div class="pl-2 pl-lg-5 d-flex align-items-center">
                        <ul class="list-unstyled mb-0">
                            <li class="font-weight-bold">
                                <a class="text-decoration-none text-dark" href="{{ getUrlAuthor($user) }}" title="{{ $user->author }}">{{ $user->author }}</a>
                            </li>
                            @if (!empty($user->description))
                            <li>{!! $user->description !!}</li>
                            @endif
                            {{-- @php $optional = json_decode($user->optional) @endphp --}}
                            <li>Kết nối với tác giả:
                                <div class="d-lg-inline">
                                {{-- <a target="_blank" rel="nofollow" href="{{ $optional->facebook }}"><i class="icon-facebook ml-2"></i></a>
                                <a target="_blank" rel="nofollow" href="{{ $optional->twitter }}"><i class="icon-twitter ml-4"></i></a>
                                <a target="_blank" rel="nofollow" href="{{ $optional->instagram }}"><i class="icon-instagram ml-4"></i></a>
                                <a target="_blank" rel="nofollow" href="{{ $optional->reddit }}"><i class="icon-reddit ml-4"></i></a> --}}
                                </div>
                            </li>
                        </ul>
                    </div>
                </section>
                @endif  
                <?php if (!IS_AMP) :?>
                <div id="fb-comment" class="my-3 bg-white">
                    <div class="fb-comments" data-href="https://doithuongthecao.com/" data-width="100%" data-numposts="5" data-order-by="reverse_time"></div>
                </div>
                <?php else: ?>
                <div id="fb-comment" class="my-3">
                    <amp-facebook-comments
                        width="486"
                        height="657"
                        layout="responsive"
                        data-numposts="5"
                        data-href="https://doithuongthecao.com/"
                    >
                    </amp-facebook-comments>
                </div>
                
                <?php endif; ?>
                <section class="row mx-0 box-news-blue">
                    <div class="sidebar-title mb-3 border-title-black1 w-100">
                        <h4 class="font-14 mb-0">
                            <a href="{{ getUrlLink('/') }}" class="position-relative text-white bg-black1 d-inline-block pt-2 pb-1 pl-2 pr-4">Bài viết liên quan</a>
                        </h4>
                    </div>    
                         
                        @if(IS_AMP)
                            @if(strpos(Request::root(), env('DOMAIN')) === false)
                                @php $src = '//doithuongthecao.test' @endphp
                            @else
                                @php $src = url('/') @endphp
                            @endif
                            <div class="col-12 px-0">
                                <amp-list width="auto" height="{{count($related_post) * 113}}" layout="fixed-height"
                                          src="{{$src}}/ajax-load-more-post-amp?category_id={{$category->id ?? ''}}&limit={{$limit}}&page={{$page}}" binding="refresh"
                                          load-more="manual" load-more-bookmark="next">
                                    <template type="amp-mustache">
                                        <article class="col-12 col-lg-4 d-flex flex-wrap mb-3 px-0">
                                            <div class="col-4 col-lg-12 px-0 position-relative">
                                                <a href="@{{ slug }}" rel="nofollow">
                                                    <amp-img src="@{{ thumbnail }}" alt="@{{ title }}" layout="responsive" width="269" height="187"></amp-img>
                                                </a>
                                                <a rel="nofollow" class="category-badge font-10 font-weight-600 text-white text-uppercase bg-black1 px-2 py-1 position-absolute left-0 bottom-0 text-decoration-none" href="@{{category_slug}}" title="@{{category_title}}">@{{category_title}}</a>
                                            </div>
                                            <h3 class="col-8 col-lg-12 font-17 font-lg-13 font-weight-500 pl-3 pl-lg-0 pr-0 mt-0 mt-lg-2">
                                                <a rel="nofollow" class="text-black3 text-decoration-none box-news-title font-17" href="@{{ slug }}">@{{ title }}</a>
                                            </h3>
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
                            </div>
                        @else
                            <div class="row" id="ajax_content">
                            @foreach($related_post as $value)
                                <article class="col-12 col-lg-4 d-flex flex-wrap mb-3">
                                    <div class="col-4 col-lg-12 px-0 position-relative">
                                        <a href="{{getUrlPost($value)}}" title="{{$value->title}}" rel="nofollow">{!! get_thumbnail($value->thumbnail, '', '', 'img-fluid', $value->title) !!}</a>
                                        @if(!empty($category))
                                            <a class="category-badge font-10 font-weight-600 text-white text-uppercase bg-black1 px-2 py-1 position-absolute left-0 bottom-0 text-decoration-none" href="{{ getUrlCate($category) }}" title="{{$category->title}}">{{$category->title}}</a>
                                        @endif
                                    </div>
                                    <h3 class="col-8 col-lg-12 font-17 font-lg-13 font-weight-500 pl-3 pl-lg-0 pr-0 mt-0 mt-lg-2">
                                        <a href="{{getUrlPost($value)}}" title="{{$value->title}}" class="text-black3 text-decoration-none box-news-title">{{$value->title}}</a>
                                    </h3>
                                </article>
                            @endforeach
                            </div>
                            <div class="w-100 d-flex justify-content-center">
                                <a href="{{IS_AMP ? '#' : 'javascript:;'}}" class="border px-5 py-2 font-11 text-decoration-none view-more load-more">XEM THÊM</a>
                            </div>
                        @endif
                </section>
            </div>
            @include('web.block._sidebar')
        </div>
    </main>
@endsection
