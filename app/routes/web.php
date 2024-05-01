<?php

use App\Http\Controllers\LinkShortenerController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', [LinkShortenerController::class, 'start']);

Route::post('/process-link', [LinkShortenerController::class, 'process'])->name('process-link');
Route::get('{code}', [LinkShortenerController::class, 'redirectToLink']);
