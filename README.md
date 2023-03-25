# Test docs

Dev stack is a PHP/React dev environment based on Docker.

## Requirements

* [Docker](https://docs.docker.com/engine/install/)
* [Docker Compose](https://docs.docker.com/compose/install/)

## Docker Services

* PHP 8.2
* Symfony 6.2
* Nginx
* MailHog
* RabbitMQ

## Symfony components

* symfony/property-access
* symfony/serializer
* symfony/test-pack
* symfony/validator
* symfony/http-client
* symfony/event-dispatcher
* symfony/mailer
* symfony/notifier
* symfony/messenger
* symfony/amqp-messenger

## Installation

1. Cloning repo

2. Go to repo dir

3. Create `.env`
```bash
cp .env.example .env
```

4. Set valid ``x_rapid_api_key`` to `.env`  

5. Build docker containers
```bash
docker-compose build
```
You can use the Makefile to build :
```bash
make build
```

6. Start app
```bash
docker-compose up -d

# same thing here you can use the Makefile
make up
```

7. Install vendors
```bash
docker exec -it php_v21_0_5 composer install
```

8. Run messenger listener
```bash
docker exec -it php_v21_0_5 php bin/console messenger:consume amqp_email_notification -vv
```

9. Run tests
```bash
docker exec -it php_v21_0_5 php bin/phpunit
```

### Stop
```bash
docker-compose down --remove-orphans

# same thing here you can use the Makefile
make down
```
