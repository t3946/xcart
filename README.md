# xcart

# Project hierarchy

```
xcart
├──app
│  └──include
│     └──Xcart
│        └──App ─ Mindy framework engine
│            ├──Behaviours ─ Field Behaviours
│            └──Template
│               └──TemplateManager.php ─ Fenom initial file(use it for fenom extesion)
```

# Mindy framework engine

## Fenom

Fenom is site template engine.

You can find it on [Github](https://github.com/fenom-template/fenom).

Fenom has good [Documentation](https://github.com/fenom-template/fenom/tree/master/docs).

## Field Behaviours

Determine strategy of form fields behaviour as what can and can't field does, his template rendering and validation.
This is similar to controllers but for fields.

Before work on Branches setup these files:

```php
<?php
return [
    'components' => [
        'db' => [
            'connections' => [
                'default' => [
                    'host' => 'localhost',
                    'dbname' => 'db_name',
                    'user' => 'db_user',
                    'password' => 'db_password',
                ]
            ]
        ]
    ]
];
```

- Static files build:
    - install `node.js` and `npm`
    - go in `/www/static/`
    - run `npm install`
    - run `npm run gulp watch:frontend` or `npm run gulp watch:backend` for building distribution static files.

---

# Development Agreements

## Layout

- Use BEM methodology form yandex company
- For BEM blocks and BEM elements to create new directory
- Don't use scss many-level css rules (rule inside rule)
- Don't use !important in styles

## Naming

> Module hierarchy example

```xslt
ModuleName

|--Controllers/
|--Models/
|  |--NameModel.php (extend Model or TreeModel)
|--NameModule.php (extend Module class)
```

> Классы

Model - Должен описывать функции ```static tableName() ``` и ``` static getFields ```

- в ```getFields``` описываются все поля таблицы с соотвествием типа и опций поля. Должны быть описаны все поля таблицы,
  в противном случае будет сгенерирована ошибка
- в ```tableName``` описывается имя таблицы в которой хранятся данные

AutoMetaTrait - позволяет оттказатся от полного описания всех полей таблицы, но необходимо описать ключевые поля, так-же
следует помнить, что описание полей может быть приведено не корректно.


