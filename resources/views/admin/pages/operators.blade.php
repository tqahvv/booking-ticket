@extends('layouts.admin')

@section('title', 'Quản lý nhà xe')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách nhà xe</h3>
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

                                        <table id="datatable-buttons" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Hãng xe</th>
                                                    <th>Mô tả</th>
                                                    <th>Đánh giá</th>
                                                    <th>Liên hệ</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($operators as $op)
                                                    <tr id="operator-row-{{ $op->id }}">
                                                        <td>{{ $op->name }}</td>
                                                        <td>{{ $op->description }}</td>
                                                        <td>{{ $op->rating }}</td>
                                                        <td>{{ $op->contact_info }}</td>

                                                        <td style="text-align:center">
                                                            <a class="btn btn-app" data-toggle="modal"
                                                                data-target="#updateOperator-{{ $op->id }}">
                                                                <i class="fa fa-edit"></i> Chỉnh sửa
                                                            </a>
                                                        </td>

                                                        <td style="text-align:center">
                                                            <a class="btn btn-app btn-delete-operator"
                                                                data-id="{{ $op->id }}"
                                                                data-url="{{ route('admin.operators.delete', $op->id) }}">
                                                                <i class="fa fa-trash"></i> Xóa
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    {{-- Modal Update --}}
                                                    <div class="modal fade" id="updateOperator-{{ $op->id }}">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form id="update-operator-{{ $op->id }}"
                                                                    enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Cập nhật nhà xe</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal">
                                                                            <span>&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">

                                                                        <div class="form-group">
                                                                            <label>Hãng xe</label>
                                                                            <input type="text" name="name"
                                                                                class="form-control"
                                                                                value="{{ $op->name }}" required>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Mô tả</label>
                                                                            <textarea name="description" class="form-control">{{ $op->description }}</textarea>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Rating</label>
                                                                            <input type="number" name="rating"
                                                                                class="form-control" min="0"
                                                                                max="5" step="0.1"
                                                                                value="{{ $op->rating }}" required>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Số điện thoại</label>
                                                                            <input type="text" name="contact_info"
                                                                                class="form-control"
                                                                                value="{{ $op->contact_info }}" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Lưu</button>
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Đóng</button>
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
                    @endsection
                </div>
            </div>
        </div>
    </div>
</div>
