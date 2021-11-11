<?php

namespace App\Providers;

use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Support\ServiceProvider;

class ApiRequestServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->afterResolving(ValidatesWhenResolved::class, function ($resolved) {
            $resolved->validateResolved();
        });

        $this->app->resolving(ApiRequest::class, function ($request, $app) {
            $request = ApiRequest::createFrom($app['request'], $request);

            $request->setContainer($app);
        });
    }
}
