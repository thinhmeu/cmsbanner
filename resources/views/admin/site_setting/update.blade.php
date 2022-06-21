@extends('admin.layout')
@section('content')
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <form method="post" action="">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-header"><strong>{{!empty($oneItem) ? 'Chỉnh sửa' : 'Thêm mới'}} Thông tin trang</strong></div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Tiêu đề trang</label>
                                                <input class="form-control" name="site_title" value="{{!empty($oneItem->site_title) ? $oneItem->site_title : ''}}" type="text" placeholder="Tiêu đề trang">
                                            </div>
                                            <div class="form-group">
                                                <label>Mô tả trang</label>
                                                <textarea class="form-control" name="site_description" rows="4" placeholder="Mô tả trang">{{!empty($oneItem->site_description) ? $oneItem->site_description : ''}}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Mã header</label>
                                                <textarea class="form-control" name="meta_head" rows="4" placeholder="Mã header">{{!empty($oneItem->meta_head) ? $oneItem->meta_head : ''}}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Mã header amp</label>
                                                <textarea class="form-control" name="meta_head_amp" rows="4" placeholder="Mã header amp">{{!empty($oneItem->meta_head_amp) ? $oneItem->meta_head_amp : ''}}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Giới thiệu trang</label>
                                                <input class="form-control" name="site_about" value="{{!empty($oneItem->site_about) ? $oneItem->site_about : ''}}" type="text" placeholder="Giới thiệu trang">
                                            </div>
                                            <div class="form-group">
                                                <label>Từ khóa trang</label>
                                                <input class="form-control" name="site_keyword" value="{{!empty($oneItem->site_keyword) ? $oneItem->site_keyword : ''}}" type="text" placeholder="Từ khóa trang">
                                            </div>
                                            <div class="form-group">
                                                <label>Địa chỉ email</label>
                                                <input class="form-control" name="site_email" value="{{!empty($oneItem->site_email) ? $oneItem->site_email : ''}}" type="text" placeholder="Địa chỉ email">
                                            </div>
                                            <div class="form-group">
                                                <label>Copyright</label>
                                                <textarea id="tiny-featured" class="form-control" name="site_copyright" rows="2" placeholder="Copyright">{{!empty($oneItem->site_copyright) ? $oneItem->site_copyright : ''}}</textarea>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <label>Facebook</label>
                                                    <input class="form-control" name="site_facebook" value="{{!empty($oneItem->site_facebook) ? $oneItem->site_facebook : ''}}" type="text" placeholder="Facebook">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Twitter</label>
                                                    <input class="form-control" name="site_twitter" value="{{!empty($oneItem->site_twitter) ? $oneItem->site_twitter : ''}}" type="text" placeholder="Twitter">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Pinterest</label>
                                                    <input class="form-control" name="site_pinterest" value="{{!empty($oneItem->site_pinterest) ? $oneItem->site_pinterest : ''}}" type="text" placeholder="Pinterest">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Youtube</label>
                                                    <input class="form-control" name="site_youtube" value="{{!empty($oneItem->site_youtube) ? $oneItem->site_youtube : ''}}" type="text" placeholder="Youtube">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>LinkedIn</label>
                                                    <input class="form-control" name="site_linkedin" value="{{!empty($oneItem->site_linkedin) ? $oneItem->site_linkedin : ''}}" type="text" placeholder="LinkedIn">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Tumblr</label>
                                                    <input class="form-control" name="site_tumblr" value="{{!empty($oneItem->site_tumblr) ? $oneItem->site_tumblr : ''}}" type="text" placeholder="Tumblr">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Instagram</label>
                                                    <input class="form-control" name="site_instagram" value="{{!empty($oneItem->site_instagram) ? $oneItem->site_instagram : ''}}" type="text" placeholder="Instagram">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Reddit</label>
                                                    <input class="form-control" name="site_reddit" value="{{!empty($oneItem->site_reddit) ? $oneItem->site_reddit : ''}}" type="text" placeholder="Reddit">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Scoop.it</label>
                                                    <input class="form-control" name="site_scoopit" value="{{!empty($oneItem->site_scoopit) ? $oneItem->site_scoopit : ''}}" type="text" placeholder="Scoop.it">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Nội dung trang</label>
                                                <textarea id="full-featured" class="form-control" name="site_content">{{!empty($oneItem->site_content) ? $oneItem->site_content : ''}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-header"><strong>Thông tin khác</strong></div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Logo</label>
                                        @if(!empty($oneItem->site_logo))
                                            <img src="{{$oneItem->site_logo}}" id="lbl_img" class="img-fluid d-block" onclick="upload_file('chosefile','img')">
                                        @else
                                            <img src="{{url('admin/images/no-image.jpg')}}" id="lbl_img" class="img-fluid d-block" onclick="upload_file('chosefile','img')">
                                        @endif
                                        <input type="hidden" id="hd_img" name="site_logo" value="{{!empty($oneItem->site_logo)? $oneItem->site_logo: ''}}" required>
                                    </div>
                                    <div class="form-group float-right">
                                        <button type="submit" class="btn btn-primary">Lưu trữ</button>
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
