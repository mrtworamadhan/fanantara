<?php

use App\Http\Controllers\ReportController;
use App\Livewire\Member\Marketplace;
use App\Livewire\Member\Mutation;
use App\Livewire\Member\News;
use App\Livewire\Member\NotificationInbox;
use App\Livewire\Member\Shop\Cart;
use App\Livewire\Member\Shop\Checkout;
use App\Livewire\Member\Shop\MyOrders;
use App\Livewire\Member\Shop\OrderSuccess;
use App\Mail\WelcomeMemberMail;
use App\Models\Member;
use Barryvdh\DomPDF\Facade\Pdf;
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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. PUBLIC ROUTES
Route::get('/', function () {
    return view('welcome');
});

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
        Route::get('/member/mutation',Mutation::class)->name('mutation');
        Route::get('/member/marketplace',Marketplace::class)->name('marketplace');
        Route::get('/member/news',News::class)->name('news');
        Route::get('/shop/cart', Cart::class)->name('shop.cart');
        Route::get('/checkout', Checkout::class)->name('checkout');
        Route::get('/order-success/{orderId}', OrderSuccess::class)->name('order.success');
        Route::get('/orders', MyOrders::class)->name('orders.index');
        Route::get('/notifications', NotificationInbox::class)->name('notifications');

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