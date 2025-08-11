<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Client;
use App\Models\Invoice;
use App\Policies\v1\ClientPolicy;
use App\Policies\v1\InvoicePolicy;
use App\Policies\v1\StateOfAccountPolicy;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Client::class => ClientPolicy::class,
        Invoice::class => InvoicePolicy::class,
        'state-of-account' => StateOfAccountPolicy::class,
    ];
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
        $this->registerPolicies();
    }
}
