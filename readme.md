# Consumer manager SPA: Laravel 5.6 & Vue 2.5.17 & Element Ui 2.4.9

## Introduction

### Master branch is awlays in sync with the latest finished version in the list below

- **[Simple version without Authentication and Routing](https://github.com/grandeto/consumer-manager/tree/consumer-manager-basic)**
- **[Extended version with Laravel built-in Routing, Web Authentication and Passport API token Authentication](https://github.com/grandeto/consumer-manager/tree/consumer-manager-extended)**
- Upcoming: Extended Vue Router version

## Installation

- ```git clone https://github.com/grandeto/consumer-manager.git```
- ```git checkout <desired branch>```

1. ```composer install```
2. Create DB
3. Setup .env
4. ```php artisan migrate:refresh --seed```
5. ```php artisan passport:install``` (for extended versions only)
6. ```php artisan passport:keys``` (When deploying Passport to your production servers for the first time)
6. ```npm install```
7. ```npm run dev``` or ```npm run prod```
