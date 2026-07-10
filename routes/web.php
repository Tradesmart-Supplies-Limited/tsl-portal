<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientComplaintController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Root
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/dashboard');

/*
|--------------------------------------------------------------------------
| Guest routes — login / register
|--------------------------------------------------------------------------
*/
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Public routes (no login) — clients submit / track complaints here
|--------------------------------------------------------------------------
*/
Route::prefix('complaints')->name('complaints.public.')->group(function () {
    Route::get('/submit', [ClientComplaintController::class, 'create'])->name('create');
    Route::post('/submit', [ClientComplaintController::class, 'store'])->name('store');
    Route::get('/submitted/{ticketNumber}', [ClientComplaintController::class, 'confirmation'])->name('confirmation');
    Route::get('/track', [ClientComplaintController::class, 'trackForm'])->name('track.form');
    Route::post('/track', [ClientComplaintController::class, 'track'])->name('track');
});

/*
|--------------------------------------------------------------------------
| Internal portal routes — requires an authenticated staff/admin user
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Client database CRUD
    Route::resource('clients', ClientController::class);

    // Reports (upload / download / delete against a client)
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('reports/{report}/download', [ReportController::class, 'download'])->name('reports.download');
    Route::delete('reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

    // Complaint center — staff view of everything clients submitted publicly
    Route::get('complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
    Route::put('complaints/{complaint}', [ComplaintController::class, 'update'])->name('complaints.update');
    Route::post('complaints/{complaint}/reply', [ComplaintController::class, 'reply'])->name('complaints.reply');
    Route::delete('complaints/{complaint}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');
});
