<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FinancialController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

// Rotta per generare il token di accesso (Login API)
Route::post('/tokens/create', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Le credenziali fornite non sono corrette.'],
        ]);
    }

    $token = $user->createToken('travel-agent-token')->plainTextToken;

    return response()->json([
        'success' => true,
        'token' => $token
    ]);
});

// Rotta per ottenere i dati dell'utente autenticato via API
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotte protette della Financial App (travel-agent-api)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/financial/records', [FinancialController::class, 'index']);
    Route::post('/financial/records', [FinancialController::class, 'store']);
});