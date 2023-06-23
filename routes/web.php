<?php

use App\Http\Controllers\WorkerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [WorkerController::class, 'index'])->name('index');

Route::get('/export', [WorkerController::class, 'generatePDF'])->name('export');

Route::get('/save-selected-exports', [WorkerController::class, 'saveSelectedExports'])->name('save-selected-exports');

Route::get('/clear-selected-exports', [WorkerController::class, 'clearSelectedExports'])->name('clear-selected-exports');

Route::get('/employee-profile/{emp_no}', [WorkerController::class, 'showProfile'])->name('employee-profile');

Route::get('/export-employee', [WorkerController::class, 'generatePDFEmployee'])->name('export-employee');
