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

VERY VERY VERY VERYVERY VERYVERY VERYVERY VERYVERY VERYIMPORTANT!!!! 
Load the DB schema!!!
```
$ php artisan migrate
```
Import Seeds (since is too much data Github will ban for an hour before it finishs the importation)
Only Laravel and Saptie commits imported as seeds \n
Only Laravel and Spatie Repos imported as seeds
Only Laravel contributors imported as seeds
```
php artisan db:seed
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
$ php artisan get:Commits {repo} {owner}
$ php artisan get:Contributes {owner}
```

How clean Repos, COntributes and Commmits tables form the DB
```
$ php artisan drop:cleanUp
```

### Github Repo's page result
```
http://localhost:8000/repos?name=laravel&owner_uuid=958072
http://localhost:8000/commits?name=laravel
```

### Github API
```
https://api.github.com/users/spatie
https://api.github.com/users/spatie/repos
https://api.github.com/repos/spatie/7to5/commits
https://api.github.com/repos/laravel/airlock/stats/contributors
```

### Usefull example datas
```
http://localhost:8000/repos
http://localhost:8000/repos/1863329
http://localhost:8000/orgs/details/7535935

spatie id = 7535935
laravel id = 958072
repo id = 1863329
```

### END 
HIRE ME!!!! :)


