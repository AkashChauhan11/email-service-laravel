<?php

use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Route;

Route::post('/send-email', [EmailController::class, 'send']);
