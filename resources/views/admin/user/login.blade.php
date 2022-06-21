<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
{{--    <meta name="csrf-token" content="{{csrf_token()}}" />--}}
    <title>Đăng nhập</title>
    <link href="/admin/css/coreui/coreui.min.css" rel="stylesheet">
</head>
<body class="c-app flex-row align-items-center">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card-group mb-5">
                <div class="card p-4">
                    <form method="post" action="">
                        <div class="card-body">
                            <h1 class="text-center mb-4">Đăng nhập</h1>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><svg class="c-icon"><use xlink:href="/admin/images/icon-svg/free.svg#cil-user"></use></svg></span>
                                </div>
                                <input class="form-control" name="username" required type="text" placeholder="Tài khoản">
                            </div>
                            <div class="input-group mb-4">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><svg class="c-icon"><use xlink:href="/admin/images/icon-svg/free.svg#cil-lock-locked"></use></svg></span>
                                </div>
                                <input class="form-control" name="password" required type="password" placeholder="Mật khẩu">
                            </div>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <button class="btn btn-primary px-4 ajax-login" type="button">Đăng nhập</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="/admin/js/custom.js"></script>
