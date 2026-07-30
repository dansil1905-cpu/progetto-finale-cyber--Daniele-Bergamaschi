<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::where('status', 'approved')->latest()->get();

        return view('posts.index', compact('posts'));
    }


    public function create()
    {
        return view('posts.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Post::create([
            'title'     => $request->title,
            'slug'      => Str::slug($request->title) . '-' . time(),
            'content'   => $request->content,
            'status'    => 'pending',
            'id_utente' => auth()->id(),
        ]);

        return redirect()->route('posts.index')->with('success', "Articolo inviato con successo! È in attesa di revisione.");
    }


    public function show(Post $post)
    {
        if ($post->status !== 'approved' && (!Auth::check() || (!Auth::user()->is_revisore && !Auth::user()->is_admin))) {
    abort(404);
}

        return view('posts.show', compact('post'));
    }
}