.DEFAULT_GOAL := help

help: ## Display help
	@echo "Usage: make [target]"
	@echo ""
	@echo "Targets:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

build: ## Build docker images
	docker-compose build

up: ## Start the containers
	docker-compose up -d

down: ## Stop the containers
	docker-compose down

restart: ## Restart the containers
	docker-compose down && docker-compose up -d

composer-install: ## Install PHP dependencies
	docker-compose run --rm app composer install

phpstan: ## Run PHPStan for static analysis
	docker-compose run --rm app vendor/bin/phpstan analyse --ansi

ecs-check: ## Check coding standards
	docker-compose run --rm app vendor/bin/ecs check

ecs-fix: ## Fix coding standards
	docker-compose run --rm app vendor/bin/ecs check --fix

tests: ## Run PHPUnit tests
	docker-compose run --rm app vendor/bin/phpunit

test-coverage: ## Run PHPUnit tests with coverage
	docker-compose run --rm app vendor/bin/phpunit --coverage-html build/coverage
