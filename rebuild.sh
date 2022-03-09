./stop.sh
git clean -fd
git fetch origin dev
git reset --hard origin/dev
cd ./nextjs
npx prisma generate
npm run build
cd ../
./start.sh
