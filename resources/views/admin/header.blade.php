<header class="c-header c-header-light c-header-fixed c-header-with-subheader">
    <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar"
            data-class="c-sidebar-show">
        <svg class="c-icon c-icon-lg">
            <use xlink:href="/admin/images/icon-svg/free.svg#cil-menu"></use>
        </svg>
    </button>
    <a class="c-header-brand d-lg-none" href="#">
        <svg width="118" height="46" alt="CoreUI Logo">
            <use xlink:href="/admin/images/icon-svg/coreui.svg#full"></use>
        </svg>
    </a>
    <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar"
            data-class="c-sidebar-lg-show" responsive="true">
        <svg class="c-icon c-icon-lg">
            <use xlink:href="/admin/images/icon-svg/free.svg#cil-menu"></use>
        </svg>
    </button>
    <ul class="c-header-nav ml-auto mr-4">
        <li class="c-header-nav-item dropdown">
            <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <div class="c-avatar"><img class="c-avatar-img" src="/admin/images/icon/no-avatar.png"></div>
            </a>
            <div class="dropdown-menu dropdown-menu-right pt-2">
                <a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#changePassword">
                    <svg class="c-icon mr-2">
                        <use xlink:href="/admin/images/icon-svg/free.svg#cil-bell"></use>
                    </svg>
                    Đổi mật khẩu
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/admin/user/logout">
                    <svg class="c-icon mr-2">
                        <use xlink:href="/admin/images/icon-svg/free.svg#cil-account-logout"></use>
                    </svg>
                    Logout</a>
            </div>
        </li>
    </ul>
    <div class="c-subheader px-3">
        <ol class="breadcrumb border-0 m-0">
            <li class="breadcrumb-item"><a href="/admin/home">Home</a></li>
            <li class="breadcrumb-item active">{{getCurrentControllerTitle()}}</li>
        </ol>
    </div>
</header>
<div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Đổi mật khẩu</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-5">
                        <label class="mt-1">Mật khẩu cũ</label>
                    </div>
                    <div class="col-7">
                        <input class="form-control customValidity" name="old_password" type="password" placeholder="Mật khẩu cũ">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-5">
                        <label class="mt-1">Mật khẩu mới</label>
                    </div>
                    <div class="col-7">
                        <input class="form-control customValidity" name="new_password" type="password" placeholder="Mật khẩu mới">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-5">
                        <label class="mt-1">Nhập lại mật khẩu mới</label>
                    </div>
                    <div class="col-7">
                        <input class="form-control customValidity" name="re_password" type="password" placeholder="Nhập lại mật khẩu mới">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>
                <button class="btn btn-primary btn-change-password" type="button">Lưu trữ</button>
            </div>
        </div>
    </div>
</div>
