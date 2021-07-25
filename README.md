# online-shop-backend
## Installation
`composer install`
## copy .env.example
copy .env.example to .env and setup db settings
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```
## DB Setup
run the below commands to migrate and seed the database
```
php artisan migrate
php artisan db:seed
```
## Uploads
create an uploads/ directory in the public/ directory and make it writable

## jwt 
Generate jwt secret key with this command
```
php artisan jwt:secret
```
