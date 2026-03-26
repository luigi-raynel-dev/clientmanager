<?php

namespace App\Providers;

use App\Domain\Clients\Repositories\ClientRepository;
use App\Domain\PricingTypes\Repositories\PricingTypeRepository;
use App\Domain\Services\Repositories\ServiceRepository;
use App\Domain\Users\Repositories\UserRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentClientRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentPricingTypeRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentServiceRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentUserRepository;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepository::class,
            EloquentUserRepository::class
        );
        $this->app->bind(
            ClientRepository::class,
            EloquentClientRepository::class
        );
        $this->app->bind(
            ServiceRepository::class,
            EloquentServiceRepository::class
        );
        $this->app->bind(
            PricingTypeRepository::class,
            EloquentPricingTypeRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        Inertia::share([
            'flash' => fn() => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    protected function configureDefaults(): void
    {

        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(
            fn(): ?Password => app()->isProduction()
                ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
                : null
        );
    }
}
