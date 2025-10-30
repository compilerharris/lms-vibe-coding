<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChannelPartnerController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\DeveloperDashboardController;
use App\Http\Controllers\CSDashboardController;
use App\Http\Controllers\CPDashboardController;
use App\Http\Controllers\DeveloperMappingController;
use App\Http\Controllers\DeveloperAltNameController;
use App\Http\Controllers\ApiTestController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});


// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard Routes - Role-based access
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin Dashboard (Admin only)
    Route::middleware(['role-access:admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    });
    
    // Leader Dashboard (Leader only)
    Route::middleware(['role-access:leader'])->group(function () {
        Route::get('/leader/dashboard', [DashboardController::class, 'index'])->name('leader.dashboard');
    });
    
    // Developer Dashboard (Developer only)
    Route::middleware(['role-access:developer'])->group(function () {
        Route::get('/developer/dashboard', [DeveloperDashboardController::class, 'index'])->name('developer.dashboard');
    });
    
    // Channel Partner Dashboard (Channel Partner only)
    Route::middleware(['role-access:channel_partner'])->group(function () {
        Route::get('/cp/dashboard', [CPDashboardController::class, 'index'])->name('cp.dashboard');
        Route::get('/cp/lead/{lead}', [CPDashboardController::class, 'showLead'])->name('cp.lead.show');
    });

    // CS Dashboard (CS and Biddable only)
    Route::middleware(['role-access:cs,biddable'])->group(function () {
        Route::get('/cs/dashboard', [CSDashboardController::class, 'index'])->name('cs.dashboard');
        Route::get('/cs/lead/{lead}', [CSDashboardController::class, 'showLead'])->name('cs.lead.show');
    });

    // Channel Partner Routes (Admin, Leader access only) - Read-only
    Route::middleware(['role-access:admin,leader'])->group(function () {
        Route::get('/channel-partners', [ChannelPartnerController::class, 'index'])->name('channel-partners.index');
        Route::get('/channel-partners/{channelPartner}', [ChannelPartnerController::class, 'show'])->name('channel-partners.show');
    });

    // Project Routes (Admin, Leader access only)
    Route::middleware(['role-access:admin,leader'])->group(function () {
        Route::resource('projects', ProjectController::class);
    });

    // Developer Management Routes (Admin, Leader access) - Read-only
    Route::middleware(['role-access:admin,leader'])->group(function () {
        Route::get('/developers', [DeveloperController::class, 'index'])->name('developers.index');
        Route::get('/developers/{developer}', [DeveloperController::class, 'show'])->name('developers.show');
    });

    // Lead Routes (All authenticated users, but with role-based filtering in controller)
    Route::resource('leads', LeadController::class);

            // User Management Routes (Admin only)
            Route::middleware(['role-access:admin'])->group(function () {
                Route::resource('user-management', UserManagementController::class)->parameters([
                    'user-management' => 'user'
                ]);
            });

    // Developer-Channel Partner Mapping Routes (Admin only)
    Route::middleware(['role-access:admin'])->group(function () {
        Route::get('/developer-mapping', [DeveloperMappingController::class, 'index'])->name('developer-mapping.index');
        Route::post('/developer-mapping', [DeveloperMappingController::class, 'store'])->name('developer-mapping.store');
        Route::delete('/developer-mapping', [DeveloperMappingController::class, 'destroy'])->name('developer-mapping.destroy');
        Route::get('/developer-mapping/mapped-cps', [DeveloperMappingController::class, 'getMappedChannelPartners'])->name('developer-mapping.mapped-cps');
        Route::get('/developer-mapping/available-cps', [DeveloperMappingController::class, 'getAvailableChannelPartners'])->name('developer-mapping.available-cps');
        
        // Developer Alt Names Route (Admin only)
        Route::get('/developer-alt-names', [DeveloperAltNameController::class, 'index'])->name('developer-alt-names.index');
        
        // API Testing Routes (Admin only)
        Route::get('/apis', [ApiTestController::class, 'index'])->name('apis.index');
        Route::post('/apis/test-create-lead', [ApiTestController::class, 'testCreateLead'])->name('apis.test-create-lead');
    });

    // Developer Dashboard Routes (Developer access)
    Route::middleware(['role-access:developer'])->group(function () {
        Route::get('/developer/project/{project}', [DeveloperDashboardController::class, 'showProject'])->name('developer.project.show');
    });
});
