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

rebuild-submodules:
	bash -c "cd ./nextjs; git submodule init"
	bash -c "cd ./nextjs; git submodule update"
	bash -c "cd ./www/static; git submodule init"
	bash -c "cd ./www/static; git submodule update"

rebuild-node:
	docker-compose exec node npm i --include=dev
	docker-compose exec node npx prisma generate
	docker-compose exec node /bin/bash -c "cd ./submodules/bootstrap; npm i; npm run css-compile"
	docker-compose exec node /bin/bash -c "cd ../www/static/local_modules/bootstrap; npm i; npm run css-compile"
	docker-compose exec node npm run build
	docker-compose restart node
	docker-compose restart node-server

rebuild-npm:
	docker-compose exec node /bin/bash -c "cd ../www/static; npm i --include=dev"

rebuild-frontend:
	docker-compose exec node /bin/bash -c "cd ../www/static; npm run gulp frontend:bem"
	docker-compose exec node /bin/bash -c "cd ../www/static; npm run gulp client:bem:css"
	docker-compose exec node /bin/bash -c "cd ../www/static; npm run gulp build:frontend"

rebuild-backend:
	docker-compose exec node /bin/bash -c "cd ../www/static; npm run gulp build:backend"

rebuild-old: rebuild-npm rebuild-frontend rebuild-backend

rebuild: rebuild-submodules rebuild-node rebuild-old

deploy: git-deploy composer clear-cache rebuild

email:
	docker-compose exec php php ./app/console.php Mail MailSender

