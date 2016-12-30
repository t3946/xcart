<?php
define("IS_MULTILANGUAGE", true);
define('USE_TRUSTED_POST_VARIABLES',1);
$trusted_post_variables = array("html");

$location[] = array("Banners");

require $xcart_dir."/include/security.php";

$connection = Xcart\Connection::getInstance();
$qb = \Mindy\QueryBuilder\QueryBuilder::getInstance($connection);

if (!isset($bannerid) && !empty($_POST['bannerid'])) {
    $bannerid = $_POST['bannerid'];
}

if (!empty($bannerid) || $mode == 'new')
{
    $mode = (empty($bannerid)) ? 'new' : 'edit';
    $section = 'form';


    if ($REQUEST_METHOD == 'POST')
    {
        $fast_action = ['Delete', 'Enable', 'Disable'];

        if (in_array($_POST['action'], $fast_action))
        {
            $qb->where(['id' => $bannerid]);

            switch ($_POST['action']) {
                case 'Delete': {
                    $qb->setTypeDelete()->from('xcart_banners');
                    break;
                }
                case 'Enable': {
                    $qb->setTypeUpdate()->update('xcart_banners',['enabled' => 'Y']);
                    break;
                }
                case 'Disable': {
                    $qb->setTypeUpdate()->update('xcart_banners',['enabled' => 'N']);
                    break;
                }
            }

            $connection->exec($qb->toSQL());
            func_header_location("/admin/configuration.php?option=Banners");
        }


        $errors = [];
        $type = $_POST['type'];

        if (empty($_POST['name']))                                  { $errors[] = 'Field "name" not be empty'; }
        if (empty($_POST['storefronts']))                           { $errors[] = 'Field "storefronts" not be empty'; }
        if (empty($_POST['start_at']))                              { $errors[] = 'Field "Date start" not be empty'; }
        if ($type == 'html' && empty($_POST['html']))               { $errors[] = 'Field "HTML" not be empty'; }

        if (empty($errors))
        {
            $params = [
                'enabled'       => $_POST['enabled'],
                'position'      => $_POST['position'],
                'name'          => $_POST['name'],
                'storefronts'   => $_POST['storefronts'],
                'type'          => $_POST['type'],
                'start_at'      => date('Y-m-d H:i:s', strtotime($_POST['start_at'])),
                'end_at'        => null
            ];

            if (!empty($_POST['end_at'])) {
                $params['end_at'] = date('Y-m-d H:i:s', strtotime($_POST['end_at']));
            }

            if ($type == 'html') {
                $params['html'] = $_POST['html'];
            }

            if ($mode == 'new')
            {
                $connection->exec($qb->insert('xcart_banners', $params));
                $bannerid = $connection->lastInsertId();

                func_header_location("/admin/configuration.php?option=Banners&bannerid={$bannerid}");
            }
            else {
                $sql = $qb->setTypeUpdate()->update('xcart_banners', $params)->where(['id' => $bannerid])->toSQL();
                $connection->exec($sql);
            }
        }
        else {
            $smarty->assign('errors', $errors);
        }
    }

    if ($mode == 'edit')
    {
        $sql = $qb->setTypeSelect()->from('xcart_banners')->where(['id' => $bannerid])->toSQL();

        if ($banner = $connection->query($sql)->fetch())
        {
            $smarty->assign('banner', $banner);
        }
        else {
            $section = $mode = 'error';
        }
    }

    $smarty->assign("mode", $mode);
}
else {
    $section = 'list';
    $sql = $qb->setTypeSelect()->select('*') ->from('xcart_banners')->toSQL();
    $smarty->assign("banners", $connection->query($sql)->fetchAll());
}



$smarty->assign("section", $section);
$smarty->assign("single_mode", $single_mode);
$smarty->assign("main","banners");
$smarty->assign("location", $location);

//@include $xcart_dir."/modules/gold_display.php";
//func_display("admin/home.tpl",$smarty);

