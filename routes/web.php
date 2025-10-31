<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard if authenticated, otherwise to login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Student management routes
    Route::resource('students', StudentController::class);
    
    // Book management routes
    Route::resource('books', BookController::class);
    
    // Loan management routes
    Route::resource('loans', LoanController::class)->except(['edit', 'update', 'destroy']);
    Route::patch('/loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');
    Route::get('/loans-history', [LoanController::class, 'history'])->name('loans.history');
    Route::get('/loans-overdue', [LoanController::class, 'overdue'])->name('loans.overdue');
    
    // AJAX routes for search
    Route::get('/api/students/search', [LoanController::class, 'searchStudents'])->name('students.search');
    Route::get('/api/books/search', [LoanController::class, 'searchBooks'])->name('books.search');
});
