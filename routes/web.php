<?php

use App\Http\Controllers\PrizeController;
use Illuminate\Support\Facades\Route;

Route::get('/api/prizes', [PrizeController::class, 'index']);
Route::post('/api/draw/{game}', [PrizeController::class, 'draw']);

Route::view('/{any?}', 'app')->where('any', '.*');
