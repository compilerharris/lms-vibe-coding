<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LeadApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes for lead management
Route::prefix('v1')->group(function () {
    // Create a new lead
    Route::post('/leads', [LeadApiController::class, 'store']);
    
    // Get available developers and their projects
    Route::get('/developers-projects', [LeadApiController::class, 'getDevelopersAndProjects']);
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'service' => 'Lead Assignment API'
    ]);
});
