<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/coop/token', 'coopcontroller@index');
Route::post('/coop/callback', 'coopcontroller@callback');
