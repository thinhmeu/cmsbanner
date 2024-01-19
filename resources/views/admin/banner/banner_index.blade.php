@extends('admin.layout')
@section('content')
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="card" method="get">
                    <div class="card-header">
                        Danh sách Banner
                    </div>

                    <div class="card-body">
                        <form class="row my-3 no-gutters gap-3">
                            <div>
                                <b>Chọn website</b>
                                @if(!empty($allSite))
                                    <select class="form-control changeToSubmitForm" name="id_website">
                                        <option value="0">Tất cả</option>
                                        @foreach($allSite as $item)
                                            <option @if($item->id == $id_website) selected @endif value="{{$item->id}}">{{$item->title}} ({{$item->count_position}} vị trí có banner)</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            <div>
                                <b>Chọn vị trí</b>
                                @if(!empty($allPosition))
                                    <select class="form-control changeToSubmitForm" name="id_position">
                                        <option value="0">Tất cả</option>
                                        @foreach($allPosition as $item)
                                            <option @if($item->id == $id_position) selected @endif value="{{$item->id}}">{{$item->title}} @if($item->count_banner)({{$item->count_banner}} banner)@endif</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            <div>
                                <b>Tìm kiếm</b>
                                <input class="form-control" type="text" name="keyword" value="{{$keyword}}">
                            </div>
                            <button class="btn btn-sm btn-primary" type="submit">Lọc</button>
                            <div class="col-12">
                                <button name="addBanner" value="1" class="btn btn-primary btn-sm mr-3">Thêm mới</button>
                            </div>
                        </form>

                        <div class="overflow-auto">
                            <table class="table table-striped table-bordered datatable text-center">
                                <thead>
                                <tr>
                                    <th class="w-5">ID</th>
                                    <th class="w-10">Thứ tự</th>
                                    <th class="text-left">Tiêu đề</th>
                                    <th class="text-left">Link</th>
                                    <th class="w-15">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($listItem)) @foreach($listItem as $item)
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td>
                                            <input type="number" min="1" class="form-control form-control-sm" name="order[{{$item->id}}]" value="{{$item->order}}">
                                        </td>

                                        <?php $reallyStatus = [
                                            '<span class="badge badge-danger">Off</span>',
                                            '<span class="badge badge-success">On</span>',
                                        ]
                                        ?>
                                        <td class="text-left">{{$item->title}} {{$item->width.'*'.$item->height}} {!!$reallyStatus[$item->recent_status]!!}</td>
                                        <td class="text-left">{{$item->link ?? ''}}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-3">
                                                <a class="btn btn-info" href="{{route("getUrlBannerUpdate", [$item->id])}}">
                                                    <svg class="c-icon"><use xlink:href="/admin/images/icon-svg/free.svg#cil-pencil"></use></svg>
                                                </a>
                                                <a class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')" href="{{route("getUrlBannerDelete", [$item->id])}}">
                                                    <svg class="c-icon"><use xlink:href="/admin/images/icon-svg/free.svg#cil-trash"></use></svg>
                                                </a>
                                                <a class="btn btn-info" href="{{route("getUrlBannerDuplicate", [$item->id])}}">
                                                    <img loading="lazy" src="/admin/icons/svg/content_copy.svg" alt="" width="23" height="23" style="filter: invert(1)">
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach @endif
                                </tbody>
                            </table>
                        </div>
                        <form action="" id="formUpdateOrder">
                            <button name="updateOrder" value="1" class="btn btn-sm btn-success">Update order</button>
                        </form>
                    </div>
                    {{$listItem->links()}}
                </div>
            </div>
        </div>
    </main>
@endsection
