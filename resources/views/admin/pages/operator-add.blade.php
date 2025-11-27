@extends('layouts.admin')

@section('title', 'Thêm nhà xe')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Thêm nhà xe</h3>
                </div>
            </div>

            <div class="x_panel">
                <div class="x_title">
                    <h2>Thêm mới nhà xe</h2>
                </div>

                <div class="x_content">
                    <form id="add-operator-form" data-store-url="{{ route('admin.operators.store') }}" class="form-horizontal"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label>Tên</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Mô tả</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Đánh giá</label>
                            <input type="number" name="rating" class="form-control" min="0" max="5"
                                step="0.1" required>
                        </div>

                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="text" name="contact_info" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success">Thêm</button>
                    </form>

                    <div id="form-result" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
