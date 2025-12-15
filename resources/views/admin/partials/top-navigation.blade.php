<div class="top_nav">
    <div class="nav_menu">
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>
        <nav class="nav navbar-nav">
            <ul class=" navbar-right">
                <li class="nav-item dropdown open" style="padding-left: 15px;">
                    <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown"
                        data-toggle="dropdown" aria-expanded="false">
                        @php
                            $admin = Auth::guard('admin')->user();
                        @endphp

                        <img src="{{ $admin->avatar ? asset('storage/' . $admin->avatar) : asset('storage/uploads/avatars/avatar-default.jpg') }}"
                            alt="">
                        {{ $admin->name }}
                    </a>
                    <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="javascript:;"> Trang cá nhân</a>
                        <a class="dropdown-item" href="javascript:;">Đổi mật khẩu</a>
                        <a class="dropdown-item" href="{{ route('admin.logout') }}"><i
                                class="fa fa-sign-out pull-right"></i> Đăng xuất</a>
                    </div>
                </li>

                <li role="presentation" class="nav-item dropdown open">
                    <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1"
                        data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-envelope-o"></i>
                        <span class="badge bg-green">{{ $unreadCount }}</span>
                    </a>
                    <ul class="dropdown-menu list-unstyled msg_list" role="menu">
                        @forelse ($unreadContacts as $contact)
                            <li class="nav-item {{ $contact->is_read ? 'read' : 'unread' }}">
                                <a class="dropdown-item"
                                    href="{{ route('admin.contact.index', ['open' => $contact->id]) }}">
                                    <span class="image">
                                        <img src="{{ asset('assets/admin/images/img.jpg') }}" />
                                    </span>
                                    <span>
                                        <span>{{ $contact->full_name }}</span>
                                        <span class="time">{{ $contact->created_at->diffForHumans() }}</span>
                                    </span>
                                    <span class="message">
                                        {{ Str::limit($contact->message, 40) }}
                                    </span>
                                </a>
                            </li>
                        @empty
                            <li class="nav-item text-center">
                                <span class="dropdown-item">Không có thông báo mới</span>
                            </li>
                        @endforelse

                        <li class="nav-item">
                            <div class="text-center">
                                <a href="{{ route('admin.contact.index') }}" class="dropdown-item">
                                    <strong>Xem tất cả liên hệ</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
