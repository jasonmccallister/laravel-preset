<?php

namespace JasonMcCallister\LaravelPreset;

use Illuminate\Filesystem\Filesystem;

class LaravelPreset
{
    protected $command;

    protected $options = [];

    protected $packages = [];

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

        if (is_array($this->packages)) {
            foreach ($this->packages as $package) {
                $this->command->info('Installing composer dependency ' . $package);
                $this->runCommand(sprintf(
                    'composer require %s',
                    $package
                ));
            }
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

    private function runCommand($command)
    {
        return exec(sprintf('%s 2>&1', $command));
    }
}
