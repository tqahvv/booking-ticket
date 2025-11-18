@php
    $defaultImageUrl = asset('storage/uploads/images/default.jpg');
@endphp

<div class="single-sidebar-widget popular-post-widget">
    <h4 class="popular-title">Bài Viết Liên Quan</h4>
    <div class="popular-post-list" id="related-posts-container">
        @forelse ($relatedPosts ?? [] as $relatedPost)
            <div class="related-post-entry d-flex flex-row align-items-start">
                <div class="related-thumb-wrapper">
                    <a href="{{ route('post.detail', $relatedPost->slug) }}" class="ajax-post-link"
                        data-slug="{{ $relatedPost->slug }}">
                        <img class="related-thumb-img-strict" src="{{ $relatedPost->image_url }}"
                            alt="{{ $relatedPost->title }}"
                            onerror="this.onerror=null; this.src='{{ $defaultImageUrl }}';">
                    </a>
                </div>

                <div class="details flex-grow-1">
                    <a href="{{ route('post.detail', $relatedPost->slug) }}" class="ajax-post-link text-dark-hover"
                        data-slug="{{ $relatedPost->slug }}">
                        <h6 class="related-post-title-optimized">{{ Str::limit(strip_tags($relatedPost->title), 50) }}
                        </h6>
                    </a>
                    <p class="related-post-date-optimized">
                        {{ $relatedPost->published_at ? $relatedPost->published_at->diffForHumans() : 'Vừa đăng' }}</p>
                </div>
            </div>
        @empty
            <p class="p-3 text-muted">Không tìm thấy bài viết nào khác trong danh mục này.</p>
        @endforelse
    </div>
</div>
