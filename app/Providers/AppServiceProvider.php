<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\FeePaymentTransaction::observe(\App\Observers\FeePaymentTransactionObserver::class);
        \App\Models\Expense::observe(\App\Observers\ExpenseObserver::class);
        \App\Models\Payroll::observe(\App\Observers\PayrollObserver::class);
        \App\Models\InventoryPurchase::observe(\App\Observers\InventoryPurchaseObserver::class);
    }
}
