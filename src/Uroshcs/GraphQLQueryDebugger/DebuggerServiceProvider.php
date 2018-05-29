<?php

namespace Uroshcs\GraphQLQueryDebugger;

use Illuminate\Support\ServiceProvider;

class DebuggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->environment() !== 'production') {
            \DB::listen(function ($queryExecuted) {
                $this->app->get('queries_executed')->push($queryExecuted);
            });
        }
    }

    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->singleton('queries_executed', \Uroshcs\GraphQLQueryDebugger\QueriesExecuted::class);
        }
    }

    public function provides()
    {
        return ['queries_executed'];
    }
}