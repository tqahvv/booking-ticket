@extends('layouts.client')

@section('title', 'Chi tiết tài khoản')
@section('content')
    <div class="settings-page">
        <div class="sidebar-menu">
            <div class="user-info-section">
                <div class="avatar-hn">
                    @if ($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="avatar"
                            style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                    @else
                        <img src="{{ asset('storage/uploads/avatars/avatar-default.jpg') }}" alt="default avatar"
                            style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                    @endif
                </div>

                <div class="user-details">
                    <div class="user-name" style="font-weight: 600;">
                        {{ $user->name }}
                    </div>
                    <div class="user-source">
                        {{ $user->provider ?? 'Tài khoản thường' }}
                    </div>
                </div>
            </div>

            <ul class="menu-list">
                <li class="menu-item active"><i class="bi bi-credit-card"></i> Thẻ của tôi</li>
                <li class="menu-item"><i class="bi bi-journal-text"></i> Đặt chỗ của tôi</li>
                <li class="menu-item"><i class="bi bi-list-ul"></i> Danh sách giao dịch</li>
                <li class="menu-item"><i class="bi bi-person-lines-fill"></i> Thông tin hành khách đã lưu</li>
                <li class="menu-item"><i class="bi bi-bell"></i> Cài đặt thông báo</li>
                <li class="menu-item"><i class="bi bi-person-circle"></i> Tài khoản</li>
            </ul>
        </div>

        <div class="main-content1">
            <div class="main-content1-header-wrapper">
                <h1 class="page-title">Cài đặt</h1>
                <div class="tabs">
                    <a href="#" class="tab-link active">Thông tin tài khoản</a>
                </div>
            </div>

            <div class="section-container">
                <h2 class="section-title1">Dữ liệu cá nhân</h2>

                <form id="profile-form" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Ảnh đại diện</label><br>
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/uploads/avatars/avatar-default.jpg') }}"
                            alt="avatar" id="avatar-preview"
                            style="width:100px;height:100px;border-radius:50%;object-fit:cover;">
                        <input type="file" name="avatar" id="avatar" class="text-input" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label for="full-name">Tên đầy đủ</label>
                        <input type="text" autocomplete="off" id="full-name" name="name" class="text-input"
                            value="{{ $user->name }}">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" autocomplete="off" class="text-input"
                            value="{{ $user->email }}" disabled>
                    </div>

                    <div class="form-group-inline">
                        <label>Giới tính</label>
                        <div class="input-container-dropdown">
                            <select class="dropdown-input" name="gender">
                                <option value="">Chọn giới tính</option>
                                <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                                <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="birthday">Ngày sinh</label>
                        <input type="date" id="birthday" name="birthday" class="text-input"
                            value="{{ $user->birthday }}">
                    </div>

                    <div class="form-group">
                        <label for="city-of-residence">Địa chỉ</label>
                        <input type="text" autocomplete="off" id="city-of-residence" name="address"
                            value="{{ $user->address }}" class="text-input">
                    </div>

                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="text" autocomplete="off" id="phone" name="phone" value="{{ $user->phone }}"
                            class="text-input">
                    </div>

                    <div class="save-actions">
                        <button type="button" class="button-later">Có lẽ sau</button>
                        <button type="submit" class="button-save">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
