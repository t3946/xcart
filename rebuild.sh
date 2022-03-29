bash -c "cd ./nextjs; git submodule init"
bash -c "cd ./nextjs; git submodule update"
bash -c "cd ./www/static; git submodule init"
bash -c "cd ./www/static; git submodule update"

docker-compose exec node npm i --include=dev
docker-compose exec node npx prisma generate
docker-compose exec node /bin/bash -c "cd ./submodules/bootstrap; npm i; npm run css-compile"
docker-compose exec node /bin/bash -c "cd ../www/static/local_modules/bootstrap; npm i; npm run css-compile"
docker-compose exec node npm run build
docker-compose restart node

docker-compose exec node /bin/bash -c "cd ../www/static; npm i --include=dev"
docker-compose exec node /bin/bash -c "cd ../www/static; npm run gulp frontend:bem"
docker-compose exec node /bin/bash -c "cd ../www/static; npm run gulp client:bem:css"
docker-compose exec node /bin/bash -c "cd ../www/static; npm run gulp build:frontend"
docker-compose exec node /bin/bash -c "cd ../www/static; npm run gulp build:backend"
