list:
	docker-compose ps

up:
	docker-compose up -d

dev:
	docker-compose -f docker-compose_dev.yml up -d --force-recreate

test:
	docker-compose -f docker-compose_dev_server.yml up -d

git-deploy:
	git reset --hard origin/master
	git clean -fd

composer:
	docker-compose exec php composer install

clear-cache:
	rm -rf ./app/runtime/*/*

rebuild:
	./rebuild.sh

deploy: git-deploy composer clear-cache rebuild

email:
	docker-compose exec php php ./app/console.php Mail MailSender

