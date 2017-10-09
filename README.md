Kaazar
======

Kaazar is an internal system of LiveXP used to simplify morning checks.

Installation
------------

This Symfony application needs some extra steps to work. You need to use the
Docker environment for follow those steps.

### Docker Compose

You can install the project with docker (docker compose). 

For more informations about docker-compose check this [readme](./engine/README.md)

First you need to copy the ``docker-compose.yml.dist`` to ``docker-compose.yml`` and update the mysql informations 

Then run ``docker-compose up`` to start the docker environment

You need to copy your own parameters.yml file.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$ cp app/config/parameters.yml.dist app/config/parameters.yml
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

After, you need to install composer dependencies.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$ docker-compose exec php-fpm composer install
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

#### MariaDB (MySQL)

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$ docker-compose exec php-fpm bin/console doctrine:schema:update --force
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Data for fixtures are available, if you wish to use them, you need to fill the files 
in [DataFixtures/Data](./src/AppBundle/DataFixtures/Data) with you own data and then load them

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$ docker-compose exec php-fpm bin/console doctrine:fixtures:load
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Assets
------

This project uses bower to install all the css and js ressources

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$ bower install
$ bower install ./vendor/sonata-project/admin-bundle/bower.json
$ docker-compose exec php-fpm bin/console assets:install --symlink web
$ docker-compose exec php-fpm bin/console assetic:dump
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Commands
------

This project uses the following command to send emails, you can put it in a crontab to send email to a fixed interval

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$ docker-compose exec php-fpm bin/console swiftmailer:spool:send --env=prod
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

