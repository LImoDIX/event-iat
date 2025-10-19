<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TelegramBotController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is working',
        'timestamp' => now()->toDateTimeString()
    ]);
});

// Telegram Bot routes
Route::match(['get', 'post'], '/telegram/webhook', [TelegramBotController::class, 'webhook']);
Route::post('/telegram/set-webhook', [TelegramBotController::class, 'setWebhook']);
Route::get('/telegram/webhook-info', [TelegramBotController::class, 'getWebhookInfo']);
