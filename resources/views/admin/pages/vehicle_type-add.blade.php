@extends('layouts.admin')

@section('title', 'Thêm loại xe')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Thêm loại xe</h3>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Thêm mới loại xe</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />
                            <form id="add-vehicleType-form" data-store-url="{{ route('admin.vehicleTypes.store') }}"
                                class="form-horizontal form-label-left">
                                @csrf
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Tên loại xe <span
                                            class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Số ghế <span
                                            class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="capacity_total" class="form-control" min="1"
                                            required>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Số tầng</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="number_of_floors" class="form-control" min="1">
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Mô tả</label>
                                    <div class="col-md-6 col-sm-6">
                                        <textarea name="description" class="form-control"></textarea>
                                    </div>
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
