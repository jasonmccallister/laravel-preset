<?php

namespace JasonMcCallister\LaravelPreset;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Console\PresetCommand;
use JasonMcCallister\LaravelPreset\LaravelPreset;

class LaravelPresetServiceProvider extends ServiceProvider
{
    public function boot()
    {
        PresetCommand::macro('jasonmccallister', function ($command) {
            LaravelPreset::install($command);
        });
    }
}
