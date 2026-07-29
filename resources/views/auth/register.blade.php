@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-slate-800 p-8 rounded-xl border border-slate-700 shadow-xl">
    <h2 class="text-2xl font-bold text-cyan-400 mb-6 text-center">Registrati</h2>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-slate-300 mb-1">Nome Completo</label>
            <input type="text" name="name" id="name" required class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-slate-100 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
            @error('name') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-1">Email</label>
            <input type="email" name="email" id="email" required class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-slate-100 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
            @error('email') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-1">Password</label>
            <input type="password" name="password" id="password" required class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-slate-100 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
            @error('password') <span class="text-rose-400 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1">Conferma Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-slate-100 focus:ring-2 focus:ring-cyan-500 focus:outline-none">
        </div>

        <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-medium py-2.5 rounded-lg transition">
            Crea Account
        </button>
    </form>
</div>
@endsection