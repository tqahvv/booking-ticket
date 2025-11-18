<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $populars = Image::where('linked_type', 'route')->latest()->take(6)->get();
        $posts = Post::where('status', 'published')->latest()->take(6)->get();
        $from = $request->query('from');
        $to = $request->query('to');
        $date = $request->query('date');
        $seats = $request->query('seats', 1);
        return view('client.pages.home', compact('populars', 'posts', 'from', 'to', 'date', 'seats'));
    }
}
