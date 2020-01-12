# Docker Laravel App example

## Description
Connecting to GitHub API, get Repos and commits from a Repo using Laravel's Scheduler.
THe Result will be show up as a Json Format. enjoy!

The images used in this repo is `php:7.2-apache` and `mysql:5.7`.

## Up and running
Clone the repo:
```
$ git clone https://github.com/jcreforme/docker_laravel.git

$ cd docker_laravel
```
Copy `.env.example` to `.env`
```
$ cp .env.example .env 
```

Cleaning the database before start
```
$ rm -R run/*
```


Build the images and start the services:
```
docker-compose build
docker-compose up -d
```

### composer
```
$ composer install
```

VERY VERY VERY VERYVERY VERYVERY VERYVERY VERYVERY VERYIMPORTANT!!!! 
Load the DB schema!!!
```
$ ./container
$ php artisan migrate
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
$ php artisan get:Repos {name}
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

HIRE ME!!!! :)