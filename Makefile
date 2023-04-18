.PHONY: docker-build
docker-build:
	CLOUDSDK_PYTHON=python2 docker-compose build

.PHONY: docker-up
docker-up: docker-build
	CLOUDSDK_PYTHON=python2 docker-compose up -d

.PHONY: docker-down
docker-down:
	CLOUDSDK_PYTHON=python2 docker-compose down -v

.PHONY: docker-logs
docker-logs:
	CLOUDSDK_PYTHON=python2 docker-compose logs -f api

docker-shell:
	CLOUDSDK_PYTHON=python2 docker-compose exec api bash

docker-watch:
	CLOUDSDK_PYTHON=python2 watch -d -n 1 docker-compose ps

### === In Docker ===
SYMFONY=/root/.symfony/bin/symfony

.PHONY: start
start: migrations
	$(SYMFONY) server:start

.PHONY: migrations
migrations:
	php bin/console doctrine:migrations:migrate --no-interaction

.PHONY: tests
tests:
	php bin/phpunit
