# Laravel Presets for Docker, PHPCS, and PHPUnit

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jasonmccallister/laravel-preset.svg?style=flat-square)](https://packagist.org/packages/jasonmccallister/laravel-preset)
[![Total Downloads](https://img.shields.io/packagist/dt/jasonmccallister/laravel-preset.svg?style=flat-square)](https://packagist.org/packages/jasonmccallister/laravel-preset)

This preset will install and prompt you to install Laravel official packages like Horizon and Telescope. This also includes a `Makefile` for helpful commands for local development and continous integration.

The overall goal is to make it as easy as possible to ship a project with Laravel using Docker, CI/CD, and PHPUnit testing.

### Docker

Taking years of experience shipping PHP applications (both Craft CMS and Laravel) with Docker, this is a combination of lessons learned in one package.

## Installation

You can install the package via composer:

```bash
composer require --dev jasonmccallister/laravel-preset
```

## Usage

```bash
php artisan preset jasonmccallister
```

Now all that is left is to run the following command:

```bash
make up
```

### Docker

Running the preset command (`php artisan preset jasonmccallister`) will prompt you on the type of database you are going to use on the project. This will setup the Dockerfile and docker-compose file with the correct database dependencies.

There is a `.dockerignore` that will exclude the `vendor` and `node_modules` from the Docker Build Context. This is included to [improve the build times for Docker](https://docs.docker.com/engine/reference/builder/#dockerignore-file).

#### Dockerfile

The goal is to use the same `Dockerfile` for local development, CI/CD, and deploying a production image. By default only "production" OS packages are installed.

> PHP Extensions like xdebug can be installed but are not enabled by default, there is a `Makefile` command for that!

#### docker-compose.yaml

To make development with Docker easier locally, we use the `docker-compose.yaml` to scaffold the creation of the database, queue, and redis instance. Docker Compose makes it really easy to spin all of your services up with one command.

There are a few things of note with this file:

- The volumes are tagged with `:cache` to [improve Docker's performance with macOS](https://docs.docker.com/docker-for-mac/osxfs-caching/#cached) specifically but has no impact on other operating systems
- There are lines commented out if you are not using Laravel Passport
- No image is specified for the app and queue containers, this will default to the root folders name. Instead the file will look at the Dockerfile and build the image if it cannot find it locally

#### Packages

The preset will prompt you to install some recommended first-party packages.

1. [Laravel Dusk](https://github.com/laravel/dusk)
1. [Laravel Horizon](https://github.com/laravel/horizon)
1. [Laravel Telescope](https://github.com/laravel/telescope)

> Note: if you select to install Horizon, the preset will also prompt you to use the horizon command instead of `queue:work`. Again this is optional but recommended when using Horizon.

#### Makefile

The preset will also install a `Makefile` with a lot of helpful commands. Here is a list of available commands:

- `make build` will build an image
- `make composer` will install composer dependencies inside of a throw away docker container and copy to your local machine
- `make down` will stop, or shutdown, the projects services
- `make logs` will show all of your serivces logs with the `--follow` flag
- `make migrate` will run `php artisan migrate` inside of the docker container
- `make migrate:fresh` will run `pap artisan migrate` inside of the docker container
- `make phpcs` will apply `.php_cs` fixes on the `app` directory
- `make phpunit` runs phpunit inside of the container, useful for CI/CD environments
- `make reports` runs phpunit with HTML code coverage inside the container
- `make scale` will scale your queue container up to 15 containers. Useful for testing concurrency of background jobs and queues locally
- `make seed` runs `db:seed` inside of your container
- `make ssh` will "ssh" you into the app container with a bash shell
- `make ssh-queue` the same as the `ssh` command but will give you a bash shell in the queue container
- `make tag` will tag your docker image
- `make testdox` runs phpunit with the `--testdox` flag for prettier output
- `make up` is used to start all of your services
- `make xdebug` will install the xdebug PHP Extension inside of your app container

### Security

If you discover any security related issues, please email themccallister@gmail.com instead of using the issue tracker.

## Credits

- [Jason McCallister](https://github.com/jasonmccallister)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
