@extends('layouts.app')

@section('content')

    Crea un nuovo Articolo

    
        @csrf
        
            Titolo dell'articolo
            
        

        
            Contenuto
            
        

        
            ⚠️ L'articolo verrà inviato in stato pending e sarà visibile sul blog solo dopo l'approvazione di un revisore.
        

        
            Invia per Revisione
        
    

@endsection