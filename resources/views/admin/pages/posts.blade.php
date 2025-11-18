@extends('layouts.admin')

@section('title', 'Quản lý bài viết')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Danh sách bài viết</h3>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Luôn mang đến những trải nghiệm tốt nhất</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card-box table-responsive">
                                        <p class="text-muted font-13 m-b-30">
                                            Trang quản lý bài viết cho phép admin tạo, chỉnh sửa và xóa các bài viết.
                                        </p>
                                        <table id="datatable-buttons" class="table table-striped table-bordered"
                                            style="width: 100%;">
                                            <thead>
                                                <tr role="row">
                                                    <th style="width: 10%;">Hình ảnh</th>
                                                    <th style="width: 20%;">Tiêu đề</th>
                                                    <th style="width: 39%;">Nội dung</th>
                                                    <th style="width: 11%;">Tác giả</th>
                                                    <th style="width: 9%;">Ngày đăng</th>
                                                    <th style="width: 7%;">Trạng thái</th>
                                                    <th style="width: 3%;"></th>
                                                    <th style="width: 3%;"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($posts as $post)
                                                    <tr id="post-row-{{ $post->id }}">
                                                        <td>
                                                            <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                                                                class="" style="width: 100px; height: 100px;">
                                                        </td>
                                                        <td>{{ strip_tags($post->title) }}</td>
                                                        <td>{{ Str::limit(strip_tags($post->content), 300) }}</td>
                                                        <td
                                                            style="display: flex; justify-content: center; align-content: center">
                                                            {{ $post->author->name }}
                                                        </td>
                                                        <td
                                                            style="display: flex; justify-content: center; align-content: center">
                                                            {{ $post->published_at->format('d-m-Y') }}

                                                        </td>
                                                        <td
                                                            style="display: flex; justify-content: center; align-content: center">
                                                            <button
                                                                class="btn btn-sm btn-toggle-status 
                                                                {{ $post->status === 'published' ? 'btn-success' : 'btn-secondary' }}"
                                                                data-id="{{ $post->id }}">
                                                                {{ $post->status }}
                                                            </button>
                                                        </td>
                                                        <td
                                                            style="display: flex; justify-content: center; align-content: center">
                                                            <a class="btn btn-app btn-update-post" data-toggle="modal"
                                                                data-target="#modalUpdate-{{ $post->id }}">
                                                                <i class="fa fa-edit"></i>Chỉnh sửa
                                                            </a>
                                                        </td>
                                                        <td
                                                            style="display: flex; justify-content: center; align-content: center">
                                                            <a class="btn btn-app btn-delete-post"
                                                                data-id="{{ $post->id }}">
                                                                <i class="fa fa-trash"></i>Xóa
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    <div class="modal fade" id="modalUpdate-{{ $post->id }}"
                                                        tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="postModalLabel">Chỉnh
                                                                        sửa</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form id="update-post-{{ $post->id }}"
                                                                        method="POST"
                                                                        class="form-horizontal form-label-left"
                                                                        enctype="multipart/form-data">
                                                                        @csrf
                                                                        <div class="item form-group">
                                                                            <label
                                                                                class="col-form-label col-md-3 col-sm-3 label-align">Tiêu
                                                                                đề <span class="required">*</span></label>
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <textarea id="post-title-{{ $post->id }}" name="title" class="form-control" rows="3">
                                                                                    {{ $post->title }}
                                                                                </textarea>
                                                                            </div>
                                                                        </div>

                                                                        <div class="item form-group">
                                                                            <label
                                                                                class="col-form-label col-md-3 col-sm-3 label-align">Mô
                                                                                tả ngắn <span
                                                                                    class="required">*</span></label>
                                                                            <div class="col-md-6 col-sm-6">
                                                                                <textarea id="post-excerpt-{{ $post->id }}" name="excerpt" class="form-control" rows="4">
                                                                                    {{ $post->excerpt }}
                                                                                </textarea>
                                                                            </div>
                                                                        </div>

                                                                        <div class="item form-group">
                                                                            <label
                                                                                class="col-form-label col-md-3 col-sm-3 label-align">Nội
                                                                                dung <span class="required">*</span></label>
                                                                            <div class="col-md-6 col-sm-6">
                                                                                @php
                                                                                    $content = $post->content;
                                                                                    $content = preg_replace(
                                                                                        '/src="(uploads\/[^"]+)"/',
                                                                                        'src="' .
                                                                                            asset('storage/$1') .
                                                                                            '"',
                                                                                        $content,
                                                                                    );
                                                                                @endphp
                                                                                <textarea id="post-content-{{ $post->id }}" name="content" class="form-control" rows="8">
                                                                                    {!! $content !!}
                                                                                </textarea>
                                                                            </div>
                                                                        </div>

                                                                        <div class="item form-group">
                                                                            <label
                                                                                class="col-form-label col-md-3 col-sm-3 label-align"
                                                                                for="post-image">Hình ảnh</label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <img src="{{ $post->image_url }}"
                                                                                    alt="{{ $post->name }}"
                                                                                    id="image-preview-{{ $post->id }}"
                                                                                    class="image-preview">
                                                                                <label class="custom-file-upload"
                                                                                    for="post-image-{{ $post->id }}">
                                                                                    Chọn ảnh</label>
                                                                                <input type="file" class="post-image"
                                                                                    data-id="{{ $post->id }}"
                                                                                    id="post-image-{{ $post->id }}"
                                                                                    name="image" accept="image/*"
                                                                                    style="display: none">
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Quay lại</button>
                                                                    <button type="button"
                                                                        class="btn btn-primary btn-update-submit-post"
                                                                        data-id="{{ $post->id }}">Chỉnh sửa</button>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        let editors = {};

        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($posts as $post)
                ClassicEditor
                    .create(document.querySelector('#post-title-{{ $post->id }}'))
                    .then(editor => {
                        editors['title-{{ $post->id }}'] = editor;
                    })
                    .catch(error => console.error(error));

                ClassicEditor
                    .create(document.querySelector('#post-excerpt-{{ $post->id }}'))
                    .then(editor => {
                        editors['excerpt-{{ $post->id }}'] = editor;
                    })
                    .catch(error => console.error(error));

                ClassicEditor
                    .create(document.querySelector('#post-content-{{ $post->id }}'))
                    .then(editor => {
                        editors['content-{{ $post->id }}'] = editor;
                    })
                    .catch(error => console.error(error));
            @endforeach
        });
    </script>
@endsection
