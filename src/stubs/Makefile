IMAGE ?= jasonmccallister
TAG ?= laravel-preset

build:
	docker build -t $(IMAGE):$(TAG) .
composer:
	docker run --interactive --tty --volume $(PWD):/app composer install --ignore-platform-reqs --no-interaction --no-plugins --prefer-dist
down:
	docker-compose down
horizon:
	docker-compose exec app php artisan horizon:install
logs:
	docker-compose logs -f
migrate:
	docker-compose exec app php artisan migrate
model:
	docker-compose exec app php artisan make:model
migrate-fresh:
	docker-compose exec app php artisan migrate:fresh
phpcs:
	./vendor/bin/php-cs-fixer fix --config .php_cs
phpunit:
	docker-compose exec app ./vendor/bin/phpunit
reports:
	docker-compose exec app ./vendor/bin/phpunit --coverage-html reports
scale:
	docker-compose up -d --scale queue=15
seed:
	docker-compose exec app php artisan db:seed
ssh:
	docker-compose exec app bash
ssh-queue:
	docker-compose exec queue bash
tag:
	docker tag "$(IMAGE):$(TAG)" "$(IMAGE):$(TAG)"
telescope:
	docker-compose exec app php artisan telescope:install
testdox:
	docker-compose exec app ./vendor/bin/phpunit --testdox
tinker:
	docker-compose exec app php artisan tinker
up:
	docker-compose up -d
xdebug:
	docker-compose exec app /usr/local/bin/docker-php-source extract
	docker-compose exec app pecl install xdebug
	docker-compose exec app /usr/local/bin/docker-php-ext-enable xdebug
	docker-compose exec app /usr/local/bin/docker-php-source delete
	docker-compose stop
	docker-compose up -d
