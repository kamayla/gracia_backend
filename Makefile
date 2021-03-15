start:
	docker-compose up -d
ssh:
	docker exec -it tanp-php bash
stop:
	docker-compose down