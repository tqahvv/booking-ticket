@extends('layouts.admin')

@section('title', 'Trang quản lý tài khoản')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Quản lý tài khoản</h3>
                </div>

                <div class="title_right">
                    <div class="role-filter">
                        @php
                            $currentRole = request('role');
                        @endphp
                        <button class="btn btn-{{ is_null($currentRole) ? 'primary' : 'default' }} role-btn"
                            data-role="">Tất cả ({{ $counts['all'] }})</button>
                        <button class="btn btn-{{ $currentRole === 'admin' ? 'primary' : 'default' }} role-btn"
                            data-role="admin">Admin ({{ $counts['admin'] }})</button>
                        <button class="btn btn-{{ $currentRole === 'staff' ? 'primary' : 'default' }} role-btn"
                            data-role="staff">Nhân viên ({{ $counts['staff'] }})</button>
                        <button class="btn btn-{{ $currentRole === 'customer' ? 'primary' : 'default' }} role-btn"
                            data-role="customer">Khách hàng ({{ $counts['customer'] }})</button>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="x_panel">
                <div class="x_content">
                    <div class="clearfix"></div>

                    <div class="user-container">
                        @include('admin.components.users-list')

                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
