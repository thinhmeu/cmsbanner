<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
    <div class="c-sidebar-brand d-lg-down-none">
        <svg class="c-sidebar-brand-full" width="118" height="46" alt="CoreUI Logo">
            <use xlink:href="/admin/images/icon-svg/coreui.svg#full"></use>
        </svg>
        <svg class="c-sidebar-brand-minimized" width="46" height="46" alt="CoreUI Logo">
            <use xlink:href="/admin/images/icon-svg/coreui.svg#signet"></use>
        </svg>
    </div>
    <ul class="c-sidebar-nav ps ps--active-y">
        <li class="c-sidebar-nav-item c-sidebar-nav-dropdown @if(getCurrentController() == 'banner') c-show @endif">
            <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="/admin/images/icon-svg/free.svg#cil-notes"></use>
                </svg>
                Quản lý banner
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link @if(!empty($_GET['status'])) c-active @endif" href="/admin/banner/site/website"><span class="c-sidebar-nav-icon"></span> Danh sách site</a></li>
                <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link @if(!empty($_GET['status'])) c-active @endif" href="/admin/banner/site/position"><span class="c-sidebar-nav-icon"></span> Danh sách vị trí</a></li>
                <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="/admin/banner"><span class="c-sidebar-nav-icon"></span> Danh sách banner</a></li>
            </ul>
        </li>
        @if(!empty($permission['user']))
        <li class="c-sidebar-nav-item c-sidebar-nav-dropdown">
            <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
                <svg class="c-sidebar-nav-icon">
                    <use xlink:href="/admin/images/icon-svg/free.svg#cil-user"></use>
                </svg>
                Thành viên
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="/admin/user"><span class="c-sidebar-nav-icon"></span> Danh sách</a></li>
                <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="/admin/user/update"><span class="c-sidebar-nav-icon"></span> Thêm mới</a></li>
                @if(!empty($permission['group']))
                <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="/admin/group"><span class="c-sidebar-nav-icon"></span> Phân quyền</a></li>
                @endif
            </ul>
        </li>
        @endif
        @if(!empty($permission['site_setting']))
            <li class="c-sidebar-nav-item c-sidebar-nav-dropdown">
                <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="/admin/images/icon-svg/free.svg#cil-settings"></use>
                    </svg>
                    Cấu hình chung
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="/admin/site_setting/update"><span class="c-sidebar-nav-icon"></span> Thông tin trang</a></li>
                </ul>
            </li>
        @endif
    </ul>
    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div>
