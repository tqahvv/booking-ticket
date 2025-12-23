@extends('layouts.admin')

@section('title', 'Quản lý tuyến đường')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách tuyến đường</h3>
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
                                            Trang quản lý bài viết cho phép admin tạo, chỉnh sửa và xóa các bài viết.
                                        </p>
                                        <table id="datatable-buttons" class="table table-striped table-bordered"
                                            style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center align-middle">#</th>
                                                    <th class="text-center align-middle">Điểm đi</th>
                                                    <th class="text-center align-middle">Điểm đến</th>
                                                    <th class="text-center align-middle">Nhà xe</th>
                                                    <th class="text-center align-middle">Khoảng cách (km)</th>
                                                    <th class="text-center align-middle">Mô tả</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($routes as $route)
                                                    <tr id="route-row-{{ $route->id }}">
                                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                                        <td class="text-center align-middle">
                                                            {{ $route->origin->name ?? '---' }}</td>
                                                        <td class="text-center align-middle">
                                                            {{ $route->destination->name ?? '---' }}</td>
                                                        <td class="text-center align-middle">
                                                            {{ $route->operator->name ?? '---' }}</td>
                                                        <td class="text-center align-middle">{{ $route->distance }}</td>
                                                        <td class="text-center align-middle">{{ $route->description }}</td>
                                                        <td class="text-center">
                                                            <a class="btn btn-success btn-sm btn-update-route"
                                                                data-toggle="modal"
                                                                data-target="#modalUpdate-{{ $route->id }}"
                                                                style="color: #fff">
                                                                <i class="fa fa-edit"></i>Chỉnh sửa
                                                            </a>
                                                        </td>
                                                        <td class="text-center">
                                                            <a class="btn btn-danger btn-sm btn-delete-route"
                                                                data-id="{{ $route->id }}" style="color: #fff">
                                                                <i class="fa fa-trash"></i>Xóa
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <div class="modal fade" id="modalUpdate-{{ $route->id }}"
                                                        tabindex="-1" aria-labelledby="routeModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="routeModalLabel">Chỉnh
                                                                        sửa</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form id="update-route-{{ $route->id }}"
                                                                        method="POST"
                                                                        class="form-horizontal form-label-left"
                                                                        enctype="multipart/form-data">
                                                                        @csrf
                                                                        <div class="item form-group">
                                                                            <label
                                                                                class="col-form-label col-md-3 col-sm-3 label-align">Điểm
                                                                                đi <span class="required">*</span></label>
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <select name="origin_location_id"
                                                                                    class="form-control" required>
                                                                                    @foreach ($locations as $loc)
                                                                                        <option value="{{ $loc->id }}"
                                                                                            {{ $loc->id == $route->origin_location_id ? 'selected' : '' }}>
                                                                                            {{ $loc->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="item form-group">
                                                                            <label
                                                                                class="col-form-label col-md-3 col-sm-3 label-align">Điểm
                                                                                đến <span class="required">*</span></label>
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <select name="destination_location_id"
                                                                                    class="form-control" required>
                                                                                    @foreach ($locations as $loc)
                                                                                        <option value="{{ $loc->id }}"
                                                                                            {{ $loc->id == $route->destination_location_id ? 'selected' : '' }}>
                                                                                            {{ $loc->name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="item form-group">
                                                                            <label
                                                                                class="col-form-label col-md-3 col-sm-3 label-align">Khoảng
                                                                                cách (km) <span
                                                                                    class="required">*</span></label>
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <input type="number" name="distance"
                                                                                    class="form-control"
                                                                                    value="{{ $route->distance }}"
                                                                                    required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label
                                                                                class="col-form-label col-md-3 col-sm-3 label-align">Mô
                                                                                tả <span class="required">*</span></label>
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <textarea name="description" class="form-control">{{ $route->description }}</textarea>
                                                                            </div>
                                                                        </div>

                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Quay lại</button>
                                                                    <button type="submit"
                                                                        form="update-route-{{ $route->id }}"
                                                                        class="btn btn-primary btn-update-submit-route">Lưu</button>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
