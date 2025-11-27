@extends('layouts.admin')

@section('title', 'Quản lý bài viết')

@section('content')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Thêm bài viết</h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Thêm mới bài viết</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />
                            <form action="{{ route('admin.post.add') }}" id="add-post" method="POST"
                                class="form-horizontal form-label-left" enctype="multipart/form-data">
                                @csrf
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">
                                        Tiêu đề <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <textarea id="post-title" name="title" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">
                                        Danh mục <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="category_id" id="post-category" class="form-control" required>
                                            <option value="">Chọn danh mục</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">
                                        Mô tả ngắn <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <textarea id="post-excerpt" name="excerpt" class="form-control" rows="4"></textarea>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">
                                        Nội dung <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <textarea id="post-content" name="content" class="form-control" rows="8"></textarea>
                                    </div>
                                </div>

                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align">
                                        Hình ảnh
                                    </label>
                                    <div class="col-md-6 col-sm-6">
                                        <label class="custom-file-upload" for="post-images">Chọn ảnh</label>
                                        <input type="file" id="post-images" style="display: none" name="images"
                                            accept="image/*" multiple>
                                        <div id="image-preview-container"
                                            style="display:flex; gap:10px; margin-top:10px; flex-wrap: wrap;">
                                        </div>
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

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <script>
        let editorTitle, editorExcerpt, editorContent;

        ClassicEditor
            .create(document.querySelector('#post-title'))
            .then(e => editorTitle = e)
            .catch(err => console.error(err));

        ClassicEditor
            .create(document.querySelector('#post-excerpt'))
            .then(e => editorExcerpt = e)
            .catch(err => console.error(err));

        class MyUploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }

            upload() {
                return this.loader.file.then(file => {
                    const data = new FormData();
                    data.append('upload', file);

                    return fetch("{{ route('admin.post.upload-image') }}", {
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: data
                        })
                        .then(response => response.json())
                        .then(result => {
                            return {
                                default: result.default
                            };
                        });
                });
            }

            abort() {}
        }

        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get("FileRepository").createUploadAdapter = loader => {
                return new MyUploadAdapter(loader);
            };
        }


        ClassicEditor
            .create(document.querySelector('#post-content'), {
                extraPlugins: [MyCustomUploadAdapterPlugin]
            })
            .then(e => editorContent = e)
            .catch(err => console.error(err));
    </script>

@endsection
