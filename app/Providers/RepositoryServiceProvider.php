<?php


namespace App\Providers;

use App\Contracts\EventSubscriberRepository as EventSubscriberRepositoryContract;
use App\Repositories\Eloquent\EventSubscriberRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            EventSubscriberRepositoryContract::class,
            EventSubscriberRepository::class
        );
    }
}
