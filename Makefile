build:
	docker-compose build
.PHONY: build

up:
	docker-compose up -d
.PHONY: up

down:
	docker-compose down --remove-orphans
.PHONY: down
