@extends('admin.layout')
@section('content')
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <form method="post" action="" autocomplete="off">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header"><strong>Cấu hình top game chuyên mục</strong></div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h5>Chọn chuyên mục</h5>
                                            <div class="row mb-1">
                                                <div class="form-group col-9">
                                                    <select class="form-control sl-position">
                                                        @foreach($listCategory as $item)
                                                            <option @if($item->id == $category_id) selected @endif data-url="/admin/top_game/category/{{$item->id}}">{{$item->title}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <h5>Bài viết</h5>
                                            <div class="row">
                                                <div class="col-9">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">
                                                            <svg class="c-icon">
                                                            <use xlink:href="/admin/images/icon-svg/free.svg#cil-search"></use>
                                                            </svg></span>
                                                        </div>
                                                        <input autocomplete="off" class="form-control nha-cai-search-keyword" name="custom_link" type="text" placeholder="Tìm kiếm">
                                                    </div>
                                                    <div class="ajax-list-post border d-none">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="cf nestable-lists">
                                                <div class="dd" id="nestable">
                                                    <ol class="dd-list">
                                                        @if(!empty($listItem))
                                                            @foreach($listItem as $item)
                                                                <li class="dd-item">
                                                            <div class="dd-handle">{!! $item->post->title !!}</div>
                                                            <div class="action-item">
                                                                <span class="nestledeletedd">
                                                                    <svg class="c-icon">
                                                                        <use xlink:href="/admin/images/icon-svg/free.svg#cil-trash"></use>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                            <input type="hidden" name="post_id[]" value="{{$item->post->id}}">
                                                        </li>
                                                            @endforeach
                                                        @endif
                                                    </ol>
                                                </div>
                                            </div>
                                            <div class="form-group float-right">
                                                <button type="submit" class="btn btn-primary">Lưu trữ</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    {{--Modal--}}
    <div class="modal fade" id="smallModal" tabindex="-1" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p>Bạn có muốn xóa?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary confirm_yes" type="button">Yes</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
    <script defer src="/admin/js/nestable/top_game.js?{{time()}}"></script>
@endsection
