@extends('admin.layout')
@section('content')
    <main class="c-main bg-white">
        <div class="container-fluid">
            <div class="fade-in">
                <form method="post" action="">
                    <div class="row">
                        <div class="col-md-8 pr-0">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#seo" role="tab" aria-controls="seo">Nội dung SEO</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#info" role="tab" aria-controls="info">Thông tin</a></li>
                            </ul>
                        </div>
                        <div class="col-md-4 border-bottom">
                            <button type="submit" class="btn btn-primary float-right">Lưu trữ</button>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active" id="seo" role="tabpanel">
                            <div class="row py-2">
                                <div class="col-sm-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>Tiêu đề</label>
                                                        <input class="form-control" required name="title" value="{!! !empty($oneItem->title) ? $oneItem->title : '' !!}" type="text" placeholder="Tiêu đề">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Nội dung</label>
                                                        <textarea id="full-featured" class="form-control" name="content">{{!empty($oneItem->content) ? $oneItem->content : ''}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>
                                                    Tiêu đề SEO
                                                    <span class="text-danger" id="title-count-text">
                                                    <span>Độ dài hiện tại: </span>
                                                    <span id="title-count">0</span> ký tự</span>
                                                </label>
                                                <input class="form-control" name="meta_title" value="{{!empty($oneItem->meta_title) ? $oneItem->meta_title : ''}}" type="text" placeholder="Tiêu đề SEO">
                                            </div>
                                            <div class="form-group">
                                                <label>Đường dẫn (URL)</label>
                                                <input class="form-control" name="slug" value="{{!empty($oneItem->slug) ? $oneItem->slug : ''}}" type="text" placeholder="Slug bài viết">
                                            </div>
                                            <div class="form-group">
                                                <label>
                                                    Mô tả SEO
                                                    <span class="text-danger" id="description-count-text">
                                                <span>Độ dài hiện tại: </span>
                                                <span id="description-count">0</span> ký tự
                                            </span>
                                                </label>
                                                <textarea class="form-control" name="meta_description" rows="4" placeholder="Mô tả SEO">{{!empty($oneItem->meta_description) ? $oneItem->meta_description : ''}}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>
                                                    Từ khóa liên quan
                                                </label>
                                                <input class="form-control" name="meta_keyword" value="{{!empty($oneItem->meta_keyword) ? $oneItem->meta_keyword : ''}}" type="text" placeholder="Từ khóa liên quan">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="info" role="tabpanel">
                            <div class="row py-2">
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>Chuyên mục cha</label>
                                                        <select class="form-control" name="parent_id">
                                                            <option value="0">Lựa chọn</option>
                                                            @foreach($categoryTree as $item)
                                                                <option value="{{$item['id']}}" {{!empty($oneItem) && $item['id'] == $oneItem->parent_id? 'selected': ''}}>{{$item['title']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-md-6">
                                                            <label>Meta robots</label>
                                                            <select name="is_index" class="form-control">
                                                                <option {{isset($oneItem->is_index) && $oneItem->is_index == 1 ? 'selected' : ''}} value="1">Index, follow</option>
                                                                <option {{isset($oneItem->is_index) && $oneItem->is_index == 0 ? 'selected' : ''}} value="0">Noindex, nofollow</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Thumbnail</label>
                                                @if(!empty($oneItem->thumbnail))
                                                    <img style="width: 150px" src="{{$oneItem->thumbnail}}" id="lbl_img" class="img-fluid d-block" onclick="upload_file('chosefile','img')">
                                                @else
                                                    <img style="width: 150px" src="{{url('admin/images/no-image.jpg')}}" id="lbl_img" class="img-fluid d-block" onclick="upload_file('chosefile','img')">
                                                @endif
                                                <input type="hidden" name="thumbnail" id="hd_img" value="{{!empty($oneItem->thumbnail)? $oneItem->thumbnail: ''}}" required>
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
@endsection
