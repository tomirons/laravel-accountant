# Laravel Accountant
[![Version](https://poser.pugx.org/tomirons/laravel-accountant/v/stable.svg)](https://packagist.org/packages/tomirons/laravel-accountant)
[![Total Downloads](https://img.shields.io/packagist/dt/tomirons/laravel-accountant.svg)](https://packagist.org/packages/tomirons/laravel-accountant)
[![Build Status](https://travis-ci.org/tomirons/laravel-accountant.svg)](https://travis-ci.org/tomirons/laravel-accountant)
[![License](https://poser.pugx.org/tomirons/laravel-accountant/license.svg)](https://packagist.org/packages/tomirons/laravel-accountant)

## Introduction

Accountant is a beautiful dashboard where you can view Stripe data without ever having to leave your application.

## Requirements

- PHP >= 7.1
- Laravel 5.5.*
- Configured Queue Driver

## Installation

1) Run the following command to install the package:

    ````shell
    composer require tomirons/laravel-accountant
    ````
    
2) Run the following command to publish the assets and configuration

    ````shell
    php artisan vendor:publish --provider="TomIrons\Accountant\AccountantServiceProvider"
    ````
    **Note:** When updating, `--force` will need to be suffixed to replace all assets. If you've updated the configuration file, you'll want to also add `--tag=accountant-assets` so it doesn't get replaced.
    
    
## Usage

All routes for accountant are prefixed with `accountant`, so to view the dashboard head to `http://example.dev/accountant`. By default we use the `auth` middleware, feel free to add or change this in the configuration.

## License

Laravel Accountant is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
