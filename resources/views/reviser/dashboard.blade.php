@extends('layouts.app')

@section('content')

    Dashboard Revisione Articoli
    Gestisci le richieste di pubblicazione pendenti.


@if($pendingPosts->isEmpty())
    
        🎉 Nessun articolo in attesa di revisione!
    
@else
    
        @foreach($pendingPosts as $post)
            
                
                    {{ $post->title }}
                    {{ $post->content }}
                    Inviato da: {{ $post->user->name ?? 'Utente' }} • {{ $post->created_at->diffForHumans() }}
                
                
                    
                        @csrf
                        
                            Approva
                        
                    
                    
                        @csrf
                        
                            Rifiuta
                        
                    
                
            
        @endforeach
    
@endif
@endsection