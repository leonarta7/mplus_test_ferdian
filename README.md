## Installation Instruction

1. Make sure your environment has composer installed. If not, run this command on the project directory inside your local environment with composer
```bash
composer install
```
2. Create .env file and copy the content of .env.example into the .env file
3. Run this command on the project's directory
```bash
php artisan key:generate
```
4. Modify the APP_URL parameter in the .env according to your environment setup
5. Create a new database schema in your mysql environment
6. Change the DB_CONNECTION inside .env file to mysql and modify these parameters according to your database configuration
```bash
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mplus_test
DB_USERNAME=root
DB_PASSWORD=
```
7. Run this command on the project's directory
```bash
php artisan migrate
```
8. Run the project using this command or according to your environment configuration
```bash
php artisan serve
```
