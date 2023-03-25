build:
	docker-compose build
.PHONY: build

up:
	docker-compose up -d
.PHONY: up

messenger:
	docker exec -it php_v21_0_5 php bin/console messenger:consume amqp_email_notification -vv
.PHONY: up

composer:
	docker exec -it php_v21_0_5 composer install
.PHONY: up

test:
	docker exec -it php_v21_0_5 php bin/phpunit
.PHONY: up

down:
	docker-compose down --remove-orphans
.PHONY: down
