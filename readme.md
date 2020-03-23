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

Build the images and start the services:
```
$ docker-compose build
$ docker-compose up -d
```

### composer 
Get into the container and install the composer
```
$ ./container
$ composer install
```
VERY VERYIMPORTANT!!!! 
Load the DB schema!!!
```
$ php artisan migrate
```

### Seeds
Import Seeds <br />
Only Laravel and Spatie commits imported as seeds <br />
Only Laravel and Spatie Repos imported as seeds <br />
Only Laravel contributors imported as seeds <br />
```
php artisan db:seed
```

### App commands
How Get Repos from Github using cron Jobs {name} is the Repo's name and {owner} is the user's name (examples: laravel, spatie, symfony...)
```
$ php artisan get:Repos {name}
$ php artisan get:Commits {repo} {owner}
$ php artisan get:Contributes {owner}
```

How clean Repos, COntributes and Commmits tables form the DB
```
$ php artisan drop:cleanUp
```

### Github API's
```
Spatie Repo ID = 7535935
Laravel Repo ID = 958072
Symfony Repo ID = 143937

https://api.github.com/users/spatie
https://api.github.com/users/spatie/repos
https://api.github.com/repos/spatie/7to5/commits
https://api.github.com/repos/laravel/airlock/stats/contributors
```

### Usefull example pages
```
http://localhost:8000/repos
http://localhost:8000/repos/1863329
http://localhost:8000/orgs/details/958072
```


### In case requires to check the DB
Running `./db` connects to your database container's daemon using mysql client.
the Query to get the Repos ids.
```
$ ./db
mysql> select id, node_id from repos;
```


### In case requires Phpunit
Run `./vendor/bin/phpunit` to execute tests, example:
```
$ ./phpunit --group=failing
vendor/bin/phpunit --group=failing
PHPUnit 7.5.8 by Sebastian Bergmann and contributors.
```


### END 
HIRE ME!!!! :)


