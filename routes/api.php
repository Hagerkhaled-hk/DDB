<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BranchController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/admin/signup', [AuthController::class, 'signup']);
Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/branches', [BranchController::class, 'create']);
Route::get('/branches', [BranchController::class, 'show']);

// Protected routes with Sanctum auth
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/students', [StudentController::class, 'store']);
    Route::get('/students', [StudentController::class, 'index']);
    Route::post('/records', [RecordController::class, 'store']);
    Route::post('/requests', [RequestController::class, 'store']);
    Route::get('/requests', [RequestController::class, 'index']);
    Route::get('/myrequests', [RequestController::class, 'myrequests']);
    Route::post('/requests/status', [RequestController::class, 'updateStatus']);
});