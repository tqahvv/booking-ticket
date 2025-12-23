@extends('layouts.admin')

@section('title', 'Quản lý điểm đón – trả')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách mã giảm giá</h3>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Luôn mang đến những trải nghiệm tốt nhất</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card-box table-responsive">
                                        <p class="text-muted font-13 m-b-30">
                                            Trang quản lý mã giảm giá cho phép admin tạo, chỉnh sửa và xóa.
                                        </p>

                                        <table id="datatable-buttons" class="table table-striped table-bordered"
                                            style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center align-middle">Mã giảm giá</th>
                                                    <th class="text-center align-middle">Mô tả</th>
                                                    <th class="text-center align-middle">Loại giảm giá</th>
                                                    <th class="text-center align-middle">Giá trị giảm</th>
                                                    <th class="text-center align-middle">Ngày hiệu lực</th>
                                                    <th class="text-center align-middle">Hạn mức khách hàng</th>
                                                    <th class="text-center align-middle">Tổng hạn mức</th>
                                                    <th class="text-center align-middle">Số tiền tối thiểu</th>
                                                    <th class="text-center align-middle">Trạng thái</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($promotions as $pro)
                                                    <tr id="promotion-row-{{ $pro->id }}">
                                                        <td class="text-center align-middle">{{ $pro->code }}</td>
                                                        <td class="text-center align-middle">{{ $pro->description }}</td>
                                                        <td class="text-center align-middle">
                                                            @if ($pro->discount_type === 'percentage')
                                                                Giảm theo %
                                                            @elseif($pro->discount_type === 'fixed_amount')
                                                                Giảm tiền mặt
                                                            @else
                                                                {{ $pro->discount_type }}
                                                            @endif
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            @if ($pro->discount_type === 'percentage')
                                                                {{ $pro->discount_value }}%
                                                            @elseif($pro->discount_type === 'fixed_amount')
                                                                {{ number_format($pro->discount_value, 0, ',', '.') }} VNĐ
                                                            @else
                                                                {{ $pro->discount_value }}
                                                            @endif
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            {{ \Carbon\Carbon::parse($pro->valid_from)->format('d-m-Y') }} -
                                                            {{ \Carbon\Carbon::parse($pro->valid_to)->format('d-m-Y') }}
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            {{ $pro->usage_limit_per_user ?? '-' }}</td>
                                                        <td class="text-center align-middle">
                                                            {{ $pro->total_usage_limit ?? '-' }}
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            {{ number_format($pro->min_booking_amount, 0, ',', '.') }} VNĐ
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <select class="status-select form-control"
                                                                data-id="{{ $pro->id }}">
                                                                <option value="1"
                                                                    {{ $pro->is_active ? 'selected' : '' }}>Còn sử dụng
                                                                </option>
                                                                <option value="0"
                                                                    {{ !$pro->is_active ? 'selected' : '' }}>Không sử dụng
                                                                </option>
                                                            </select>
                                                        </td>

                                                        <td class="text-center">
                                                            <a class="btn btn-success btn-sm btn-update-promotion"
                                                                data-toggle="modal"
                                                                data-target="#modalUpdate-{{ $pro->id }}"
                                                                style="color: #fff">
                                                                <i class="fa fa-edit"></i>Chỉnh sửa
                                                            </a>
                                                        </td>

                                                        <td class="text-center">
                                                            <a class="btn btn-danger btn-sm btn-delete-promotion"
                                                                data-id="{{ $pro->id }}"
                                                                data-delete-url="{{ route('admin.promotions.delete', $pro->id) }}"
                                                                style="color: #fff">
                                                                <i class="fa fa-trash"></i> Xóa
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    {{-- MODAL UPDATE --}}
                                                    <div class="modal fade" id="modalUpdate-{{ $pro->id }}"
                                                        tabindex="-1">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">

                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Chỉnh sửa mã giảm giá</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-dismiss="modal">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <form id="update-promo-{{ $pro->id }}"
                                                                        class="form-horizontal"
                                                                        enctype="multipart/form-data">
                                                                        @csrf

                                                                        <div class="form-group">
                                                                            <label>Mã giảm giá</label>
                                                                            <input type="text" class="form-control"
                                                                                name="code" value="{{ $pro->code }}"
                                                                                required>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Mô tả</label>
                                                                            <input type="text" class="form-control"
                                                                                name="description"
                                                                                value="{{ $pro->description }}">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Loại giảm giá</label>
                                                                            <select name="discount_type"
                                                                                class="form-control" required>
                                                                                <option value="percentage"
                                                                                    {{ $pro->discount_type === 'percentage' ? 'selected' : '' }}>
                                                                                    Giảm %</option>
                                                                                <option value="fixed_amount"
                                                                                    {{ $pro->discount_type === 'fixed_amount' ? 'selected' : '' }}>
                                                                                    Giảm tiền mặt</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Giá trị giảm</label>
                                                                            <input type="number" class="form-control"
                                                                                name="discount_value"
                                                                                value="{{ $pro->discount_value }}"
                                                                                required>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Ngày hiệu lực từ</label>
                                                                            <input type="date" class="form-control"
                                                                                name="valid_from"
                                                                                value="{{ $pro->valid_from->format('Y-m-d') }}"
                                                                                required>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Ngày hiệu lực đến</label>
                                                                            <input type="date" class="form-control"
                                                                                name="valid_to"
                                                                                value="{{ $pro->valid_to->format('Y-m-d') }}"
                                                                                required>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Hạn mức sử dụng trên khách hàng</label>
                                                                            <input type="number" class="form-control"
                                                                                name="usage_limit_per_user"
                                                                                value="{{ $pro->usage_limit_per_user }}">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Tổng hạn mức sử dụng</label>
                                                                            <input type="number" class="form-control"
                                                                                name="total_usage_limit"
                                                                                value="{{ $pro->total_usage_limit }}">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Số tiền tối thiểu</label>
                                                                            <input type="number" class="form-control"
                                                                                name="min_booking_amount"
                                                                                value="{{ $pro->min_booking_amount }}">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Trạng thái</label>
                                                                            <select name="is_active" class="form-control"
                                                                                required>
                                                                                <option value="1"
                                                                                    {{ $pro->is_active ? 'selected' : '' }}>
                                                                                    Còn sử dụng</option>
                                                                                <option value="0"
                                                                                    {{ !$pro->is_active ? 'selected' : '' }}>
                                                                                    Không sử dụng</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-dismiss="modal">Đóng</button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Lưu</button>
                                                                        </div>

                                                                    </form>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>

                        @endsection
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
