@extends('admin.layout')
@section('content')
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <form method="post" action="">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header"><strong>{{!empty($oneItem) ? 'Chỉnh sửa' : 'Thêm mới'}} Nhóm quyền</strong></div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Tiêu đề</label>
                                                <input class="form-control" required name="title" required value="{{!empty($oneItem->title) ? $oneItem->title : ''}}" type="text" placeholder="Tiêu đề">
                                            </div>
                                            <div class="form-group">
                                                <label>Nhóm quyền</label>
                                                <table class="table table-responsive-sm table-bordered text-center">
                                                    <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Xem</th>
                                                        <th>Thêm</th>
                                                        <th>Sửa</th>
                                                        <th>Xóa</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($listPermission as $key => $title)
                                                        <tr>
                                                            <td class="text-left">{{$title}}</td>
                                                            <td>
                                                                <div class="form-check checkbox">
                                                                    <input class="form-check-input" name="permission[{{$key}}][index]" {{!empty($permission[$key]) && array_key_exists('index', $permission[$key]) ? 'checked' : ''}} type="checkbox">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check checkbox">
                                                                    <input class="form-check-input" name="permission[{{$key}}][add]" {{!empty($permission[$key]) && array_key_exists('add', $permission[$key]) ? 'checked' : ''}} type="checkbox">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check checkbox">
                                                                    <input class="form-check-input" name="permission[{{$key}}][edit]" {{!empty($permission[$key]) && array_key_exists('edit', $permission[$key]) ? 'checked' : ''}} type="checkbox">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check checkbox">
                                                                    <input class="form-check-input" name="permission[{{$key}}][delete]" {{!empty($permission[$key]) && array_key_exists('delete', $permission[$key]) ? 'checked' : ''}} type="checkbox">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
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
