<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; //Import Schema

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191); //Solved by increasing StringLength
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\Services\Logic\IUserAppService', 'App\Services\Logic\UserAppService');
        $this->app->bind('App\Contracts\Repositories\IUserRepository', 'App\Repositories\UserRepository');

        $this->app->bind('App\Contracts\Services\Logic\IAgentAppService', 'App\Services\Logic\AgentAppService');
        $this->app->bind('App\Contracts\Repositories\IAgentRepository', 'App\Repositories\AgentRepository');

        $this->app->bind('App\Contracts\Services\Logic\ISubModuleAppService', 'App\Services\Logic\SubModuleAppService');
        $this->app->bind('App\Contracts\Repositories\ISubModuleRepository', 'App\Repositories\SubModuleRepository');
    }

}
