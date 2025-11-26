<div class="modal fade" id="modalAddTemplate" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="add-template-form">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Template mới</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tuyến</label>
                        <select name="route_id" class="form-control" required>
                            @foreach ($routes as $r)
                                <option value="{{ $r->id }}">{{ $r->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nhà xe</label>
                        <select name="operator_id" class="form-control" required>
                            @foreach ($operators as $op)
                                <option value="{{ $op->id }}">{{ $op->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Loại xe</label>
                        <select name="vehicle_type_id" class="form-control" required>
                            @foreach ($vehicleTypes as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Giờ xuất bến</label>
                        <input type="time" name="departure_time" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Thời gian chạy (phút)</label>
                        <input type="number" name="travel_duration_minutes" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Ngày chạy</label>
                        @foreach ([1, 2, 3, 4, 5, 6, 7] as $day)
                            <label><input type="checkbox" name="running_days[]" value="{{ $day }}">
                                {{ $day }}</label>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <label>Giá vé</label>
                        <input type="number" name="base_fare" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Số ghế</label>
                        <input type="number" name="default_seats" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>
