<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

Route::get("/", [PostController::class, "index"])->name("posts.index");

Route::middleware(["guest"])->group(function () {
    Route::get("/register", [AuthController::class, "showRegister"])->name("register");
    Route::post("/register", [AuthController::class, "register"]);
    Route::get("/login", [AuthController::class, "showLogin"])->name("login");
    Route::post("/login", [AuthController::class, "login"]);
});

Route::middleware(["auth"])->group(function () {
    Route::post("/logout", [AuthController::class, "logout"])->name("logout");

    Route::get("/posts/create", [PostController::class, "create"])->name("posts.create");
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store')->middleware('auth');
});

Route::get("/posts/{post:slug}", [PostController::class, "show"])->name("posts.show");

Route::middleware(["auth", "revisore"])->group(function () {
    Route::get("/reviser/dashboard", [DashboardController::class, "reviserDashboard"])->name("reviser.dashboard");
    Route::post("/reviser/posts/{post}/accept", [DashboardController::class, "acceptPost"])->name("reviser.accept");
    Route::post("/reviser/posts/{post}/reject", [DashboardController::class, "rejectPost"])->name("reviser.reject");
});