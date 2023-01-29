# A simple chat over HTTP(S)

[![Coverage Status](https://coveralls.io/repos/github/seyyedaghaei/simple-chat/badge.svg?branch=main)](https://coveralls.io/github/seyyedaghaei/simple-chat?branch=main)

## TODO
- Login with email and/or phone and verify
- Pagination
- Delete message
- Refresh token

## Prerequisites

- PHP 7.4+
- Composer
- Docker & Docker Compose (optional)

To prepare the project run these commands:

```bash
cp .env.example .env
composer run migrate
```

## Install dependencies

```bash
composer install
```

## Run the application

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writable.

To run the application , you can run this command'

```bash
composer start
```

Or you can use `docker-compose` to run the app with `docker`, so you can run this command:
```bash
docker-compose up -d
```

Or if you use `docker compose plugin` to run the app with `docker`, you should run this command:
```bash
docker compose up -d
```

After that, open `http://localhost:8080` in your browser.

## Test

Run this command in the application directory to run the test suite

```bash
composer test
```

That's it!
