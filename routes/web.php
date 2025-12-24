<?php

use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Livewire\PosPage;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/print/invoice/{order}', [PdfController::class, 'printInvoice'])->name('print.invoice');
    Route::get('/print/po/{purchase}', [PdfController::class, 'printPurchaseOrder'])->name('print.po');
    Route::get('/print/receipt/{order}', [PosController::class, 'printReceipt'])->name('print.receipt');
});

Route::get('/pos', PosPage::class)->middleware('auth')->name('pos');

Route::get('/print/card/{member}', [App\Http\Controllers\PdfController::class, 'printIdCard'])
    ->middleware('auth')
    ->name('print.card');