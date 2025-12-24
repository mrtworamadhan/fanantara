<?php

namespace App\Providers;

use App\Models\Payment;
use App\Observers\PaymentObserver;
use Illuminate\Support\ServiceProvider;
use App\Models\Purchase;
use App\Models\Order;
use App\Observers\PurchaseObserver;
use App\Observers\OrderObserver;
use App\Models\SavingTransaction;
use App\Observers\SavingTransactionObserver;
use App\Models\Member;
use App\Observers\MemberObserver;
use App\Models\StockOpname;
use App\Observers\StockOpnameObserver;
use Filament\Auth\Http\Responses\Contracts\LoginResponse as LoginResponseContract;
use App\Http\Responses\LoginResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LoginResponseContract::class, LoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Purchase::observe(PurchaseObserver::class);
        Order::observe(OrderObserver::class);
        SavingTransaction::observe(SavingTransactionObserver::class);
        Member::observe(MemberObserver::class);
        Payment::observe(PaymentObserver::class);
        StockOpname::observe(StockOpnameObserver::class);
    }
}
