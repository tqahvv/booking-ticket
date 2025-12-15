<?php

namespace App\Providers;

use App\Models\Contact;
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
        view()->composer('layouts.admin', function ($view) {
            $unreadContacts = Contact::where('is_read', 0)
                ->orderByDesc('created_at')
                ->take(5)
                ->get();

            $unreadCount = Contact::where('is_read', 0)->count();

            $view->with(compact('unreadContacts', 'unreadCount'));
        });
    }
}
