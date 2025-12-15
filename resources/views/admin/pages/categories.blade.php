@extends('layouts.admin')

@section('title', 'Quản lý danh mục bài viết')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách danh mục bài viết</h3>
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
                                            Trang quản lý danh mục bài viết cho phép admin tạo, chỉnh sửa và xóa địa điểm.
                                        </p>

                                        <table id="datatable-buttons" class="table table-striped table-bordered"
                                            style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Tên danh mục</th>
                                                    <th>Slug</th>
                                                    <th>Mô tả</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($categories as $cat)
                                                    <tr id="category-row-{{ $cat->id }}">
                                                        <td>{{ $cat->name }}</td>
                                                        <td>{{ $cat->slug }}</td>
                                                        <td>{{ $cat->description }}</td>

                                                        <td style="display: flex; justify-content: center">
                                                            <a class="btn btn-app btn-update-category" data-toggle="modal"
                                                                data-target="#modalUpdate-{{ $cat->id }}">
                                                                <i class="fa fa-edit"></i>Chỉnh sửa
                                                            </a>
                                                        </td>

                                                        <td style="display: flex; justify-content: center">
                                                            <a class="btn btn-app btn-delete-category"
                                                                data-id="{{ $cat->id }}"
                                                                data-url="{{ route('admin.categories.delete', $cat->id) }}">
                                                                <i class="fa fa-trash"></i> Xóa
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    {{-- MODAL UPDATE --}}
                                                    <div class="modal fade" id="modalUpdate-{{ $cat->id }}"
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
                                                                    <form data-id="{{ $cat->id }}"
                                                                        data-url="{{ route('admin.categories.update', $cat->id) }}"
                                                                        method="POST"
                                                                        class="form-horizontal update-category-form"
                                                                        enctype="multipart/form-data">
                                                                        @csrf

                                                                        <div class="form-group">
                                                                            <label>Tên danh mục</label>
                                                                            <input type="text" class="form-control"
                                                                                name="name" value="{{ $cat->name }}"
                                                                                required>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label>Mô tả</label>
                                                                            <input type="text" class="form-control"
                                                                                name="description"
                                                                                value="{{ $cat->description }}" required>
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
