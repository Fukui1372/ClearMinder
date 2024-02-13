<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){
        Paginator::useBootstrap();
        \URL::forceScheme('https');
        $this->app['request']->server->set('HTTPS', 'on');
        //$test_dt = Carbon::create(2024, 2, 13, 6, 59, 50);
        //Carbon::setTestNow($test_dt);
    }
}
