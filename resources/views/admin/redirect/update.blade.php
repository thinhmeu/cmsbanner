@extends('admin.layout')
@section('content')
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <form method="post" action="">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header"><strong>{{!empty($oneItem) ? 'Chỉnh sửa' : 'Thêm mới'}} Link</strong></div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Link gốc</label>
                                                <input class="form-control" name="original_url" value="{{!empty($oneItem->original_url) ? $oneItem->original_url : ''}}" type="text" placeholder="Link gốc">
                                            </div>
                                            <div class="form-group">
                                                <label>Link mới</label>
                                                <input class="form-control" name="redirect_url" value="{{!empty($oneItem->redirect_url) ? $oneItem->redirect_url : ''}}" type="text" placeholder="Link mới">
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
@endsection
