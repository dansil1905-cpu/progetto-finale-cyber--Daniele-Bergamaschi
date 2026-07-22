<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard del revisore: mostra solo gli articoli in attesa di approvazione.
     */
    public function reviserDashboard()
    {
        $pendingPosts = Post::where('status', 'pending')->latest()->get();

        return view('reviser.dashboard', compact('pendingPosts'));
    }

    /**
     * Approva un articolo per la pubblicazione.
     */
    public function acceptPost(Post $post)
    {
        $post->update(['status' => 'approved']);

        return redirect()->back()->with('success', "L'articolo '{$post->title}' è stato approvato e pubblicato.");
    }

    /**
     * Rifiuta un articolo.
     */
    public function rejectPost(Post $post)
    {
        $post->update(['status' => 'rejected']);

        return redirect()->back()->with('error', "L'articolo '{$post->title}' è stato rifiutato.");
    }
}