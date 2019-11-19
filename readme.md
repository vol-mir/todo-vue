<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Project

This project To-Do с Laravel 5.8 and Vue.

## Stack
- Vue 2.5.17
- Laravel 5.8
- Bootstrap 4.1.0

## Quick Start
```shell
$ git clone https://github.com/vol-mir/todo-vue.git
$ cd todo-vue
$ composer install
$ cp .env.example .env
$ php artisan key:generate
$ php artisan migrate:fresh --seed
$ php artisan passport:install
$ npm install
```

## Test
```shell
$ cp .env.testing .env
$ php vendor/phpunit/phpunit/phpunit
```
