IMAGE ?= jasonmccallister
TAG ?= laravel-preset

build:
	docker build -t $(IMAGE):$(TAG) .
composer:
	docker run --interactive --tty --volume $(PWD):/app composer install --ignore-platform-reqs --no-interaction --no-plugins --prefer-dist
down:
	docker-compose down
logs:
	docker-compose logs -f
migrate:
	docker-compose exec app php artisan migrate
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
tag:
	docker tag "$(IMAGE):$(TAG)" "$(IMAGE):$(TAG)"
testdox:
	docker-compose exec app ./vendor/bin/phpunit --testdox
up:
	docker-compose up -d
xdebug:
	docker-compose exec app /usr/local/bin/docker-php-source extract
	docker-compose exec app pecl install xdebug
	docker-compose exec app /usr/local/bin/docker-php-ext-enable xdebug
	docker-compose exec app /usr/local/bin/docker-php-source delete
	docker-compose stop
	docker-compose up -d
