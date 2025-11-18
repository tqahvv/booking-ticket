@extends('layouts.client')

@section('title', 'Đăng ký tài khoản')
@section('breadcrumb', 'Đăng ký tài khoản')

@section('content')
    <div class="register-fill d-flex justify-content-center align-items-center py-5">
        <div class="card p-4 shadow-lg" style="width: 420px; border-radius: 12px;">
            <h3 class="text-center fw-bold">Chào mừng đến với Travelista</h3>
            <p class="text-center text-muted mb-4">
                Đăng ký tài khoản ngay để có những trải nghiệm thú vị và những ưu đãi hấp dẫn
            </p>

            <form id="registerForm" method="POST">
                @csrf
                <div class="mb-3 position-relative">
                    <span class="input-icon"><i class="bi bi-person"></i></span>
                    <input type="text" autocomplete="off" class="form-control" name="name" placeholder="Họ và tên"
                        required>
                    <div class="text-danger small mt-1 error-name"></div>
                </div>
                <div class="mb-3 position-relative">
                    <span class="input-icon"><i class="bi bi-envelope"></i></span>
                    <input type="email" autocomplete="off" class="form-control" name="email" placeholder="Email"
                        required>
                    <div class="text-danger small mt-1 error-email"></div>
                </div>
                <div class="mb-3 position-relative">
                    <span class="input-icon"><i class="bi bi-telephone"></i></span>
                    <input type="text" autocomplete="off" class="form-control" name="phone" placeholder="Số điện thoại"
                        required>
                    <div class="text-danger small mt-1 error-phone"></div>
                </div>
                <div class="mb-3 position-relative">
                    <span class="input-icon"><i class="bi bi-geo-alt"></i></span>
                    <input type="text" autocomplete="off"class="form-control" name="address" placeholder="Địa chỉ">
                    <div class="text-danger small mt-1 error-address"></div>
                </div>
                <div class="mb-3 position-relative">
                    <span class="input-icon"><i class="bi bi-lock"></i></span>
                    <input type="password" autocomplete="off" class="form-control" name="password"
                        placeholder="Nhập mật khẩu" required>
                    <div class="text-danger small mt-1 error-password"></div>
                </div>
                <div class="mb-3 position-relative">
                    <span class="input-icon"><i class="bi bi-shield-lock"></i></span>
                    <input type="password" autocomplete="off" class="form-control" name="password_confirmation"
                        placeholder="Nhập lại mật khẩu" required>
                    <div class="text-danger small mt-1 error-password_confirmation"></div>
                </div>

                <button type="submit" class="btn btn-danger w-100 mb-3">Tiếp tục</button>

                <div class="d-flex align-items-center my-3">
                    <hr class="flex-grow-1">
                    <span class="mx-2 text-muted">Hoặc</span>
                    <hr class="flex-grow-1">
                </div>

                <button type="button" class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-facebook me-2"></i> Tiếp tục với Facebook
                </button>
                <button type="button" class="btn btn-light border w-100 mb-3">
                    <i class="bi bi-google me-2"></i> Tiếp tục với Google
                </button>

                <p class="text-center small text-muted">
                    Bằng cách tiếp tục, bạn đồng ý với <a href="#" class="fw-bold">Điều khoản dịch vụ</a>
                    của chúng tôi và xác nhận rằng bạn đã đọc <a href="#" class="fw-bold">Chính sách quyền riêng
                        tư</a>.
                </p>

                <p class="text-center mt-3 mb-0">
                    Đã là thành viên? <a href="" class="fw-bold">Đăng nhập</a>
                </p>
                <p class="text-center mt-1">
                    <a href="#" class="fw-bold">Đăng nhập với tư cách là khách!</a>
                </p>
            </form>
        </div>
    </div>
@endsection
