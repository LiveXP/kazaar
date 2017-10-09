Kaazar
======

Kaazar is an internal system of LiveXP used to simplify morning checks.

Installation
------------

This Symfony application needs some extra steps to work. You need to use the
Docker environment for follow those steps.

### Docker Compose

You can install the project with docker (docker compose).

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

This project uses the following command

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$ docker-compose exec php-fpm bin/console swiftmailer:spool:send
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

