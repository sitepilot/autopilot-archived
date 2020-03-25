<?php

namespace App\Providers;

use App\ServerApp;
use App\ServerHost;
use App\ServerUser;
use App\ProjectHour;
use App\ServerGroup;
use App\ServerAuthKey;
use App\ServerDatabase;
use App\Observers\SlugName;
use App\ServerFirewallRule;
use App\Observers\UserObserver;
use App\Observers\VarsObserver;
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
        # User observers
        ProjectHour::observe(UserObserver::class);

        # Slug name
        ServerGroup::observe(SlugName::class);
        ServerHost::observe(SlugName::class);
        ServerUser::observe(SlugName::class);
        ServerApp::observe(SlugName::class);
        ServerFirewallRule::observe(SlugName::class);

        # Var observers
        ServerGroup::observe(VarsObserver::class);
        ServerHost::observe(VarsObserver::class);
        ServerUser::observe(VarsObserver::class);
        ServerApp::observe(VarsObserver::class);
        ServerAuthKey::observe(VarsObserver::class);
        ServerDatabase::observe(VarsObserver::class);
        ServerFirewallRule::observe(VarsObserver::class);
    }
}
