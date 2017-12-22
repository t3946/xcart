<?php

if (empty($per_page)) {
    $per_page = 30;
}
if (empty($page)) {
    $page = 1;
}

$objects_per_page = $per_page;
$queryBuilder = Xcart\Connection::getInstance()->createQueryBuilder();
$queryBuilder->select('SQL_CALC_FOUND_ROWS f.*', "(".
    Xcart\Connection::getInstance()->createQueryBuilder()->select('count(*)')
        ->from('xcart_external_verification_products_queue', 'q1')
        ->where('q1.feed_id = f.feed_id')
        ->getSQL(). ") as total", "(".
    Xcart\Connection::getInstance()->createQueryBuilder()->select('count(*)')
        ->from('xcart_external_verification_products_queue', 'q2')
        ->where('q2.feed_id = f.feed_id', "q2.amz_listing_status = 'submit_to_feed_failed'")
        ->getSQL(). ") as error", "(".
    Xcart\Connection::getInstance()->createQueryBuilder()->select('count(*)')
        ->from('xcart_external_verification_products_queue', 'q3')
        ->where('q3.feed_id = f.feed_id', "q3.amz_listing_status = 'submit_to_feed_success'")
        ->getSQL(). ") as success")
    ->from('xcart_external_verification_feeds', 'f')
    ->orderBy('feed_date', 'DESC');
$queryBuilder->setFirstResult(($page-1) * $per_page);
$queryBuilder->setMaxResults($per_page);
$state = $queryBuilder->execute();
$aRes = $state->fetchAll();

$total_items = intval(Xcart\Connection::getInstance()->executeQuery('SELECT FOUND_ROWS() AS foundRows')->fetchColumn(0));
$total_nav_pages = ceil($total_items/$objects_per_page)+1;

if (!empty($aRes)) {
    foreach ($aRes as $key => $a) {
        $aRes[$key]['Customer'] = \Xcart\Customer::model(['login' => $a['login']]);
    }
}

$smarty->assign('aFeeds', $aRes);
