<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Cyber Blog') }}</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col justify-between font-sans">

    <!-- NAVBAR -->
    <nav class="bg-slate-800 border-b border-slate-700 shadow-lg">
        <div class="max-w-7xl mx-mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">🛡️</span>
                    <a href="{{ route('posts.index') }}" class="text-xl font-bold tracking-wide text-cyan-400">CyberBlog</a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('posts.index') }}" class="hover:text-cyan-400 transition">Home</a>
                    
                    @auth
                        <a href="{{ route('posts.create') }}" class="bg-cyan-600 hover:bg-cyan-500 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition">Scrivi Articolo</a>
                        
                        @if(Auth::user()->is_revisore || Auth::user()->is_admin)
                            <a href="{{ route('reviser.dashboard') }}" class="bg-amber-600 hover:bg-amber-500 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition relative">
                                Dashboard Revisore
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-slate-400 hover:text-rose-400 ml-2">Logout</button>
                        </form>
                    @else
                        <a href="#" class="text-slate-300 hover:text-white text-sm">Accedi</a>
                        <a href="#" class="bg-slate-700 hover:bg-slate-600 px-3 py-1.5 rounded-lg text-sm font-medium">Registrati</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- MESSAGGI DI FEEDBACK -->
    <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
        @if(session('success'))
            <div class="bg-emerald-900/80 border border-emerald-500 text-emerald-200 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-900/80 border border-rose-500 text-rose-200 px-4 py-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- CONTENUTO PAGINA -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-slate-950 border-t border-slate-800 text-slate-400 py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center sm:flex sm:justify-between sm:text-left items-center">
            <div>
                <p class="font-semibold text-slate-200">Progetto Finale Specializzazione Cyber Security</p>
                <p class="text-xs text-slate-500 mt-1">Sviluppato da Daniele Bergamaschi • DevSecOps & Laravel Architecture</p>
            </div>
            <div class="mt-4 sm:mt-0 text-xs text-slate-500">
                <span class="inline-flex items-center px-2 py-1 rounded bg-slate-800 text-cyan-400 font-mono">
                    🔒 OWASP Compliant Pipeline
                </span>
            </div>
        </div>
    </footer>

</body>
</html>