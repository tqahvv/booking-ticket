<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="#" class="site_title"><i class="fa fa-paw"></i> <span>TRAVELISTA!</span></a>
        </div>

        <div class="clearfix"></div>

        <div class="profile clearfix">
            <div class="profile_pic">
                @php
                    $admin = Auth::guard('admin')->user();
                @endphp

                <img src="{{ $admin->avatar ? asset('storage/' . $admin->avatar) : asset('storage/uploads/avatars/avatar-default.jpg') }}"
                    alt="Avatar" class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Xin chào,</span>
                <h2>{{ $admin->name }}</h2>
            </div>
        </div>

        <br />

        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>Tổng quan</h3>
                @php
                    $adminUser = Auth::guard('admin')->user();
                @endphp
                <ul class="nav side-menu">
                    <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i> Dashboard </a></li>
                    @if ($adminUser->role->permissions->contains('name', 'manage_users'))
                        <li><a href="{{ route('admin.users') }}"><i class="fa fa-users"></i> Quản lý người dùng
                            </a></li>
                    @endif
                    @if ($adminUser->role->permissions->contains('name', 'manage_posts'))
                        <li><a href="#"><i class="fa fa-lock"></i> Quản lý bài viết <span
                                    class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="{{ route('admin.post.add') }}">Thêm mới bài viết</a></li>
                                <li><a href="{{ route('admin.posts.index') }}">Danh sách bài viết</a></li>
                            </ul>
                        </li>
                    @endif
                    @if ($adminUser->role->permissions->contains('name', 'manage_bookings'))
                        <li><a href="#"><i class="fa fa-table"></i> Quản lý đặt chỗ <span
                                    class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="{{ route('admin.bookings.index') }}">Danh sách đặt chỗ</a></li>
                            </ul>
                        </li>
                    @endif
                    @if ($adminUser->role->permissions->contains('name', 'manage_schedules'))
                        <li><a href="#"><i class="fa fa-bar-chart-o"></i> Quản lý lịch chuyến <span
                                    class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="{{ route('admin.schedules.index') }}">Danh sách lịch chuyến</a></li>
                            </ul>
                        </li>
                    @endif
                    @if ($adminUser->role->permissions->contains('name', 'manage_schedules'))
                        <li><a href="#"><i class="fa fa-clone"></i> Quản lý lịch chạy <span
                                    class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="">Thêm mới lịch chạy</a></li>
                                <li><a href="{{ route('admin.scheduleTemplates.index') }}">Danh sách lịch chạy</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Đăng xuất" href="{{ route('admin.logout') }}">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
    </div>
</div>
