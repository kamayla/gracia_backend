start:
	docker-compose up -d
ssh:
	docker exec -it tanp-php bash
build:
	docker-compose build --no-cache
stop:
	docker-compose down