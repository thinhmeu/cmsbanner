@php
 use App\Helpers\AdminUrl;
@endphp
@extends('admin.layout')
@section('content')
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="card">
                    <div class="card-header">
                        Danh sách {{$type == 'website' ? 'Website' : 'Vị trí'}}
                        <div class="card-header-actions pr-1">
                            <a href="/admin/banner/{{$type}}/0"><button class="btn btn-block btn-primary btn-sm mr-3" type="button">Thêm mới</button></a>
                        </div>
                    </div>
                    <form class="card-body">
                        <div class="d-flex my-3">
                            <input name="keyword" type="search" placeholder="Tìm kiếm..." class="form-control" value="{{$keyword}}">
                            <button type="submit" class="btn btn-primary text-nowrap ml-3">Tìm kiếm</button>
                        </div>
                        <table class="table table-striped table-bordered datatable">
                            <thead>
                            <tr>
                                <th class="text-center w-5">ID</th>
                                <th>Tiêu đề</th>
                                <th class="text-center w-15">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $item)
                                <tr>
                                    <td class="text-center">{{$item->id}}</td>
                                    <td>{{$item->title}}</td>
                                    <td class="text-center">
                                        <a class="btn btn-info" href="{{AdminUrl::getUrlBannerSite($type, $item->id)}}"><svg class="c-icon"><use xlink:href="/admin/images/icon-svg/free.svg#cil-pencil"></use></svg></a>
                                        <a class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')" href="{{AdminUrl::getUrlBannerSiteDelete($item->id)}}"><svg class="c-icon"><use xlink:href="/admin/images/icon-svg/free.svg#cil-trash"></use></svg></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$data->links()}}
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
