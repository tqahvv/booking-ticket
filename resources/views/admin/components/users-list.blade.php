<div class="row user-list">
    @forelse ($users as $user)
        <div class="col-md-4 col-sm-4 profile_details">
            <div class="well profile_view">
                <div class="col-sm-12 user-box">
                    <div class="left">
                        <h4 class="brief text-uppercase"><i>{{ optional($user->role)->name }}</i></h4>
                        <ul class="list-unstyled user-info-list">
                            <li>
                                <i class="fa fa-user"></i><strong>Họ tên:
                                </strong>{{ $user->name }}
                            </li>
                            <li>
                                <i class="fa fa-envelope"></i><strong>Email:
                                </strong>{{ $user->email }}
                            </li>
                            <li>
                                <i class="fa fa-map-marker"></i><strong>Địa chỉ:
                                </strong>{{ $user->address }}
                            </li>
                            <li>
                                <i class="fa fa-phone"></i><strong>Số điện thoại:
                                </strong>{{ $user->phone }}
                            </li>
                        </ul>
                    </div>

                    <div class="right">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/uploads/avatars/avatar-default.jpg') }}"
                            alt="avatar" class="avatar-img img-fluid">
                    </div>
                </div>

                @php
                    $currentRole = auth()->user()->role->name;
                    $targetRole = $user->role->name;

                    $canManage = false;

                    if ($currentRole === 'admin' && $targetRole !== 'admin') {
                        $canManage = true;
                    }

                    if ($currentRole === 'staff' && $targetRole === 'customer') {
                        $canManage = true;
                    }
                @endphp

                @if ($canManage)
                    <div class="bottom text-center">
                        <div class="col-sm-12 emphasis">
                            @if ($user->status == 'banned')
                                <button type="button" class="btn btn-success btn-sm changeStatus"
                                    data-userid="{{ $user->id }}" data-status="active">
                                    <i class="fa fa-check"></i> Bỏ chặn
                                </button>
                            @else
                                <button type="button" class="btn btn-warning btn-sm changeStatus"
                                    data-userid="{{ $user->id }}" data-status="banned">
                                    <i class="fa fa-check"></i> Chặn
                                </button>
                            @endif

                            @if ($user->status == 'deleted')
                                <button type="button" class="btn btn-success btn-sm changeStatus"
                                    data-userid="{{ $user->id }}" data-status="active">
                                    <i class="fa fa-check"></i> Khôi phục
                                </button>
                            @else
                                <button type="button" class="btn btn-danger btn-sm changeStatus"
                                    data-userid="{{ $user->id }}" data-status="deleted">
                                    <i class="fa fa-check"></i> Xóa
                                </button>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    @empty
        <div class="col-12">
            <p>Không có người dùng nào.</p>
        </div>
    @endforelse
</div>
