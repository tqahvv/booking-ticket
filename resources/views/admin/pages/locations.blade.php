@extends('layouts.admin')

@section('title', 'Quản lý điểm đón – trả')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách điểm đón – trả</h3>
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
                                            Trang quản lý điểm đón – trả cho phép admin tạo, chỉnh sửa và xóa địa điểm.
                                        </p>

                                        <table id="datatable-buttons" class="table table-striped table-bordered"
                                            style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th class="text-center align-middle">Tên điểm</th>
                                                    <th class="text-center align-middle">Thành phố</th>
                                                    <th class="text-center align-middle">Địa chỉ</th>
                                                    <th class="text-center align-middle">Tỉnh/Thành</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($locations as $l)
                                                    <tr id="location-row-{{ $l->id }}">
                                                        <td class="text-center align-middle">{{ $l->name }}</td>
                                                        <td class="text-center align-middle">{{ $l->city }}</td>
                                                        <td class="text-center align-middle">{{ $l->address }}</td>
                                                        <td class="text-center align-middle">{{ $l->province }}</td>

                                                        <td class="text-center">
                                                            <a class="btn btn-success btn-sm btn-update-location"
                                                                data-toggle="modal"
                                                                data-target="#modalUpdate-{{ $l->id }}"
                                                                style="color: #fff">
                                                                <i class="fa fa-edit"></i>Chỉnh sửa
                                                            </a>
                                                        </td>

                                                        <td class="text-center">
                                                            <a class="btn btn-danger btn-sm btn-delete-location"
                                                                data-id="{{ $l->id }}"
                                                                data-delete-url="{{ route('admin.locations.delete', $l->id) }}"
                                                                style="color: #fff">
                                                                <i class="fa fa-trash"></i> Xóa
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    {{-- MODAL UPDATE --}}
                                                    <div class="modal fade" id="modalUpdate-{{ $l->id }}"
                                                        tabindex="-1">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">

                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Chỉnh sửa</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-dismiss="modal">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <form id="update-location-{{ $l->id }}"
                                                                        class="form-horizontal"
                                                                        enctype="multipart/form-data">
                                                                        @csrf

                                                                        <div class="form-group">
                                                                            <label>Tên điểm</label>
                                                                            <input type="text" class="form-control"
                                                                                name="name" value="{{ $l->name }}"
                                                                                required>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Thành phố</label>
                                                                            <input type="text" class="form-control"
                                                                                name="city" value="{{ $l->city }}"
                                                                                required>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Địa chỉ</label>
                                                                            <input type="text" class="form-control"
                                                                                name="address" value="{{ $l->address }}"
                                                                                required>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Tỉnh/Thành</label>
                                                                            <input type="text" class="form-control"
                                                                                name="province" value="{{ $l->province }}"
                                                                                required>
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

                        @endsection
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
