

// ============================================================
// FILE: routes/api.php
// ============================================================

use App\Http\Controllers\SensorController;
use App\Http\Controllers\FocusController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AIController;

// ESP32 Device endpoints (no auth needed — uses device token)
Route::prefix('sensor')->group(function () {
    Route::post('/store',           [SensorController::class, 'store']);
    Route::post('/distraction',     [SensorController::class, 'logDistraction']);
    Route::get('/latest/{user_id}', [SensorController::class, 'latest']);
});

// Authenticated API routes
Route::middleware('auth:sanctum')->group(function () {

    // Focus
    Route::prefix('focus')->group(function () {
        Route::get('/score',   [FocusController::class, 'currentScore']);
        Route::get('/session', [FocusController::class, 'currentSession']);
        Route::post('/start',  [FocusController::class, 'startSession']);
        Route::post('/stop',   [FocusController::class, 'stopSession']);
    });

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/daily',   [ReportController::class, 'daily']);
        Route::get('/weekly',  [ReportController::class, 'weekly']);
        Route::get('/monthly', [ReportController::class, 'monthly']);
    });

    // Couple mode
    Route::prefix('couple')->group(function () {
        Route::post('/generate-code',     [CoupleController::class, 'generateCode']);
        Route::post('/link',              [CoupleController::class, 'link']);
        Route::get('/status',             [CoupleController::class, 'status']);
        Route::post('/save-token',        [CoupleController::class, 'saveToken']);
        Route::post('/unlink',            [CoupleController::class, 'unlink']);
        Route::post('/test-notification', [CoupleController::class, 'testNotification']);
    });
});