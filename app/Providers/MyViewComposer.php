<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MyViewComposer extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('myviewcomposera',function(){
            //return new TestService();
            return new \App\Http\ViewComposers\MyViewComposer;
        });

        $this->app->bind('App\Contracts\ComposerContract', function()
        {
            return new \App\Http\ViewComposers\MyViewComposer;
        });
    }
}
