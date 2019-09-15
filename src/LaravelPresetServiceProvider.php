<?php

namespace McCallister\LaravelPreset;

use McCallister\LaravelPreset\Preset;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Console\PresetCommand;

class LaravelPresetServiceProvider extends ServiceProvider
{
    public function boot()
    {
        PresetCommand::macro('mccallister', function ($command) {
            Preset::install($command);
        });
    }
}
