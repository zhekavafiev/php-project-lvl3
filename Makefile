start:
	php -S localhost:8080 -t public public/index.php

lint:
	composer run-script phpcs -- -n --standard=PSR12 app/ tests/ routes/ src/ resources/

test:
	php artisan config:clear
	composer run-script phpunit tests/

test-ci:
	php artisan config:clear
	composer run-script phpunit tests -- --coverage-clover ./build/logs/clover.xml

install:
	composer install
	npm install
	cp -n .env.example .env|| true
	touch project3
	php artisan config:cache
	php artisan key:generate
	php artisan migrate
	php artisan db:seed

deploy:
	git push heroku master