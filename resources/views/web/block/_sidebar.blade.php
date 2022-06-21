<aside class="col-12 col-lg-4 px-0 pl-lg-4">
    {!! initGameBaiSidebar() !!}
    <section class="row">
        <div class="col-12 mb-4">
            <div class="sidebar-title mb-3 border-title-black1">
                <h3 class="font-14 mb-0">
                    <span class="position-relative text-white bg-black1 d-inline-block pt-2 pb-1 pl-2 pr-4">Bài mới</span>
                </h3>
            </div>
            @if(!empty($new_post))
                @php $value = $new_post[0]; @endphp
                <article class="col-12 px-0">
                    <a href="{{ getUrlPost($value) }}" rel="nofollow">
                        {!! get_thumbnail($value->thumbnail, 350, 250, 'img-fluid', $value->title, 'responsive', true) !!}
                    </a>
                    <h3 class="font-21 my-2">
                        <a class="font-weight-500 text-justify line-height-15 text-decoration-none text-black3" href="{{ getUrlPost($value) }}">{!! $value->title !!}</a>
                    </h3>
                    <div class="text-grey4 font-13 mb-3 max-line-2">{!! !empty($value->description) ? $value->description : get_limit_content(trim($value->content), 200) !!}</div>
                </article>
                @php unset($new_post[0]);  @endphp
                @foreach($new_post as $value)
                    <article class="col-12 mb-3 d-flex px-0">
                        <div class="col-4 px-0">
                            <a href="{{ getUrlPost($value) }}" rel="nofollow">
                                {!! get_thumbnail($value->thumbnail, 350, 250, 'img-fluid', $value->title, 'responsive', true) !!}
                            </a>
                        </div>
                        <div class="col-8 pr-0">
                            <h3 class="font-14 my-2">
                                <a class="font-weight-500 text-justify line-height-15 text-decoration-none text-black3" href="{{ getUrlPost($value) }}">{!! $value->title !!}</a>
                            </h3>
                        </div>
                    </article>
                @endforeach
            @endif
        </div>
    </section>
    @if(!empty($sidebar))
        @foreach($sidebar as $item)
            <section class="row">
                <div class="col-12 mb-4">
                    <div class="sidebar-title mb-3 border-title-black1">
                        <h3 class="font-14 mb-0">
                            <span class="position-relative text-white bg-black1 d-inline-block pt-2 pb-1 pl-2 pr-4">{{ $item->name }}</span>
                        </h3>
                    </div>
                    @if(!empty($item->item))
                        @foreach($item->item as $value)
                            <article class="col-12 px-0">
                                <a href="{{ getUrlPost($value) }}" rel="nofollow">
                                    {!! get_thumbnail($value->thumbnail, 350, 250, 'img-fluid', $value->title, 'responsive', true) !!}
                                </a>
                                <h3 class="font-21 my-2">
                                    <a class="font-weight-500 text-justify line-height-15 text-decoration-none text-black3" href="{{ getUrlPost($value) }}">{!! $value->title !!}</a>
                                </h3>
                                <div class="text-grey4 font-13 mb-3 max-line-2">{!! !empty($value->description) ? $value->description : get_limit_content(trim($value->content), 200) !!}</div>
                            </article>
                        @endforeach
                    @endif
                </div>
            </section>
        @endforeach
    @endif
</aside>
