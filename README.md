# Laravel Presets for Docker, PHPCS, and PHPUnit

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jasonmccallister/laravel-preset.svg?style=flat-square)](https://packagist.org/packages/jasonmccallister/laravel-preset)
[![Total Downloads](https://img.shields.io/packagist/dt/jasonmccallister/laravel-preset.svg?style=flat-square)](https://packagist.org/packages/jasonmccallister/laravel-preset)

This preset will install and prompt you install Laravel official packages like Horizon and Telescope. This also includes a `Makefile` for helpful commands for local development and continous integration.

The overall goal is to make it as easy to ship a project with Laravel using Docker, CI/CD, and PHPUnit testing.

### Docker

Taking years of experience shipping PHP applications (both Craft CMS and Laravel) with Docker, this is a combination of lessons learned in one package.

### Dockerfile

Running the preset command (`php artisan preset jasonmccallister`) will prompt you on the type of database you are going to use on the project. The goal here is to use the same Dockerfile for local development, CI/CD, and deploying a production image. By default only "production" OS packages are installed (xdebug can be installed but is not enabled by default).

### docker-compose.yaml

To make development with Docker easier locally, we use the `docker-compose.yaml` to scaffold the creation of the database, queue, and redis instance. Docker Compose makes it really easy to spin all of your services up with one command.

## Installation

You can install the package via composer:

```bash
composer require --dev jasonmccallister/laravel-preset
```

## Usage

```bash
php artisan preset jasonmccallister
```

The preset will prompt you to install some recommended first-party packages.

### Packages

1. [Laravel Dusk](https://github.com/laravel/dusk)
1. [Laravel Horizon](https://github.com/laravel/horizon)
1. [Laravel Telescope](https://github.com/laravel/telescope)

> Note: if you select to install Horizon, the preset will also prompt you to use the horizon command instead of `queue:work`. Again this is optional but recommended when using Horizon.

Now all that is left is to run the following command:

```bash
make up
```

The preset will also install a `Makefile` with a lot of helpful commands. The only two you really need to know to get started are:

1. `make up` runs the command `docker-compose up -d`
2. `make down` runs the command `docker-compose down`

### Security

If you discover any security related issues, please email themccallister@gmail.com instead of using the issue tracker.

## Credits

- [Jason McCallister](https://github.com/jasonmccallister)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
