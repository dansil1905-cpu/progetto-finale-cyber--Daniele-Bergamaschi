<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Mostra solo gli articoli approvati in Home Page.
     */
    public function index()
    {
        $posts = Post::where('status', 'approved')->latest()->get();

        return view('posts.index', compact('posts'));
    }

    /**
     * Form per creare un articolo.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Salvataggio dell'articolo con stato iniziale 'pending'.
     */
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

    /**
     * Dettaglio dell'articolo tramite Slug.
     */
    public function show(Post $post)
    {
        if ($post->status !== 'approved' && (!auth()->check() || (!auth()->user()->is_revisore && !auth()->user()->is_admin))) {
            abort(404);
        }

        return view('posts.show', compact('post'));
    }
}