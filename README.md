Sari Sari Store - Simple Ordering System

TECHNOLOGY STACK
- Laravel 10
- PHP 8.1
- Vue 3.2.37

PRE-REQUISITES:
- PHP 8+
- GIT https://git-scm.com/downloads
- Composer https://getcomposer.org/download/
- NodeJS/NPM https://nodejs.org/en/download/
- Docker Desktop https://www.docker.com/products/docker-desktop/

GUIDE TO SETUP THE PROJECT:
- Clone the project from this repository and checkout 'master' or 'develop' branch
- Copy the .env.example file and rename it to the .env file
- Execute the command 'composer install' to insall the project dependencies
- Execute the command 'php artisan key:generate' to generate environment app key
- Execute the command 'npm install' to insall the project dependencies (for Vue)
- Execute the command 'npm run build' to build the UI
- Execute the command 'sail up -d' to run the app docker containers
    (Note: in case 'sail' command does not work - execute the command 'php artisan sail:install' then select 'mariadb' and 'mailpit')
- Execute the command 'docker-compose exec main php artisan migrate:fresh --seed' to run the database migration files and seeders
- Done!

Go to http://localhost:8000/
Mailpit link http://localhost:8025/
Mailpit reference: https://laravel.com/docs/10.x/sail#previewing-emails

ACCOUNTS AVAILABLE:
Admin - admin@sarisari.net
Customer - johndc@gmail.com
Customer - juandc@gmail.com
Customer - jhondc@gmail.com
Password - P@ssw0rd123 (all of the accounts)

GUIDE FOR TESTING:
- Execute the command 'docker-compose exec main php artisan migrate:fresh --seed --env=testing' to run the database migration files and seeders for testing
- Execute the command 'docker-compose exec main php artisan test --env=testing' to run the tests
Reference: https://laravel.com/docs/10.x/testing#running-tests
