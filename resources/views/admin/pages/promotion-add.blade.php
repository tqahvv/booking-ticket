@extends('layouts.admin')

@section('title', 'Thêm mã giảm giá')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Thêm mã giảm giá</h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Thêm mới mã giảm giá</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <br />

                            <form id="add-promotion-form" action="{{ route('admin.promotions.add') }}"
                                data-store-url="{{ route('admin.promotions.store') }}" method="POST"
                                class="form-horizontal form-label-left" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label>Mã giảm giá</label>
                                    <input type="text" name="code" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <input type="text" name="description" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Loại giảm giá</label>
                                    <select name="discount_type" class="form-control" required>
                                        <option value="percentage">Giảm %</option>
                                        <option value="fixed_amount">Giảm tiền mặt</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Giá trị giảm</label>
                                    <input type="number" name="discount_value" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Ngày hiệu lực từ</label>
                                    <input type="date" name="valid_from" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Ngày hiệu lực đến</label>
                                    <input type="date" name="valid_to" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Hạn mức sử dụng trên khách hàng</label>
                                    <input type="number" name="usage_limit_per_user" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Tổng hạn mức sử dụng</label>
                                    <input type="number" name="total_usage_limit" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Số tiền tối thiểu</label>
                                    <input type="number" name="min_booking_amount" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <select name="is_active" class="form-control" required>
                                        <option value="1">Còn sử dụng</option>
                                        <option value="0">Không sử dụng</option>
                                    </select>
                                </div>

                                <div class="ln_solid"></div>

                                <div class="item form-group">
                                    <div class="col-md-6 col-sm-6 offset-md-3">
                                        <button class="btn btn-primary btn_reset" type="reset">Reset</button>
                                        <button type="submit" class="btn btn-success">Thêm</button>
                                    </div>
                                </div>

                            </form>

                            <div id="form-result" class="mt-3"></div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
