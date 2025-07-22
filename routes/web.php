<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailImportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/import-excel-from-email', [EmailImportController::class, 'importExcelFromEmail']);
