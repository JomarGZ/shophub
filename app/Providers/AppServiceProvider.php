<?php

namespace App\Providers;

use App\Enums\OrderStatus;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

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
            'primary' => Color::Orange,      // matches your "default" variant (bg-primary)
            'secondary' => Color::Cyan,      // for secondary actions
            'destructive' => Color::Red,     // for danger/delete buttons
            'success' => Color::Green,       // success
            'warning' => Color::Amber,       // warning
            'info' => Color::Blue,           // info notifications
            'neutral' => Color::Slate,       // neutral background or outline
            'accent' => Color::Cyan,         // accent (same as secondary for coherence)
            'muted' => Color::Gray,          // muted/disabled tones
        ]);
    }
}
