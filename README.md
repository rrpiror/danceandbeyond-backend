# Setting up project

composer install
php artisan key:generate
php artisan nova:user
php artisan migrate
php artisan serve

## Generating swagger documentation

php artisan l5-swagger:generate

### Accessing swagger documentation

[Swagger Documentation](http://localhost:8000/api/documentation)

use login endpoint with your credentials, you'll get a token in response, copy that token and paste it like this "Bearer your-token" in Authorize button of swagger

stripe listen --forward-to http://localhost:8000/api/stripe/webhook
