@extends('layouts.admin')

@section('title', 'Quản lý lịch chuyến')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách lịch chuyến</h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Luôn mang đến những trải nghiệm tốt nhất</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card-box table-responsive">
                                        <p class="text-muted font-13 m-b-30">
                                            Trang quản lý lịch chuyến cho phép thêm, sửa, xóa lịch chuyến.
                                        </p>
                                        <table id="datatable-buttons" class="table table-striped table-bordered"
                                            style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th style="width: 18%;" class="text-center align-middle">Tuyến</th>
                                                    <th style="width: 10%;" class="text-center align-middle">Thời gian đi
                                                    </th>
                                                    <th style="width: 10%;" class="text-center align-middle">Thời gian đến
                                                    </th>
                                                    <th style="width: 12%;" class="text-center align-middle">Giá vé</th>
                                                    <th style="width: 20%;" class="text-center align-middle">Nhà xe</th>
                                                    <th style="width: 21%;" class="text-center align-middle">Loại xe</th>
                                                    <th style="width: 3%;" class="text-center align-middle">Số ghế</th>
                                                    <th style="width: 3%;" class="text-center align-middle"></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($schedules as $s)
                                                    <tr id="schedule-row-{{ $s->id }}">
                                                        <td class="text-center align-middle">{{ $s->route->description }}
                                                        </td>
                                                        <td class="text-center align-middle">{{ $s->departure_datetime }}
                                                        </td>
                                                        <td class="text-center align-middle">{{ $s->arrival_datetime }}</td>
                                                        <td class="text-center align-middle">
                                                            {{ number_format($s->base_fare, 0, ',', '.') }} VNĐ</td>
                                                        <td class="text-center align-middle">{{ $s->vehicleType->name }}
                                                        </td>
                                                        <td class="text-center align-middle">{{ $s->operator->name }}</td>
                                                        <td class="text-center align-middle">
                                                            {{ $s->seats_available }}/{{ $s->total_seats }}</td>
                                                        <td class="text-center">
                                                            <a class="btn btn-primary btn-sm" data-toggle="modal"
                                                                data-target="#modalUpdate-{{ $s->id }}"
                                                                style="color: #fff">
                                                                <i class="fa fa-edit"></i>Chỉnh sửa
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    {{-- Modal Update --}}
                                                    <div class="modal fade" id="modalUpdate-{{ $s->id }}"
                                                        tabindex="-1">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">

                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Cập nhật lịch chuyến</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal">
                                                                        <span>&times;</span>
                                                                    </button>
                                                                </div>

                                                                <form class="update-schedule-form"
                                                                    data-id="{{ $s->id }}" method="POST"
                                                                    data-url="{{ route('admin.schedules.update', $s->id) }}">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <div class="row mb-2">
                                                                            <div class="col-md-6">
                                                                                <label>Nhà xe</label>
                                                                                <input type="text" class="form-control"
                                                                                    value="{{ $s->operator->name ?? '-' }}"
                                                                                    readonly>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label>Loại xe</label>
                                                                                <input type="text" class="form-control"
                                                                                    value="{{ $s->vehicleType->name ?? '-' }}"
                                                                                    readonly>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Ngày giờ xuất bến</label>
                                                                            <input type="datetime-local"
                                                                                name="departure_datetime"
                                                                                class="form-control"
                                                                                value="{{ \Carbon\Carbon::parse($s->departure_datetime)->format('Y-m-d\TH:i') }}"
                                                                                required>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Giá vé (VNĐ)</label>
                                                                            <input type="number" name="base_fare"
                                                                                class="form-control"
                                                                                value="{{ $s->base_fare }}" required>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Trạng thái</label>
                                                                            <select name="status" class="form-control"
                                                                                required>
                                                                                <option value="scheduled"
                                                                                    {{ $s->status == 'scheduled' ? 'selected' : '' }}>
                                                                                    Đã lên lịch</option>
                                                                                <option value="delayed"
                                                                                    {{ $s->status == 'delayed' ? 'selected' : '' }}>
                                                                                    Bị trì hoãn</option>
                                                                                <option value="cancelled"
                                                                                    {{ $s->status == 'cancelled' ? 'selected' : '' }}>
                                                                                    Bị hủy</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Đóng</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary btn-save-schedule">Lưu
                                                                            thay đổi</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
