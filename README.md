# Test docs

Dev stack is a PHP/React dev environment based on Docker.

## Requirements

* [Docker](https://docs.docker.com/engine/install/) version 20.10.23, build 7155243
* [Docker Compose](https://docs.docker.com/compose/install/) version v2.15.1

## Docker Services

* PHP 8.2
* Symfony 6.2
* Nginx 1.22.1
* MailHog 1.14.7
* RabbitMQ 3.11.11
* React 18
* Node 16

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
* nelmio/cors-bundle

## Structure

* `api` - symfony backend
* `ui` - react frontend
* `docker` - docker components

## Installation

1. Cloning repo

2. Go to repo dir

3. Create `.env`
```bash
cp .env.example .env
cp api/.env.example api/.env
cp ui/.env.example ui/.env
```
check availability for ports

[Symfony .env](https://github.com/if1bonacci/symfony_test/blob/master/api/.env.example)

[React .env](https://github.com/if1bonacci/symfony_test/blob/master/ui/.env.example)

[Docker .env](https://github.com/if1bonacci/symfony_test/blob/master/.env.example)

4. Set valid ``x_rapid_api_key`` to `api/.env`  

5. Build docker containers
```bash
docker-compose build

#same thing here you can use the Makefile
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

# same thing here you can use the Makefile
make composer
```

8. Run messenger listener
```bash
docker exec -it php_v21_0_5 php bin/console messenger:consume amqp_email_notification -vv

# same thing here you can use the Makefile
make messenger
```

9. Run tests
```bash
docker exec -it php_v21_0_5 php bin/phpunit

# same thing here you can use the Makefile
make test
```

## Testing

1. test api `http://localhost:8081` (the port can be modified in .env `${NGINX_PORT}`)

```bash
# List of prices

curl --location 'localhost:8081/api/prices-list' \
--header 'Content-Type: application/json' \
--data-raw '{
    "symbol": "GOOG",
    "startDate": "2023-03-25",
    "endDate": "2023-03-25",
    "email": "test@example.com"
}'

# List of symbols

curl --location 'localhost:8081/api/companies' \
--header 'Content-Type: application/json' \
--data ''
```
2. test front `http://localhost:3000` (the port can be modified in .env `${REACT_PORT}`)

after update don't forgot to actualize `.env` in `ui` dir 

3. test email sandbox `http://localhost:8025` (the port can be modified in .env `${MAILER_SANDBOX_PORT}`)

before testing don't forgot to run consumer
```bash
docker exec -it php_v21_0_5 php bin/console messenger:consume amqp_email_notification -vv

# same thing here you can use the Makefile
make messenger
```

4. test rabbitMQ dashboard `http://localhost:15672` (the port can be modified in .env `${RABBITMQ_MANAGER_PORT}`)
```yaml
RABBITMQ_USER=guest 
RABBITMQ_PASS=guest
```

### Stop
```bash
docker-compose down --remove-orphans

# same thing here you can use the Makefile
make down
```

## Nice to do (not done)

* would be great to add caching for requests to speed up the response process
* split "SymbolService" - change to decorator
* add the bigger amount of tests. Cover not only happy path
* add functional tests
* add  tests to "React"
* update error listener and make better error information
* add logger 
