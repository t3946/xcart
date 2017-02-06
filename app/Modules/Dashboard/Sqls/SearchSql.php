<?php
namespace Modules\Dashboard\Sqls;

use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\QueryBuilder;
use Xcart\Connection;

class SearchSql
{
    public static function getDistributorSql()
    {
        return /** @lang MySQL */ <<<SQL
select manufacturerid as id, concat('[',code,'] ', manufacturer) as text 
from xcart_manufacturers 
where manufacturer like :like or code like :like
limit 50
SQL;
    }

    public static function getInDistributorSql($in)
    {
        return QueryBuilder::getInstance(Connection::getInstance())
                           ->select(['id' => 'manufacturerid' , 'text' => new Expression("CONCAT('[', code, '] ', manufacturer)") ])
                           ->from("xcart_manufacturers")
                           ->setAlias('t')
                           ->where(['t.manufacturerid__in' => $in])->toSQL();
    }

    public static function getOperatorSql()
    {
        return /** @lang MySQL */ <<<SQL
select login as id, concat('[',login,'] ', firstname) as text 
from xcart_customers 

where not usertype in ('C') and (login like :like or firstname like :like) 
ORDER BY login asc
limit 50
SQL;
    }

    public static function getInOperatorSql($in)
    {
        return QueryBuilder::getInstance(Connection::getInstance())
                           ->select(['id' => 'login' , 'text' => new Expression("CONCAT('[', login, '] ', firstname)") ])
                           ->from("xcart_customers")
                           ->setAlias('t')
                           ->where(['t.login__in' => $in])->toSQL();
    }

    public static function getCompanySql()
    {
        return /** @lang MySQL */ <<<SQL
(select company as id, company as text from xcart_orders where company like :like  GROUP BY company)
UNION 
(select s_company as id, s_company as text from xcart_orders where s_company like :like GROUP BY s_company)
UNION 
(select b_company as id, b_company as text from xcart_orders where b_company like :like  GROUP BY b_company)
limit 50
SQL;
    }

    public static function getCitySql()
    {
        return /** @lang MySQL */ <<<SQL
(select s_city as id, s_city as text from xcart_orders where s_city like :like  GROUP BY s_city)
UNION 
(select b_city as id, b_city as text from xcart_orders where b_city like :like GROUP BY b_city)
limit 50
SQL;
    }

    public static function getStateOrderSql()
    {
        return /** @lang MySQL */ <<<SQL
(select CONCAT(code, '__', country_code) as id, state as text from xcart_states where xcart_states.state like :like)
UNION 
(   
    select s_state as id, s_state as text 
    from xcart_orders as o
    where s_state like :like
      and not s_country in ('US')
      and not EXISTS (select * from xcart_states WHERE state = o.s_state or code = o.s_state)
    GROUP BY s_state
)
UNION 
(   
    select b_state as id, b_state as text 
    from xcart_orders as o
    where b_state like :like
      and not b_country in ('US')
      and not EXISTS (select * from xcart_states WHERE state = o.b_state or code = o.b_state)
    GROUP BY b_state
)
limit 50
SQL;
    }

    public static function getInStateOrderSql($in)
    {
        $codes = $countries = [];

        foreach ($in as $item) {
            list($codes[], $countries[]) = explode('__', $item);
        }
        $countries = array_filter($countries);

        return QueryBuilder::getInstance(Connection::getInstance())
                           ->select(['id' => new Expression("CONCAT(code, '__', country_code)"), 'text' => 'state' ])
                           ->from("xcart_states")
                           ->setAlias('t')
                           ->where(['t.code__in' => $codes, 't.country_code__in' => $countries])->toSQL();
    }

    public static function getCountryOrderSql()
    {
        return /** @lang MySQL */ <<<SQL
select DISTINCT c.code as id, l.value as text 
from xcart_countries as c
LEFT JOIN xcart_languages as l on l.name = CONCAT('country_', c.code) and l.code in ('US', 'en')
where l.value like :like
ORDER BY l.name, field(l.code, 'US', 'en') ASC
SQL;
    }

    public static function getInCountryOrderSql($in)
    {
        $sql = /** @lang MySQL */ <<<SQL
select DISTINCT c.code as id, l.value as text 
from xcart_countries as c
LEFT JOIN xcart_languages as l on l.name = CONCAT('country_', c.code) and l.code in ('US', 'en')
ORDER BY l.name, field(l.code, 'US', 'en') ASC
SQL;

        return QueryBuilder::getInstance(Connection::getInstance())
                           ->from("({$sql})")
                           ->setAlias('t')
                           ->where(['t.id__in' => $in])->toSQL();
    }

    public static function getStreetSql()
    {
        return /** @lang MySQL */ <<<SQL
(select s_address as id, s_address as text from xcart_orders where s_address like :like  GROUP BY s_address)
UNION 
(select b_address as id, b_address as text from xcart_orders where b_address like :like GROUP BY b_address)
limit 50
SQL;
    }

    public static function getPhoneFaxOrderSql()
    {
        return /** @lang MySQL */ <<<SQL
(select phone as id, phone as text from xcart_orders where phone RLIKE :like  GROUP BY phone)
UNION 
(select fax as id, fax as text from xcart_orders where fax RLIKE :like GROUP BY fax)
limit 50
SQL;
//        return /** @lang MySQL */ <<<SQL
//(select phone as id, phone as text from xcart_customers where phone like :like and usertype in ('C'))
//union
//(select fax as id, fax as text from xcart_customers where fax like :like and usertype in ('C'))
//union
//(select phone as id, phone as text from xcart_orders where phone like :like  GROUP BY phone)
//UNION
//(select fax as id, fax as text from xcart_orders where fax like :like GROUP BY fax)
//limit 50
//SQL;
    }

    public static function getEmailOrderSql()
    {
        return /** @lang MySQL */ <<<SQL
(select email as id, email as text from xcart_orders where email like :like GROUP BY email)
limit 50
SQL;
//        return /** @lang MySQL */ <<<SQL
//(select email as id, email as text from xcart_customers where email like :like and usertype in ('C'))
//UNION
//(select email as id, email as text from xcart_orders where email like :like GROUP BY email)
//limit 50
//SQL;
    }

    public static function getZipOrderSql()
    {
        return /** @lang MySQL */ <<<SQL
(select b_zipcode as id, b_zipcode as text from xcart_orders where b_zipcode RLIKE :like GROUP BY b_zipcode)
UNION 
(select s_zipcode as id, s_zipcode as text from xcart_orders where s_zipcode RLIKE :like GROUP BY s_zipcode)
limit 50
SQL;
//        return /** @lang MySQL */ <<<SQL
//(select b_zipcode as id, b_zipcode as text from xcart_customers where b_zipcode like :like and usertype in ('C'))
//UNION
//(select s_zipcode as id, s_zipcode as text from xcart_customers where s_zipcode like :like and usertype in ('C'))
//UNION
//(select b_zipcode as id, b_zipcode as text from xcart_orders where b_zipcode like :like GROUP BY b_zipcode)
//UNION
//(select s_zipcode as id, s_zipcode as text from xcart_orders where s_zipcode like :like GROUP BY s_zipcode)
//limit 50
//SQL;
    }

    public static function getCustomerNameSql()
    {
        return /** @lang MySQL */ <<<SQL
(select firstname as id, firstname as text from xcart_orders where firstname like :like GROUP BY firstname)
UNION 
(select s_firstname as id, s_firstname as text from xcart_orders where s_firstname like :like GROUP BY s_firstname)
UNION 
(select b_firstname as id, b_firstname as text from xcart_orders where b_firstname like :like GROUP BY b_firstname)
limit 50
SQL;
//        return /** @lang MySQL */ <<<SQL
//(select b_zipcode as id, b_zipcode as text from xcart_customers where b_zipcode like :like and usertype in ('C'))
//UNION
//(select s_zipcode as id, s_zipcode as text from xcart_customers where s_zipcode like :like and usertype in ('C'))
//UNION
//(select b_zipcode as id, b_zipcode as text from xcart_orders where b_zipcode like :like GROUP BY b_zipcode)
//UNION
//(select s_zipcode as id, s_zipcode as text from xcart_orders where s_zipcode like :like GROUP BY s_zipcode)
//limit 50
//SQL;
    }
}