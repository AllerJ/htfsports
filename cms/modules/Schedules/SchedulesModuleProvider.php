<?php

namespace Cms\Modules\Schedules;

use Illuminate\Routing\Router;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SchedulesModuleProvider extends ServiceProvider
{
    public function register()
    {
        // Publishes
        $this->publishes([
            __DIR__.'/Publishes/app' => base_path('app'),
            __DIR__.'/Publishes/routes' => base_path('routes'),
            __DIR__.'/Publishes/resources' => base_path('resources'),
        ]);

        // Load events
        $this->app->events->listen('eloquent.saved: Cms\Modules\Schedules\Models\Schedule', 'Cms\Modules\Schedules\Models\Schedule@afterSaved');

        // Load Routes
        $this->app->router->group(['middleware' => ['web']], function ($router) {
            require __DIR__.'/Routes/web.php';
        });

        // View namespace
        $this->app->view->addNamespace('schedules', __DIR__.'/Views');

        // Configs
        $this->app->config->set('cms.modules.schedules', include(__DIR__.'/config.php'));
    }
}
