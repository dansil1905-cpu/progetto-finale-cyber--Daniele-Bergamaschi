use App\Http\Controllers\PostController;
use App\Http\Controllers\DashboardController;

// Rotte Pubbliche
Route::get('/', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');

// Rotte per Utenti Autenticati (Scrittori)
Route::middleware(['auth'])->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
});

// Rotte protette per Revisori
Route::middleware(['auth', 'revisore'])->group(function () {
    Route::get('/reviser/dashboard', [DashboardController::class, 'reviserDashboard'])->name('reviser.dashboard');
    Route::post('/reviser/posts/{post}/accept', [DashboardController::class, 'acceptPost'])->name('reviser.accept');
    Route::post('/reviser/posts/{post}/reject', [DashboardController::class, 'rejectPost'])->name('reviser.reject');
});