<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostAdminController extends Controller
{
    public function index()
    {
        $posts = Post::with('author')->get();
        return view('admin.pages.posts', compact('posts'));
    }

    public function updatePost(Request $request)
    {
        $post = Post::findOrFail($request->id);

        $post->title   = $request->input('title');
        $post->excerpt = $request->input('excerpt');
        $post->content = $request->input('content');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '-' . $file->getClientOriginalName();

            Storage::disk('public')->putFileAs('uploads/images', $file, $filename);

            $post->image_url = 'uploads/images/' . $filename;
        }

        $post->save();

        return response()->json([
            "status"     => "success",
            "title"      => $post->title,
            "content"    => $post->content,
            "excerpt"    => Str::limit(strip_tags($post->excerpt), 300),
            "image_url"  => $post->image_url,
        ]);
    }

    public function toggleStatus($id)
    {
        $post = Post::findOrFail($id);

        $post->status = $post->status === 'draft' ? 'published' : 'draft';
        $post->save();

        return response()->json([
            'success' => true,
            'status' => $post->status
        ]);
    }

    public function showFormAddPost()
    {
        $categories = Category::all();
        return view('admin.pages.post-add', compact('categories'));
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {

            $file = $request->file('upload');
            $filename = time() . '-' . $file->getClientOriginalName();

            $path = $file->storeAs('uploads/ckeditor', $filename, 'public');

            return response()->json([
                'default' => asset('storage/' . $path)
            ]);
        }

        return response()->json([], 400);
    }

    public function addPost(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $slug = Str::slug($request->title);

        $originalSlug = $slug;
        $count = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $content = $request->input('content');

        $imagePath = null;
        if ($request->hasFile('images')) {
            $filename = time() . '-' . $request->images->getClientOriginalName();
            $imagePath = $request->images->storeAs('uploads/images', $filename, 'public');
        }

        $post = Post::create([
            'title'        => $request->input('title'),
            'slug'         => $slug,
            'excerpt'      => $request->input('excerpt'),
            'content'      => $content,
            'image_url'    => $imagePath,
            'user_id'      => Auth::id(),
            'status'       => 'draft',
            'published_at' => now(),
        ]);

        $post->categories()->sync([$request->category_id]);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Thêm bài viết thành công!');
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);

        if ($post->image_url && file_exists(public_path($post->image_url))) {
            unlink(public_path($post->image_url));
        }

        $post->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa bài viết thành công'
        ]);
    }
}
