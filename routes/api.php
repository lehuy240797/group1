<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CallbackRequestController; // Import Controller mới
// Định nghĩa route API mẫu (nếu cần)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/callback-request', [CallbackRequestController::class, 'store']);