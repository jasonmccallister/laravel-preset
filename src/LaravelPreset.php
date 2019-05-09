<?php

namespace JasonMcCallister\LaravelPreset;

use Illuminate\Filesystem\Filesystem;

class LaravelPreset
{
    protected $command;

    protected $options = [];

    public function __construct($command)
    {
        $this->command = $command;
    }

    public static function install($command)
    {
        (new static($command))->run();
    }

    public function run()
    {
        // prompt for the type of database
        $this->options['database'] = $this->command->choice('What database will this project use?', ['MySQL', 'PostgreSQL'], 'PostgreSQL');

        // prompt for installing dusk
        if ($this->command->confirm('Are you going to use Laravel Dusk?')) {
            $this->command->info('installing laravel/dusk dependencies');
        }

        // prompt for installing horizon
        if ($this->command->confirm('Are you going to use Laravel Horizon?')) {
            $this->command->info('installing laravel/horizon dependencies');
        }

        // promt for installing telescope
        if ($this->command->confirm('Are you going to use Laravel Telescope?')) {
            $this->command->info('installing laravel/telescope dependencies');
        }

        $this->publishStubs();
    }

    public function publishStubs()
    {
        tap(new Filesystem, function ($filesystem) {
            if (!$filesystem->isDirectory($directory = base_path('.docker'))) {
                $filesystem->makeDirectory($directory, 0755, true);
            }
        });

        switch ($this->options['database']) {
            case 'PostgreSQL':
                copy(__DIR__ . '/stubs/postgres/docker-compose.yaml', base_path('docker-compose.yaml'));
                copy(__DIR__ . '/stubs/postgres/Dockerfile', base_path('Dockerfile'));
                break;
            default:
                copy(__DIR__ . '/stubs/mysql/docker-compose.yaml', base_path('docker-compose.yaml'));
                copy(__DIR__ . '/stubs/mysql/Dockerfile', base_path('Dockerfile'));
                break;
        }

        copy(__DIR__ . '/stubs/apache/000-default.conf', base_path('.docker/000-default.conf'));
        copy(__DIR__ . '/stubs/Makefile', base_path('Makefile'));
        copy(__DIR__ . '/stubs/.dockerignore', base_path('.dockerignore'));
        copy(__DIR__ . '/stubs/.php_cs', base_path('.php_cs'));
        copy(__DIR__ . '/stubs/phpunit.xml', base_path('phpunit.xml'));
    }
}
