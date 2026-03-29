<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusinessController;

Route::get('/', [BusinessController::class, 'dashboard'])->name('dashboard');

Route::get('/businesses', [BusinessController::class, 'index'])->name('businesses.index');

Route::get('/import', [BusinessController::class, 'showImportForm'])->name('businesses.import.form');
Route::post('/import', [BusinessController::class, 'import'])->name('businesses.import');

Route::get('/duplicates', [BusinessController::class, 'duplicates'])->name('businesses.duplicates');
Route::post('/duplicates/merge/{groupId}', [BusinessController::class, 'mergeDuplicates'])->name('businesses.merge');
Route::post('/duplicates/merge-all', [BusinessController::class, 'mergeAllDuplicates'])->name('businesses.mergeAll');

Route::get('/reports', [BusinessController::class, 'reports'])->name('businesses.reports');

Route::get('/reports/download/city-wise', [BusinessController::class, 'downloadCityWiseReport'])->name('reports.download.citywise');
Route::get('/reports/download/category-city-wise', [BusinessController::class, 'downloadCategoryCityWiseReport'])->name('reports.download.categorycitywise');
Route::get('/reports/download/category-area-wise', [BusinessController::class, 'downloadCategoryAreaWiseReport'])->name('reports.download.categoryareawise');
Route::get('/reports/download/duplicates', [BusinessController::class, 'downloadDuplicateReport'])->name('reports.download.duplicates');
Route::get('/reports/download/incomplete', [BusinessController::class, 'downloadIncompleteReport'])->name('reports.download.incomplete');
Route::get('/reports/download/summary', [BusinessController::class, 'downloadSummaryReport'])->name('reports.download.summary');

Route::post('/businesses/clear-all', [BusinessController::class, 'clearAllRecords'])->name('businesses.clearAll');