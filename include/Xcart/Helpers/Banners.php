<?php
namespace Xcart\Helpers;


use Doctrine\DBAL\Driver\PDOConnection;
use Mindy\QueryBuilder\Database\Mysql\Adapter;
use Mindy\QueryBuilder\LookupBuilder\LookupBuilder;
use Mindy\QueryBuilder\QueryBuilderFactory;

class Banners
{
    /***
     * @param $params array
     * @param $smarty \Smarty
     * @return string
     */
    public static function getBannerSmarty($params, $smarty)
    {
        $return = self::getBanner($params['position'], $params['page']);

        if (isset($params['assign']))
        {
            $smarty->assign($params['assign'], $return);
            $return = false;
        }

        return $return;
    }

    /***
     * @param $position
     * @param $page
     * @return string
     */
    public static function getBanner($position, $page)
    {
        global $config, $sql_tbl, $site_domain;

        $sql = /** @lang MySQL */ <<<SQL
select b.* 
from xcart_banners as b

join (
    select distinct 'AR' as value
    from xcart_storefronts_config
    where 'www.artistsupplysource.com' = '{$site_domain}'
    union
    select value from xcart_storefronts sf
    join xcart_storefronts_config sfc 
      on sf.storefrontid = sfc.storefrontid  
      and sfc.name like '%prefix%' 
    where sf.domain = '{$site_domain}'
) 
as sfc on UPPER(b.storefronts) like concat('%', replace(sfc.value, '-', ''), '%')
  
where b.position = '{$position}'
  and (b.pages like '%{$page}%' or (b.pages = '' or b.pages is null))
  and b.start_at <= NOW()
  and (b.end_at >= NOW() or (b.end_at = '' or b.end_at is null))
  and b.enabled = 'Y'
SQL;
        $res = db_query($sql);
        $data = [];
        while ($row = db_fetch_array($res))
        {
            if ($row['type'] == 'image')
            {
                $data[] = <<<HTML
<div class="banner">
    <a href="{$row['url']}">
        <img src="{$row['url']}" alt="{$row['name']}">
    </a>
</div>
HTML;
            }
            else {
                $html = stripslashes(html_entity_decode($row['html']));
                $data[] = "<div class='banner'>{$html}</div>";
            }
        }

        $data = implode(' ', $data);
        $data = "<div class='banners'>{$data}</div>";
        return $data;
    }
}