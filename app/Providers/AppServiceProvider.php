<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentColor;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Colors\Color;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
        FilamentColor::register([
            'gray' => Color::Gray,
            'yellow' => Color::Yellow,
        ]);
    }
}
