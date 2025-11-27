@extends('layouts.admin')

@section('title', 'Quản lý loại xe')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách loại xe</h3>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Quản lý các loại xe</h2>
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
                                            Trang quản lý loại xe cho phép admin tạo, chỉnh sửa và xóa loại xe.
                                        </p>
                                        <table id="datatable-buttons" class="table table-striped table-bordered"
                                            style="width: 100%;">
                                            <thead>
                                                <tr role="row">
                                                    <th>Tên loại xe</th>
                                                    <th>Số ghế</th>
                                                    <th>Số tầng</th>
                                                    <th>Mô tả</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($vehicleTypes as $vt)
                                                    <tr id="vehicleType-row-{{ $vt->id }}">
                                                        <td>{{ $vt->name }}</td>
                                                        <td>{{ $vt->capacity_total }}</td>
                                                        <td>{{ $vt->number_of_floors }}</td>
                                                        <td>{{ $vt->description }}</td>
                                                        <td>
                                                            <a class="btn btn-app btn-update-vehicleType"
                                                                data-toggle="modal"
                                                                data-target="#modalUpdate-{{ $vt->id }}"><i
                                                                    class="fa fa-edit"></i>Chỉnh sửa</a>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-app btn-delete-vehicleType"
                                                                data-id="{{ $vt->id }}"
                                                                data-delete-url="{{ route('admin.vehicleTypes.delete', $vt->id) }}"><i
                                                                    class="fa fa-trash"></i> Xóa</a>
                                                        </td>
                                                    </tr>

                                                    <!-- Modal Edit -->
                                                    <div class="modal fade" id="modalUpdate-{{ $vt->id }}"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Chỉnh sửa</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form id="update-vehicleType-{{ $vt->id }}"
                                                                        class="form-horizontal form-label-left">
                                                                        @csrf
                                                                        <div class="form-group">
                                                                            <label>Tên loại xe</label>
                                                                            <input type="text" name="name"
                                                                                class="form-control"
                                                                                value="{{ $vt->name }}" required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Số ghế</label>
                                                                            <input type="number" name="capacity_total"
                                                                                class="form-control"
                                                                                value="{{ $vt->capacity_total }}" required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Số tầng</label>
                                                                            <input type="number" name="number_of_floors"
                                                                                class="form-control"
                                                                                value="{{ $vt->number_of_floors }}"
                                                                                required>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Mô tả</label>
                                                                            <textarea name="description" class="form-control">{{ $vt->description }}</textarea>
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
