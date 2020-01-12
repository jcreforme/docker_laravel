# Docker Laravel App example

## Description
Start developing a fresh Laravel application with `docker` using `docker-compose`.

The images used in this repo is `php:7.2-apache` and `mysql:5.7`. The goal is to make setting up the development as simple as possible.

## Up and running
Clone the repo:
```
$ git clone https://github.com/jcreforme/docker_laravel.git

$ cd laravel_laravel
```
Copy `.env.example` to `.env`
```
$ cp .env.example .env 
```

Cleaning the database before start
```
$ ./container
$ rm -R run/*
```


Build the images and start the services:
```
docker-compose build
docker-compose up -d
```
VERY VERY VERY VERYVERY VERYVERY VERYVERY VERYVERY VERYIMPORTANT!!!! 
Load the DB schema!!!
```
$ php artisan migrate
```

### composer
```
$ composer install
```

### container
Running `./container` takes you inside the `laravel-app` container
```
$ ./container
devuser@8cf37a093502:/var/www/html$
```
### db
Running `./db` connects to your database container's daemon using mysql client.
```
$ ./db
mysql>
```


### php-artisan
Run `php artisan` commands, example:
```
$ ./php-artisan make:controller BlogPostController --resource
php artisan make:controller BlogPostController --resource
Controller created successfully.
```

### phpunit
Run `./vendor/bin/phpunit` to execute tests, example:
```
$ ./phpunit --group=failing
vendor/bin/phpunit --group=failing
PHPUnit 7.5.8 by Sebastian Bergmann and contributors.
```

### App commands
How Get Repos from Github using cron Jobs {name} is the Repo's name (laravel, spatie, symfony...)
```
$ php artisan get:Commits {name}
```

How clean Repos and Commmits tables form the DB
```
$ php artisan drop:cleanUp
```

### Github Repo's page result
```
http://localhost:8000/repos
```

