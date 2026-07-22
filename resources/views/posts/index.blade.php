@extends('layouts.app')

@section('content')

    Articoli Recenti sulla Sicurezza
    Rimani aggiornato sulle ultime notizie, vulnerabilità e guide DevSecOps.


@if($posts->isEmpty())
    
        Nessun articolo pubblicato al momento.
    
@else
    
        @foreach($posts as $post)
            
                
                    Cyber Security
                    
                        
                            {{ $post->title }}
                        
                    
                    
                        {{ Str::limit($post->content, 120) }}
                    
                
                
                    Autore: {{ $post->user->name ?? 'Anonimo' }}
                    {{ $post->created_at->format('d/m/Y') }}
                
            
        @endforeach
    
@endif
@endsection