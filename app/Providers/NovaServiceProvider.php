<?php

namespace App\Providers;

use Laravel\Nova\Nova;
use Illuminate\Support\Facades\Gate;
use App\Nova\Metrics\ProjectTotalMetric;
use App\Nova\Metrics\ServerAppsTotalMetric;
use App\Nova\Metrics\ProjectHoursTotalMetric;
use App\Nova\Metrics\ProjectBalanceTotalMetric;
use App\Nova\Metrics\ProjectBillableHoursMetric;
use Laravel\Nova\NovaApplicationServiceProvider;
use App\Nova\Metrics\ProjectHoursNonBillableMetric;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Configure the Nova authorization services.
     *
     * @return void
     */
    protected function authorization()
    {
        $this->gate();

        Nova::auth(function ($request) {
            return true;
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new ServerAppsTotalMetric,
            new ProjectTotalMetric,
            new ProjectBalanceTotalMetric,
            new ProjectHoursTotalMetric,
            new ProjectBillableHoursMetric,
            new ProjectHoursNonBillableMetric
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
