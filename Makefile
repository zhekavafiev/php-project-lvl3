start:
	php -S localhost:8080 -t public public/index.php

lint:
	composer run-script phpcs -- --standard=PSR12 public/index.php

test:
	composer run-script phpunit tests/

install:
	composer install
	cp -n .env.example .env|| true
	php artisan key:generate
	php artisan migrate
	php artisan db:seed
	npm install

deploy:
	git push heroku master