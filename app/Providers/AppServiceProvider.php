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
        // Configure Blade engine as the default view handler
        // Blade is already the default in Laravel, but we can customize it here if needed
        
        // You can add custom Blade directives here
        // Example: Blade::directive('datetime', function ($expression) {
        //     return "<?php echo ($expression)->format('m/d/Y H:i'); ?>";
        // });
    }
}
