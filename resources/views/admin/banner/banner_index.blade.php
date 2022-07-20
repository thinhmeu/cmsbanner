@extends('admin.layout')
@section('content')
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="card">
                    <div class="card-header">
                        Danh sách Banner
                        <div class="card-header-actions pr-1">
                            <a href="/admin/banner/update"><button class="btn btn-block btn-primary btn-sm mr-3" type="button">Thêm mới</button></a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="" class="row my-3">
                            <div class="col-12 col-lg-6">
                                <b>Chọn website</b>
                                @if(!empty($allSite))
                                    <select class="form-control" onchange="this.form.action = this.value; this.form.submit()">
                                        @foreach($allSite as $item)
                                            <option @if($item->id == $id_website) selected @endif value="/admin/banner/{{$item->id}}/{{$id_position}}">{{$item->title}}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            <div class="col-12 col-lg-6">
                                <b>Chọn vị trí</b>
                                @if(!empty($allPosition))
                                    <select class="form-control" onchange="this.form.action = this.value; this.form.submit()">
                                        @foreach($allPosition as $item)
                                            <option @if($item->id == $id_position) selected @endif value="/admin/banner/{{$id_website}}/{{$item->id}}">{{$item->title}}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </form>

                        <table class="table table-striped table-bordered datatable text-center">
                            <thead>
                            <tr>
                                <th class="w-5">ID</th>
                                <th class="w-10">Thứ tự</th>
                                <th class="text-left">Tiêu đề</th>
                                <th class="w-15">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if(!empty($listItem)) @foreach($listItem as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->order}}</td>

                                    <?php $reallyStatus = [
                                        '<span class="badge badge-danger">Off</span>',
                                        '<span class="badge badge-success">On</span>',
                                        ]
                                    ?>
                                    <td class="text-left">{{$item->title}} {{$item->width.'*'.$item->height}} {!!$reallyStatus[$item->really_status]!!}</td>
                                    <td>
                                        <a class="btn btn-info" href="/admin/banner/update/{{$item->id}}"><svg class="c-icon"><use xlink:href="/admin/images/icon-svg/free.svg#cil-pencil"></use></svg></a>
                                        <a class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')" href="/admin/banner/delete/{{$item->id}}"><svg class="c-icon"><use xlink:href="/admin/images/icon-svg/free.svg#cil-trash"></use></svg></a>
                                    </td>
                                </tr>
                                @endforeach @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
