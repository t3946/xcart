# xcart
Before work on Branches setup these files:
- /config.local.php
```php
<?php
/**
 *  Only for old app
 */
$xcart_http_host = $_SERVER['HTTP_HOST'];
$xcart_https_host = $_SERVER['HTTP_HOST'];

$sql_host ='localhost';
$sql_db ='db_name';
$sql_user ='db_user';
$sql_password ='db_password';

$local_storefront = 0; // artistsupplysource.com
//      $local_storefront = 38; // www.tradeshowexhibitorsupply.com
//      $local_storefront = 34; // www.rfidlocksandmore.com
//      $local_storefront = 41; // kidstuffstation.com
//      $local_storefront = 56; // organiclifesource.com
//      $local_storefront = 12; // AAJewelry
//      $local_storefront = 52; // FM
//      $local_storefront = 59; // www.petsuppliesplace.com
//      $local_storefront = 50; // www.huntersupplysource.com
//      $local_storefront = 10; // www.teachersupplysource.com
//      $local_storefront = 35; // www.businesssupplysource.com
//      $local_storefront = 63; // www.justpokersupplies.com
//      $local_storefront = 42; // www.acuhealthcare.com

define('SMARTY_AUTO_RECOMPILE', true);
define('LOCAL_SF_DOMAIN', 'dev1.test.artistsupplysource.com');
define('LOCAL_SF_ID', $local_storefront);
```
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
- /files/product_feeds_v2/
- /files/reconciliation_feeds/

---
##Соглашение о разработке
- ####Именование
    > Иерархия папок отдельно взятого модуля 
    ```$xslt
    ModuleName
    |--Controllers/
    |--Models/
    |  |--NameModel.php (extend Model or AutoMetaModel)
    |--NameModeule.php (extend Module class)
    ```
    > Классы
      
    Model - Должен описывать функции ```static tableName() ``` и ``` static getFields ``` 
    - в ```getFields``` описываются все поля таблицы с соотвествием типа и опций поля. Должны быть описаны все поля таблицы, в противном случае будет сгенерирована ошибка
    - в ```tableName``` описывается имя таблицы в которой хранятся данные

    AutoMetaModel - позволяет оттказатся от полного описания всех полей таблицы, но необходимо описать ключевые поля, так-же следует помнить, что описание полей может быть приведено не корректно.
    
    
