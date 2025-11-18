<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
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

            // Lưu vào public disk
            $path = $file->storeAs('uploads/ckeditor', $filename, 'public');

            // CKEditor 5 yêu cầu trả về "default"
            return response()->json([
                'default' => asset('storage/' . $path)
            ]);
        }

        return response()->json([], 400);
    }
}
