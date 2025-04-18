<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SLAController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\LogController; // Ensure this class exists in the specified namespace
use App\Http\Controllers\UserController;
use App\Http\Controllers\MailTestController;

use App\Http\Controllers\DashboardController;

// use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect('/admin/login');
});



Route::get('/export/tickets', [ExportController::class, 'export'])->name('export.tickets');

Route::resource('slas', SLAController::class);
// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Route::get('/tickets/{ticket}/export-pdf', [\App\Http\Controllers\TicketExportController::class, 'pdf'])
//     ->name('tickets.export-pdf');

// Route untuk testing email
Route::get('/mail-test', [MailTestController::class, 'index'])->name('mail-test.index');
Route::post('/mail-test/send', [MailTestController::class, 'sendTestMail'])->name('mail-test.send');
// API Route untuk testing email via AJAX jika diperlukan
Route::post('/api/mail-test/send', [MailTestController::class, 'apiSendTestMail'])->name('api.mail-test.send');