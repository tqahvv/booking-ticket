@extends('layouts.admin')

@section('title', 'Quản lý lịch chạy')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách lịch chạy</h3>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12">
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
                                            Trang quản lý bài viết cho phép admin tạo, chỉnh sửa và xóa lịch chạy.
                                        </p>
                                        <table id="datatable-buttons" class="table table-striped table-bordered"
                                            style="width: 100%;">
                                            <thead>
                                                <tr role="row">
                                                    <th>Tuyến</th>
                                                    <th>Nhà xe</th>
                                                    <th>Loại xe</th>
                                                    <th>Giờ xuất bến</th>
                                                    <th>Thời gian chạy (phút)</th>
                                                    <th>Ngày chạy</th>
                                                    <th>Giá vé</th>
                                                    <th>Số ghế</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($templates as $t)
                                                    <tr id="template-row-{{ $t->id }}">
                                                        <td>{{ $t->route->description }}</td>
                                                        <td>{{ $t->operator->name }}</td>
                                                        <td>{{ $t->vehicleType->name }}</td>
                                                        <td>{{ $t->departure_time }}</td>
                                                        <td>{{ $t->travel_duration_minutes }}</td>
                                                        <td>{{ implode(',', json_decode($t->running_days, true)) }}</td>
                                                        <td>{{ number_format($t->base_fare, 0, ',', '.') }} VNĐ</td>
                                                        <td>{{ $t->default_seats }}</td>

                                                        <td
                                                            style="display: flex; justify-content: center; align-content: center">
                                                            <a class="btn btn-app btn-update-schedule-template"
                                                                data-toggle="modal"
                                                                data-target="#modalUpdate-{{ $t->id }}"><i
                                                                    class="fa fa-edit"></i>Chỉnh sửa</a>
                                                        </td>

                                                        <td
                                                            style="display: flex; justify-content: center; align-content: center">
                                                            <a class="btn btn-app btn-delete-schedule-template"
                                                                data-id="{{ $t->id }}">
                                                                <i class="fa fa-trash"></i>Xóa
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    <!-- Modal Edit -->
                                                    <div class="modal fade" id="modalUpdate-{{ $t->id }}"
                                                        tabindex="-1" aria-labelledby="scheduleTemplateModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-xl">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="scheduleTemplateModalLabel">
                                                                        Chỉnh
                                                                        sửa</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form id="update-schedule-template-{{ $t->id }}"
                                                                        class="form-horizontal form-label-left"
                                                                        enctype="multipart/form-data">
                                                                        @csrf
                                                                        <div class="modal-body">
                                                                            <div class="form-group">
                                                                                <label>Giờ xuất bến</label>
                                                                                <input type="time" name="departure_time"
                                                                                    class="form-control"
                                                                                    value="{{ $t->departure_time }}"
                                                                                    required>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Thời gian chạy (phút)</label>
                                                                                <input type="number"
                                                                                    name="travel_duration_minutes"
                                                                                    class="form-control"
                                                                                    value="{{ $t->travel_duration_minutes }}"
                                                                                    required>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Ngày chạy</label>
                                                                                @foreach ([1, 2, 3, 4, 5, 6, 7] as $day)
                                                                                    <label><input type="checkbox"
                                                                                            name="running_days[]"
                                                                                            value="{{ $day }}"
                                                                                            {{ in_array($day, json_decode($t->running_days, true)) ? 'checked' : '' }}>
                                                                                        {{ $day }}</label>
                                                                                @endforeach
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Giá vé</label>
                                                                                <input type="number" name="base_fare"
                                                                                    class="form-control"
                                                                                    value="{{ $t->base_fare }}" required>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Số ghế</label>
                                                                                <input type="number" name="default_seats"
                                                                                    class="form-control"
                                                                                    value="{{ $t->default_seats }}"
                                                                                    required>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Start Date</label>
                                                                                <input type="date" name="start_date"
                                                                                    class="form-control"
                                                                                    value="{{ $t->start_date }}" required>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>End Date</label>
                                                                                <input type="date" name="end_date"
                                                                                    class="form-control"
                                                                                    value="{{ $t->end_date }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-dismiss="modal">Đóng</button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary">Lưu</button>
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
                        @endsection
