<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PostController extends Controller
{
    public function detail(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)
            ->with('categories')
            ->where('status', 'published')
            ->firstOrFail();

        $categoryIds = $post->categories->pluck('id')->toArray();

        $relatedPosts = Post::whereHas('categories', function ($query) use ($categoryIds) {
            $query->whereIn('categories.id', $categoryIds);
        })
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('client.pages.post-detail', compact('post', 'relatedPosts'));
    }

    public function index()
    {
        $categoriesWithPosts = Category::whereHas('posts', function ($query) {
            $query->where('status', 'published');
        })
            ->with(['posts' => function ($query) {
                $query->where('status', 'published')
                    ->latest()
                    ->limit(3);
            }])
            ->orderBy('name', 'asc')
            ->get();

        $postsByCategory = [];

        foreach ($categoriesWithPosts as $category) {
            if ($category->posts->isNotEmpty()) {
                $postsByCategory[$category->slug] = [
                    'name' => $category->name,
                    'posts' => $category->posts->map(function ($post) {
                        return (object)[
                            'title' => $post->title,
                            'excerpt' => $post->excerpt,
                            'slug' => $post->slug,
                            'image_url' => $post->image_url ?? asset('assets/client/img/blog/default-thumb.jpg'),
                        ];
                    })->toArray()
                ];
            }
        }
        return view('client.pages.post-home', compact('postsByCategory'));
    }
}
