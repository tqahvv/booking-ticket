@extends('layouts.client')

@section('title', 'Bài viết')
@section('breadcrumb', 'Bài viết')
@section('content')
    <section class="top-category-widget-area pt-90 pb-90 "></section>

    <section class="post-content-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 posts-list">
                    @forelse ($postsByCategory as $categorySlug => $categoryData)
                        <h3 class="category-section-title mt-4 mb-3">{{ $categoryData['name'] }}</h3>
                        <div class="single-post row mb-5">
                            @foreach ($categoryData['posts'] as $post)
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="post-card h-100">
                                        <div class="feature-img">
                                            <img class="img-fluid post-thumb-img" src="{{ $post->image_url }}"
                                                alt="{{ $post->title }}">
                                        </div>
                                        <a class="posts-title" href="{{ route('post.detail', $post->slug) }}">
                                            <h3>{{ strip_tags($post->title) }}</h3>
                                        </a>
                                        <p class="excert">
                                            {{ strip_tags($post->excerpt) }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <hr class="mb-5">
                    @empty
                        <div class="text-center p-5">
                            <h4>Hiện chưa có bài viết nào được đăng trong các danh mục.</h4>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection
