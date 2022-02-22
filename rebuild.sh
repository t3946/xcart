./stop.sh
git clean -fd
git reset --hard
git pull origin dev
cd ./nextjs
npx prisma generate
npm run build
cd ../
./start.sh
