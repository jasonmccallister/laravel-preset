<?php

namespace JasonMcCallister\LaravelPreset;

use Illuminate\Support\Arr;
use Illuminate\Filesystem\Filesystem;
use sixlive\DotenvEditor\DotenvEditor;

class LaravelPreset
{
    protected $command;

    protected $options = [];

    protected $packages = ['friendsofphp/php-cs-fixer'];

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
            array_push($this->packages, 'laravel/dusk');
        }

        // prompt for installing horizon
        if ($this->command->confirm('Are you going to use Laravel Horizon?')) {
            array_push($this->packages, 'laravel/horizon');
        }

        // promt for installing telescope
        if ($this->command->confirm('Are you going to use Laravel Telescope?')) {
            array_push($this->packages, 'laravel/telescope');
        }

        foreach ($this->packages as $package) {
            $this->command->info(' Installing ' . $package . '...');
            $this->runCommand(sprintf(
                'composer require %s',
                $package
            ));
        }

        $this->command->info(' Packages have been installed, you may need to run additional steps...');

        $this->publishStubs();
        $this->updateDotEnv('.env');
        $this->updateDotEnv('.env.example');
    }

    protected function publishStubs()
    {
        tap(new Filesystem, function ($filesystem) {
            if (!$filesystem->isDirectory($directory = base_path('.docker'))) {
                $filesystem->makeDirectory($directory, 0755, true);
            }
        });

        if (Arr::has($this->packages, 'laravel/horizon')) {
            $useHorizon = $this->command->confirm(' Use horizon instead of queue:work?');
        }

        switch ($this->options['database']) {
            case 'PostgreSQL':
                if ($useHorizon) {
                    copy(__DIR__ . '/stubs/postgres/docker-compose-horizon.yaml', base_path('docker-compose.yaml'));
                } else {
                    copy(__DIR__ . '/stubs/postgres/docker-compose.yaml', base_path('docker-compose.yaml'));
                }
                copy(__DIR__ . '/stubs/postgres/Dockerfile', base_path('Dockerfile'));
                break;
            default:
                if ($useHorizon) {
                    copy(__DIR__ . '/stubs/mysql/docker-compose-horizon.yaml', base_path('docker-compose.yaml'));
                } else {
                    copy(__DIR__ . '/stubs/mysql/docker-compose.yaml', base_path('docker-compose.yaml'));
                }
                copy(__DIR__ . '/stubs/mysql/Dockerfile', base_path('Dockerfile'));
                break;
        }

        copy(__DIR__ . '/stubs/apache/000-default.conf', base_path('.docker/000-default.conf'));
        copy(__DIR__ . '/stubs/Makefile', base_path('Makefile'));
        copy(__DIR__ . '/stubs/.dockerignore', base_path('.dockerignore'));
        copy(__DIR__ . '/stubs/.php_cs', base_path('.php_cs'));
        copy(__DIR__ . '/stubs/phpunit.xml', base_path('phpunit.xml'));
    }

    protected function updateDotEnv(string $file)
    {
        $editor = new DotenvEditor;
        $editor->load(base_path($file));

        $this->command->info(' Modifying ' . $file . '...');

        switch ($this->options['database']) {
            case 'PostgreSQL':
                $editor->set('DB_CONNECTION', 'pgsql');
                $editor->set('DB_PORT', 5432);
                break;
            default:
                $editor->set('DB_CONNECTION', 'mysql');
                $editor->set('DB_PORT', 3306);
                break;
        }

        $editor->set('DB_HOST', 'db');
        $editor->set('CACHE_DRIVER', 'redis');
        $editor->set('QUEUE_CONNECTION', 'redis');
        $editor->set('REDIS_HOST', 'redis');
        $editor->save();
    }

    private function runCommand($command)
    {
        return exec(sprintf('%s 2>&1', $command));
    }
}
