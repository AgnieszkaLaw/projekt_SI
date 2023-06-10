# projekt_SI
"Aplikacja do zarządzania informacją osobistą (terminarz, baza kontaktów)"

## Requirements

- MySQL database server version 5.7
- PHP parser version 7.4./package containing MySQL, PHP and Apache, e.g. XAMPP
- PhpStorm development environment

## Installation

### Clone

- Clone this repo to your local machine using `https://github.com/AgnieszkaLaw/projekt_SI.git`

### Setup

- Change the database connection information in the .env file

> In the app directory:
```shell
$ composer install
$ bin/console make:migration
$ bin/console doctrine:migrations:migrate
$ bin/console doctrine:fixtures:load
$ bin/console server:run
```

- The home page is at `/event`
