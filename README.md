# xcart

## Сборка проекта
### Для всего проекта
Перейти в `/`
Выполнить
- `composer install`

### Новая часть (аккаунт)
Перейти в `/nextjs`
Выполнить
- `npm i`
- `git submodule init`
- `git submodule update`
- `npx prisma generate`

Перейти в `/nextjs/submodules/bootstrap`
Выполнить
- `npm i`
- `npm run css-compile`

Перейти в `/nextjs`
Выполнить
- `npm run build`


### Старая часть
Перейти в `/www/static`
Выполнить
- `npm i`
- `git submodule init`
- `git submodule update`

Перейти в `/www/static/local_modules/bootstrap`
Выполнить
- `npm i`
- `npm run css-compile`

#### Клиентская часть
Перейти в `/www/static`
Выполнить
- `npm run gulp frontend:bem`
- `npm run gulp build:frontend`

#### Админская часть
Перейти в `/dev/www/static`
Выполнить
- `npm run gulp build:backend`

## Mindy framework engine

### Fenom

Fenom is site template engine.

You can find it on [Github](https://github.com/fenom-template/fenom).

Fenom has good [Documentation](https://github.com/fenom-template/fenom/tree/master/docs).
