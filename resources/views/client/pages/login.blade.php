@extends('layouts.client')

@section('title', 'Đăng nhập tài khoản')
@section('breadcrumb', 'Đăng nhập tài khoản')

@section('content')
    <div class="register-fill d-flex justify-content-center align-items-center py-5">
        <div class="card p-4 shadow-lg" style="width: 420px; border-radius: 12px;">
            <h3 class="text-center fw-bold">Chào mừng quay lại Travelista</h3>
            <p class="text-center text-muted mb-4">
                Đăng nhập tài khoản ngay để tiếp tục trải nghiệm dịch vụ
            </p>

            <form id="loginForm" method="POST">
                @csrf
                <div class="mb-3 position-relative">
                    <span class="input-icon"><i class="bi bi-envelope"
                            style="display: flex; align-items: center; justify-content: center"></i></span>
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                    <div class="text-danger small error-email error-message-placeholder"></div>
                </div>

                <div class="mb-3 position-relative">
                    <span class="input-icon"><i class="bi bi-lock"></i></span>
                    <input type="password" autocomplete="off" class="form-control" name="password"
                        placeholder="Nhập mật khẩu" required>
                    <div class="text-danger small mt-1 error-password"></div>
                </div>

                <button type="submit" class="btn btn-danger w-100 mb-3">Đăng nhập</button>

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

                <p class="text-center mt-3 mb-0">
                    Bạn chưa có tài khoản? <a href="{{ route('register') }}" class="fw-bold">Đăng ký</a>
                </p>
                <p class="text-center mt-1">
                    <a href="#" class="fw-bold">Đăng nhập với tư cách là khách!</a>
                </p>
            </form>
        </div>
    </div>
@endsection
