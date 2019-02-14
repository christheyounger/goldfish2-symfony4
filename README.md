Goldfish 2 - Symfony 4
![build status](https://travis-ci.org/darkbluesun/goldfish2-symfony3.svg?branch=master)
[![codecov](https://codecov.io/gh/darkbluesun/goldfish2-symfony3/branch/master/graph/badge.svg)](https://codecov.io/gh/darkbluesun/goldfish2-symfony3)
========================

This is a sample REST API for a task manager

## Installation

1. `composer install`
2. `bin/console doctrine:database:create`
3. `bin/console doctrine:schema:update --force`

## Tools

`bin/console fos:user:create`

## Run

`bin/console server:run`

## Tests

`bin/phpunit`