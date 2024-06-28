# kanyeRest

This is an API built with laravel that returns 5 random Kanye West quotes.

## Installation

Pull the main branch of this repository, and create a .env by copying .env.example.
```bash
cp .env.example .env
```

Install the required dependencies
```bash
composer install
```

Run the migrations, and select the option to create the database.
```bash
php artisan migrate
```

Set the absolute path for the newly created database
```bash
php artisan db:set-path
```

And finally, start the server.
```bash
php artisan serve
```

## Testing

To test the application, run the command
``` bash
php artisan test
```

## Usage
#### User Generation
First, a user will need to be created. This can be done with the Create User command, following the signature
``` bash
php artisan app:create-user {name} {email} {password}
```

#### Authentication
To obtain a bearer token, use the authenticate route with your email and password as params (Make sure the `Accept application/json` header is set):
```
http://127.0.0.1:8000/api/authenticate?email={email}&password={password}
```
Save the returned token for future calls.

#### Retrieving Quotes

Make a GET request to:
```
http://127.0.0.1:8000/api/quotes
```
With the bearer token set, and the `Accept application/json` header set. These results will be cached, so if you wish to generate more quotes, make a PUT request to
```
http://127.0.0.1:8000/api/quotes/refresh
```
With the same authorisation and headers, then re-try the GET request.
