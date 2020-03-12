<?php

namespace App\Providers;

use App\Client;
use App\Project;
use App\ServerApp;
use App\ServerHost;
use App\ServerUser;
use App\ProjectHour;
use App\ServerAuthKey;
use App\ServerDatabase;
use App\ServerFirewallRule;
use App\Observers\UserObserver;
use App\Observers\RefIdObserver;
use Illuminate\Support\ServiceProvider;

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
    public function boot()
    {
        Client::observe(RefIdObserver::class);
        Project::observe(RefIdObserver::class);
        ServerApp::observe(RefIdObserver::class);
        ServerHost::observe(RefIdObserver::class);
        ServerUser::observe(RefIdObserver::class);
        ServerAuthKey::observe(RefIdObserver::class);
        ServerDatabase::observe(RefIdObserver::class);
        ServerFirewallRule::observe(RefIdObserver::class);
        ProjectHour::observe(UserObserver::class);
    }
}
