<?php

namespace App\Providers;

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
        $this->overrideConfigValues();
        
        if (config('appsettings.log_query')) {
            \DB::listen(function($query) {
                \Log::channel('querylog')->info(
                    $query->sql,
                    $query->bindings,
                    $query->time
                ); 
            });
        }
        
    }

    protected function overrideConfigValues()
    {
        if (\Schema::hasTable('settings')){ 
            $dbSettings = \App\Models\Setting::active()->get();

            $config = [];
            foreach ($dbSettings as $temp) {
                // obj->name = original config path ex. config('debugbar.enabled')
                // obj->key = config key, ex. config('settings.debugbar_enabled')
                $config[$temp->name] = config('settings.'.$temp->key);
            }

            config($config);
        }

    }

}


