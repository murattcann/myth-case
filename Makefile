install-app:
	composer require symfony/runtime
	composer install
	php bin/console app:install
	php bin/console doctrine:fixtures:load
