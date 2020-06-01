start:
	php -S localhost:8080 -t public public/index.php

lint:
	composer run-script phpcs -- --standard=PSR12 public/index.php

test:
	php artisan config:clear
	composer run-script phpunit tests/

test-ci:
	composer run-script phpunit tests -- --coverage-clover ./build/logs/clover.xml

install:
	composer install
	cp -n .env.example .env|| true
	php artisan config:cache
	php artisan key:generate
	php artisan migrate --force
	php artisan db:seed

deploy:
	git push heroku master