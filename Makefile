start:
	docker-compose up -d
ssh:
	docker exec -it tanp-php bash
build:
	docker-compose build --no-cache
stop:
	docker-compose down
test:
	docker exec -it tanp-php bash -c './vendor/bin/phpunit'
prod-build:
	docker-compose -f docker-compose.prod.yaml build --no-cache
build-test:
	docker exec -it tanp-php-prod bash -c './vendor/bin/phpunit'