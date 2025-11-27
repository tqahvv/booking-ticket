@extends('layouts.admin')

@section('title', 'Thêm chuyến xe')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Thêm chuyến xe</h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Thêm mới chuyến xe</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />
                            <form action="{{ route('admin.scheduleTemplates.add') }}"
                                data-store-url="{{ route('admin.scheduleTemplates.store') }}"
                                data-index-url="{{ route('admin.scheduleTemplates.index') }}" id="add-schedule-template"
                                method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data">
                                @csrf

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Tuyến <span
                                            class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="route_id" class="form-control" required>
                                            <option value="">Chọn tuyến</option>
                                            @foreach ($routes as $route)
                                                <option value="{{ $route->id }}">{{ $route->origin->name }} →
                                                    {{ $route->destination->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Hãng vận hành --}}
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Hãng vận hành <span
                                            class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="operator_id" class="form-control" required>
                                            <option value="">Chọn hãng</option>
                                            @foreach ($operators as $op)
                                                <option value="{{ $op->id }}">{{ $op->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Loại xe --}}
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Loại xe <span
                                            class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <select name="vehicle_type_id" class="form-control" required>
                                            <option value="">Chọn loại xe</option>
                                            @foreach ($vehicleTypes as $vt)
                                                <option value="{{ $vt->id }}">{{ $vt->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Thời gian khởi hành --}}
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Giờ khởi hành <span
                                            class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="time" name="departure_time" class="form-control" required>
                                    </div>
                                </div>

                                {{-- Thời gian di chuyển --}}
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Thời gian di chuyển (phút)
                                        <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="travel_duration_minutes" class="form-control"
                                            min="1" required>
                                    </div>
                                </div>

                                {{-- Ngày chạy --}}
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Ngày chạy <span
                                            class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="checkbox">
                                            @foreach (['1' => 'Thứ 2', '2' => 'Thứ 3', '3' => 'Thứ 4', '4' => 'Thứ 5', '5' => 'Thứ 6', '6' => 'Thứ 7', '7' => 'Chủ nhật'] as $key => $day)
                                                <label><input type="checkbox" name="running_days[]"
                                                        value="{{ $key }}"> {{ $day }}</label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Giá cơ bản --}}
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Giá cơ bản (VND) <span
                                            class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="base_fare" class="form-control" min="0" required>
                                    </div>
                                </div>

                                {{-- Số ghế mặc định --}}
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Số ghế mặc định <span
                                            class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="number" name="default_seats" class="form-control" min="1"
                                            required>
                                    </div>
                                </div>

                                {{-- Ngày bắt đầu --}}
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Ngày bắt đầu <span
                                            class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="date" name="start_date" class="form-control" required>
                                    </div>
                                </div>

                                {{-- Ngày kết thúc --}}
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">Ngày kết thúc</label>
                                    <div class="col-md-6 col-sm-6">
                                        <input type="date" name="end_date" class="form-control">
                                    </div>
                                </div>

                                <div class="ln_solid"></div>

                                <div class="item form-group">
                                    <div class="col-md-6 col-sm-6 offset-md-3">
                                        <button class="btn btn-primary btn_reset" type="reset">Reset</button>
                                        <button type="submit" class="btn btn-success" id="submit-schedule">Thêm</button>
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
