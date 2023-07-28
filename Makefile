build:
	@bash ./scripts/build.sh

up:
	@docker-compose -f ./docker-compose.yml up -d

down:
	@docker-compose -f ./docker-compose.yml down

in:
	@bash ./scripts/in.sh

test:
	@bash ./scripts/test.sh