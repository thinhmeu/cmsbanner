<!DOCTYPE html>
<html lang="{{str_replace('_', '-', app()->getLocale())}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/admin/css/coreui/coreui.min.css">
    <link rel="stylesheet" href="/admin/css/custom.css">
    <link rel="stylesheet" href="/admin/css/toastr.min.css">
    <link rel="stylesheet" href="/admin/DataTables/datatables.min.css">
    <title>{{!empty(getCurrentControllerTitle()) ? 'Quản lý '.getCurrentControllerTitle() : 'Admin'}}</title>
</head>
<body>
@include('admin.sidebar')
<div class="c-wrapper c-fixed-components">
    @include('admin.header')
    <div class="c-body">
        @yield('content')
        @include('admin.footer')
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="/admin/js/coreui/coreui.bundle.min.js"></script>
<script src="/admin/js/toastr.js"></script>
<script src="/admin/DataTables/datatables.min.js"></script>
<script src="/admin/js/custom.js?{{time()}}"></script>
<script src="/admin/js/tinymce.min.js"></script>
<script src="/admin/js/tiny_full_featured.js?12"></script>
<script src="/admin/js/jquery.nestable.js"></script>
<script src="/admin/js/seo_tool.js?{{time()}}"></script>
</body>
</html>
