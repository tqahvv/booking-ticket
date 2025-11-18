@extends('layouts.client')

@section('title', 'Chi tiết bài viết')
@section('content')
    <section class="post-content-area single-post-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 posts-list">
                    @php
                        $defaultImageUrl = asset('storage/uploads/images/default.jpg');
                    @endphp
                    <div id="main-post-content">
                        <div class="single-post row">
                            <div class="col-lg-12">
                                <h3 class="mt-20 mb-20" id="post-title">{{ strip_tags($post->title) }}</h3>
                                <p id="post-excerpt">{{ strip_tags($post->excerpt) }}</p>
                            </div>

                            <div class="col-lg-12">
                                <div class="feature-img post-feature-wrapper">
                                    <img class="img-fluid post-feature-img custom-aspect-ratio-main" id="post-image"
                                        src="{{ $post->image_url }}" alt="{{ $post->title }}"
                                        onerror="this.onerror=null; this.src='{{ $defaultImageUrl }}';">
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-9">
                                <div class="post-body mt-4" id="post-body">
                                    {!! $post->processed_content !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 sidebar-widgets">
                    <div class="widget-wrap">
                        <div id="related-posts-container">
                            @include('client.partials.related-posts', [
                                'post' => $post,
                                'relatedPosts' => $relatedPosts,
                            ])
                        </div>

                        <div class="single-sidebar-widget post-category-widget">
                            <h4 class="category-title">Danh mục bài viết</h4>
                            <ul class="cat-list">
                                <li>
                                    <a href="#" class="d-flex justify-content-between">
                                        <p>Technology</p>
                                        <p>37</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="d-flex justify-content-between">
                                        <p>Lifestyle</p>
                                        <p>24</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="d-flex justify-content-between">
                                        <p>Fashion</p>
                                        <p>59</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="d-flex justify-content-between">
                                        <p>Art</p>
                                        <p>29</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="d-flex justify-content-between">
                                        <p>Food</p>
                                        <p>15</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="d-flex justify-content-between">
                                        <p>Architecture</p>
                                        <p>09</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="d-flex justify-content-between">
                                        <p>Adventure</p>
                                        <p>44</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        {{-- <div class="single-sidebar-widget tag-cloud-widget">
                            <h4 class="tagcloud-title">Tag Clouds</h4>
                            <ul>
                                <li><a href="#">Technology</a></li>
                                <li><a href="#">Fashion</a></li>
                                <li><a href="#">Architecture</a></li>
                                <li><a href="#">Fashion</a></li>
                                <li><a href="#">Food</a></li>
                                <li><a href="#">Technology</a></li>
                                <li><a href="#">Lifestyle</a></li>
                                <li><a href="#">Art</a></li>
                                <li><a href="#">Adventure</a></li>
                                <li><a href="#">Food</a></li>
                                <li><a href="#">Lifestyle</a></li>
                                <li><a href="#">Adventure</a></li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
