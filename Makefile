start:
	php -S localhost:8080 -t public public/index.php

lint:
	composer run-script phpcs -- --standard=PSR12 public/index.php

test:
	composer run-script phpunit tests/

install:
	composer install
	cp -n .env.example .env
	php artisan config:clear
	php artisan config:cache
	php artisan key:generate
	php artisan migrate
	php artisan db:seed

deploy:
	git push heroku master