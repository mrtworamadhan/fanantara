<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// --- Controllers ---
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\SocialiteController;

// --- Livewire Components ---
use App\Livewire\PosPage;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\SetupProfile;
use App\Livewire\Auth\ActivationPayment;
use App\Livewire\Member\Dashboard;
use App\Livewire\Member\Profile;

// --- Livewire Public Pages ---
use App\Livewire\PublicPages\HomePage;
use App\Livewire\PublicPages\ProductPage;
use App\Livewire\PublicPages\ArticlePage;
use App\Livewire\PublicPages\ArticleDetailPage;
use App\Livewire\PublicPages\AboutPage;
use App\Livewire\PublicPages\AdArtPage;
use App\Livewire\PublicPages\PrivacyPolicyPage;
use App\Livewire\PublicPages\TermsOfServicePage;
use App\Livewire\PublicPages\ContactPage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. PUBLIC ROUTES
Route::get('/', HomePage::class)->name('home');
Route::get('/products', ProductPage::class)->name('products');
Route::get('/articles', ArticlePage::class)->name('articles');
Route::get('/articles/{slug}', ArticleDetailPage::class)->name('articles.show');
Route::get('/about', AboutPage::class)->name('about');
Route::get('/adart', AdArtPage::class)->name('adart');
Route::get('/contact', ContactPage::class)->name('contact');
Route::get('/privacy-policy', PrivacyPolicyPage::class)->name('privacy');
Route::get('/terms-of-service', TermsOfServicePage::class)->name('terms');

Route::get('/news', function () {
    return view('welcome');
})->name('news.detail');

// 2. GUEST ROUTES (Login, Register, Socialite)
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');

    // Google Auth
    Route::get('/auth/google/redirect', [SocialiteController::class, 'redirect'])->name('login.google');
    Route::get('/auth/google/callback', [SocialiteController::class, 'callback']);
});

// 3. AUTHENTICATED ROUTES
Route::middleware('auth')->group(function () {

    // --- Logout ---
    Route::get('/logout', function () {
        auth()->logout();
        return redirect('/login');
    })->name('logout');

    // --- Member Onboarding & Profile ---
    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/setup', SetupProfile::class)->name('setup');
        Route::get('/activation', ActivationPayment::class)->name('activation');
        Route::get('/profile', Profile::class)->name('profile');
        Route::get('/member/mutation', \App\Livewire\Member\Mutation::class)->name('mutation');
        Route::get('/member/marketplace', \App\Livewire\Member\Marketplace::class)->name('marketplace');
        Route::get('/member/news', \App\Livewire\Member\News::class)->name('news');
    });

    // --- Dashboard (Strict Access) ---
    Route::get('/dashboard', Dashboard::class)
        ->middleware(['verified', 'member.check'])
        ->name('dashboard');

    // --- POS System ---
    Route::get('/pos', PosPage::class)->name('pos');

    // --- Printing / PDF Generation ---
    Route::prefix('print')->name('print.')->group(function () {

        // Via PdfController
        Route::controller(PdfController::class)->group(function () {
            Route::get('/invoice/{order}', 'printInvoice')->name('invoice');
            Route::get('/po/{purchase}', 'printPurchaseOrder')->name('po');
            Route::get('/card/{member}', 'printIdCard')->name('card');
        });

        // Via PosController
        Route::get('/receipt/{order}', [PosController::class, 'printReceipt'])->name('receipt');
    });

    //Financial Report
    Route::prefix('reports')->name('reports.')->group(function () {

        Route::get('/finance-bundle/{period}', [ReportController::class, 'downloadBundle'])->name('finance-bundle');
    });
});
