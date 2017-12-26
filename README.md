# xcart
Before work on Branches setup these files:

- /app/config/settings_local.php
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

Create folders:
- /app/runtime
- /www/files/product_feeds_v2/
- /www/files/reconciliation_feeds/

---
##Соглашение о разработке
####Именование
> Иерархия папок отдельно взятого модуля 
```xslt
ModuleName

|--Controllers/
|--Models/
|  |--NameModel.php (extend Model or TreeModel)
|--NameModule.php (extend Module class)
```

> Классы
  
Model - Должен описывать функции ```static tableName() ``` и ``` static getFields ``` 
- в ```getFields``` описываются все поля таблицы с соотвествием типа и опций поля. Должны быть описаны все поля таблицы, в противном случае будет сгенерирована ошибка
- в ```tableName``` описывается имя таблицы в которой хранятся данные

AutoMetaTrait - позволяет оттказатся от полного описания всех полей таблицы, но необходимо описать ключевые поля, так-же следует помнить, что описание полей может быть приведено не корректно.


