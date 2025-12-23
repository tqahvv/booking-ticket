@extends('layouts.admin')

@section('title', 'Thêm mới tuyến đường')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Thêm mới tuyến đường</h3>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Thêm mới tuyến đường</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />
                            <form id="add-route-form" class="form-horizontal form-label-left" enctype="multipart/form-data"
                                data-store-url="{{ route('admin.routes.store') }}"
                                data-index-url="{{ route('admin.routes.index') }}">
                                @csrf
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Điểm đi <span
                                            class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="origin_location_id" id="origin-location" class="form-control"
                                            required>
                                            <option value="">-- Chọn điểm đi --</option>
                                            @foreach ($locations as $loc)
                                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Điểm đến <span
                                            class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="destination_location_id" class="form-control" required>
                                            <option value="">-- Chọn điểm đến --</option>
                                            @foreach ($locations as $loc)
                                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Khoảng cách (km) <span
                                            class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="distance" class="form-control" required>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
