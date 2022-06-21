@extends('admin.layout')
@section('content')
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <form method="post" action="">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header"><strong>{{!empty($oneItem) ? 'Chỉnh sửa' : 'Thêm mới'}} Menu</strong></div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Tên menu</label>
                                                <input class="form-control" name="name" value="{{!empty($oneItem->name) ? $oneItem->name : ''}}" type="text" placeholder="Tên menu" required>
                                                <input type="hidden" name="data" value="{{!empty($oneItem->data) ? $oneItem->data : ''}}">
                                            </div>
                                            <h5>Chọn chuyên mục</h5>
                                            <div class="row mb-1">
                                                <div class="form-group col-9">
                                                    <select class="form-control" name="category_id">
                                                        <option value="" data-url=""
                                                                data-title="" selected disabled>Select category</option>
                                                        @foreach($categoryTree as $item)
                                                            <option value="{{$item['id']}}"
                                                                    data-url="{{ !empty($item['parent']) ? '/'.$item['parent']->slug : '' }}/{{ $item['slug'] }}/"
                                                                    data-title="{{$item['title']}}">{{$item['title']}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="text-center col-3">
                                                    <button type="button" class="category_select btn-select btn btn-theme border">Chọn &gt;&gt;</button>
                                                </div>
                                            </div>
                                            <h5>Link khác</h5>
                                            <div class="row mb-1">
                                                <div class="col-9">
                                                    <input type="text" class="form-control" name="custom_link" placeholder="link">
                                                </div>
                                                <div class="text-center col-3">
                                                    <button type="button" class="link_select btn-select btn btn-theme border">Chọn &gt;&gt;</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-3">
                                            <div class="cf nestable-lists">
                                                <div class="dd" id="nestable">
                                                    <ol class="dd-list"></ol>
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
    <script defer src="/admin/js/nestable/menu.js?{{time()}}"></script>
@endsection
